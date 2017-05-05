<?php

namespace App\Repositories\Backend\Access\User;

use App\Models\Access\User\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use App\Events\Backend\Access\User\UserCreated;
use App\Events\Backend\Access\User\UserDeleted;
use App\Events\Backend\Access\User\UserUpdated;
use App\Events\Backend\Access\User\UserRestored;
use App\Events\Backend\Access\User\UserDeactivated;
use App\Events\Backend\Access\User\UserReactivated;
use App\Events\Backend\Access\User\UserPasswordChanged;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Events\Backend\Access\User\UserPermanentlyDeleted;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = User::class;

    /**
     * @var RoleRepository
     */
    protected $role;

    /**
     * @param RoleRepository $role
     */
    public function __construct()
    {
        $this->role = new RoleRepository();
    }

    /**
     * @param        $permissions
     * @param string $by
     *
     * @return mixed
     */
    public function getByPermission($permissions, $by = 'name')
    {
        if (! is_array($permissions)) {
            $permissions = [$permissions];
        }

        return $this->query()->whereHas('roles.permissions', function ($query) use ($permissions, $by) {
            $query->whereIn('permissions.'.$by, $permissions);
        })->get();
    }

    /**
     * @param        $roles
     * @param string $by
     *
     * @return mixed
     */
    public function getByRole($roles, $by = 'name')
    {
        if (! is_array($roles)) {
            $roles = [$roles];
        }

        return $this->query()->whereHas('roles', function ($query) use ($roles, $by) {
            $query->whereIn('roles.'.$by, $roles);
        })->get();
    }

    /**
     * @param int  $status
     * @param bool $trashed
     *
     * @return mixed
     */
    public function getForDataTable($status = 1, $trashed = false)
    {
        /**
         * Note: You must return deleted_at or the User getActionButtonsAttribute won't
         * be able to differentiate what buttons to show for each row.
         */
        $dataTableQuery = $this->query()
            ->with('roles')
            ->select([
                config('access.users_table').'.id',
                config('access.users_table').'.name',
                config('access.users_table').'.email',
                config('access.users_table').'.status',
                config('access.users_table').'.confirmed',
                config('access.users_table').'.created_at',
                config('access.users_table').'.updated_at',
                config('access.users_table').'.deleted_at',
            ]);

        if ($trashed == 'true') {
            return $dataTableQuery->onlyTrashed();
        }

        // active() is a scope on the UserScope trait
        return $dataTableQuery->active($status);
    }

    /**
     * @param Model $input
     */
    public function create($input)
    {
        $data = $input['data'];
        $roles = $input['roles'];

        $user = $this->createUserStub($data);

        DB::transaction(function () use ($user, $data, $roles) {
            if ($user->save()) {

                //User Created, Validate Roles
                if (! count($roles['assignees_roles'])) {
                    throw new GeneralException(trans('exceptions.backend.access.users.role_needed_create'));
                }

                //Attach new roles
                $user->attachRoles($roles['assignees_roles']);

                //Send confirmation email if requested
                if (isset($data['confirmation_email']) && $user->confirmed == 0) {
                    $user->notify(new UserNeedsConfirmation($user->confirmation_code));
                }

                event(new UserCreated($user));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.create_error'));
        });
    }

    /**
     * @param Model $user
     * @param array $input
     *
     * @return bool
     * @throws GeneralException
     */
    public function update(Model $user, array $input)
    {
        $data = $input['data'];
        $roles = $input['roles'];

        $this->checkUserByEmail($data, $user);

        DB::transaction(function () use ($user, $data, $roles) {
            if ($user->update($data)) {
                //For whatever reason this just wont work in the above call, so a second is needed for now
                $user->status = isset($data['status']) ? 1 : 0;
                $user->confirmed = isset($data['confirmed']) ? 1 : 0;
                $user->save();

                $this->checkUserRolesCount($roles);
                $this->flushRoles($roles, $user);

                event(new UserUpdated($user));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.update_error'));
        });
    }

    /**
     * @param Model $user
     * @param $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function updatePassword(Model $user, $input)
    {
        $user->password = bcrypt($input['password']);

        if ($user->save()) {
            event(new UserPasswordChanged($user));

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.update_password_error'));
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Model $user)
    {
        if (access()->id() == $user->id) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_delete_self'));
        }

        if ($user->delete()) {
            event(new UserDeleted($user));

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     */
    public function forceDelete(Model $user)
    {
        if (is_null($user->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.delete_first'));
        }

        DB::transaction(function () use ($user) {
            if ($user->forceDelete()) {
                event(new UserPermanentlyDeleted($user));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
        });
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function restore(Model $user)
    {
        if (is_null($user->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_restore'));
        }

        if ($user->restore()) {
            event(new UserRestored($user));

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.restore_error'));
    }

    /**
     * @param Model $user
     * @param $status
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function mark(Model $user, $status)
    {
        if (access()->id() == $user->id && $status == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_deactivate_self'));
        }

        $user->status = $status;

        switch ($status) {
            case 0:
                event(new UserDeactivated($user));
            break;

            case 1:
                event(new UserReactivated($user));
            break;
        }

        if ($user->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.mark_error'));
    }

    /**
     * @param  $input
     * @param  $user
     *
     * @throws GeneralException
     */
    protected function checkUserByEmail($input, $user)
    {
        //Figure out if email is not the same
        if ($user->email != $input['email']) {
            //Check to see if email exists
            if ($this->query()->where('email', '=', $input['email'])->first()) {
                throw new GeneralException(trans('exceptions.backend.access.users.email_error'));
            }
        }
    }

    /**
     * @param $roles
     * @param $user
     */
    protected function flushRoles($roles, $user)
    {
        //Flush roles out, then add array of new ones
        $user->detachRoles($user->roles);
        $user->attachRoles($roles['assignees_roles']);
    }

    /**
     * @param  $roles
     *
     * @throws GeneralException
     */
    protected function checkUserRolesCount($roles)
    {
        //User Updated, Update Roles
        //Validate that there's at least one role chosen
        if (count($roles['assignees_roles']) == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.role_needed'));
        }
    }

    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function createUserStub($input)
    {
        $user = self::MODEL;
        $user = new $user();
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = bcrypt($input['password']);
        $user->status = isset($input['status']) ? 1 : 0;
        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->confirmed = isset($input['confirmed']) ? 1 : 0;

        return $user;
    }
    
    /**
     * 
     * @param type $postData
     * @return User|boolean
     */
    public function createAppUser($postData) {
        $user = self::MODEL;
        $user = new $user();   
        $user->username     = $postData['username'];
        $user->name         = $postData['name'];
        $user->email        = isset($postData['email']) ? $postData['email'] : '';
        $user->password     = bcrypt($postData['password']);
        $user->status       = 1;
        $user->confirmed    = 1;  
        $destinationFolder  = public_path().'/uploads/users';
        if (isset($postData['image']) && $postData['image']) { 
            $extension = $postData['image']->getClientOriginalExtension();
            $fileName = rand(11111,99999).'.'.$extension;
            if($postData['image']->move($destinationFolder, $fileName))
            {
                $user->image = $fileName;
            }                       
        }
        /*
         * get User role details
         */
        $roleDetails = $this->role->getDefaultUserRole();
        if ($user->save()) {
            $user->attachRole($roleDetails);
            return $user;
        } else {
            return false;
        }
    }
    
    public function checkEmailAlreadyExist($email) {
        $result = $this->query()
                ->where('email', '=', $email)
                ->count();
        if ($result > 0) {
            return false;
        } else {
            return true;
        }
    }
    
    public function checkUserNameAlreadyExist($username) {
        $result = $this->query()
                ->where('username', '=', $username)
                ->count();
        if ($result > 0) {
            return false;
        } else {
            return true;
        }
    }
    
    public function createFbUser($param) {
        $isUserExist = false;
        if(isset($param['email']) && $param['email'])
        {
            $user = $this->query()
                ->where('email', '=', $param['email'])
                ->get()->first();
            if($user)
            {
                $isUserExist = true;
            }            
        }
        else
        {
            $user = $this->query()
                    ->where('fb_id', '=', $param['id'])
                    ->get()->first();
            if($user)
            {
                $isUserExist = true;
            }
        }
        
        if(!$isUserExist)
        {
            $user = self::MODEL;
            $user = new $user();
        }
        if(isset($param['name']) && $param['name'])
        {
            $user->name = $param['name'];
        }
        if(isset($param['email']) && $param['email'])
        {
            $user->email = $param['email'];
        }
        $user->fb_id        = $param['id'];
        $user->status       = 1;
        $user->confirmed    = 1; 
        
        /*
         * get User role details
         */
        $roleDetails = $this->role->getDefaultUserRole();
        if ($user->save()) {
            if(!$isUserExist)
            {
                $user->attachRole($roleDetails);
            }            
            return $user;
        } else {
            return false;
        }
    }
    
    public function updateAppUser($user, $param)
    {
        if(isset($param['name']) && $param['name'])
        {
            $user->name = $param['name'];
        }
        if(isset($param['email']) && $param['email'])
        {
            $user->email = $param['email'];
        }
        if(isset($param['location']) && $param['location'])
        {
            $user->location = $param['location'];
        }
        if(isset($param['password']) && $param['password'])
        {
            $user->email = bcrypt($param['password']);
        }
        $destinationFolder  = public_path().'/uploads/users';
        if (isset($param['image']) && $param['image']) { 
            $extension = $param['image']->getClientOriginalExtension();
            $fileName = rand(11111,99999).'.'.$extension;
            if($param['image']->move($destinationFolder, $fileName))
            {
                $user->image = $fileName;
            }                       
        }
        if ($user->save()) {            
            return $user;
        } else {
            return false;
        }
    }
    
    public function getAppUserList($param, $user)
    {
        $users = $this->query()
                ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('roles.name','=', config('access.users.default_role'))
                ->where('users.id', '!=', $user->id)
                ->select('users.*')
                ->orderBy('users.name');
        if(isset($param['search']) && $param['search'])
        {
            $users = $users->where('users.name', 'LIKE', $param["search"].'%');
        }
        return $users->get();
    }
}
