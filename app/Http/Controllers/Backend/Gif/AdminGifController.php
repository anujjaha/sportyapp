<?php

namespace App\Http\Controllers\Backend\Gif;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Gif\EloquentGifRepository;
use Html;

/**
 * Class AdminGifController
 */
class AdminGifController extends Controller
{
	/**
	 * __construct
	 * 
	 * @param EloquentEventRepository $eventRepository
	 */
	public function __construct()
	{
	   $this->repository = new EloquentGifRepository;	
	}

    /**
     * Listing 
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('backend.gif.index')->with(['repository' => $this->repository]);
    }

    /**
     * Event View
     * 
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
    	return view('backend.event.create');
    }

    /**
     * Store View
     * 
     * @return \Illuminate\View\View
     */
    public function store(Request $request)
    {
        $this->repository->create($request->all());

        return redirect()->route('admin.event.index');
    }

    /**
     * Event View
     * 
     * @return \Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $event = $this->repository->findOrThrowException($id);

        return view('backend.event.edit')->with(['item' => $event]);
    }

    /**
     * Event Update
     * 
     * @return \Illuminate\View\View
     */
    public function update($id, Request $request)
    {
        $status = $this->repository->update($id, $request->all());
        
        return redirect()->route('admin.event.index');
    }

    /**
     * Event Update
     * 
     * @return \Illuminate\View\View
     */
    public function destroy($id)
    {
        $status = $this->repository->destroy($id);
        
        return redirect()->route('admin.event.index');
    }

  	/**
     * Get Table Data
     *
     * @return json|mixed
     */
    public function getTableData()
    {
        return Datatables::of($this->repository->getForDataTable())
		    ->escapeColumns(['id', 'sort'])
            ->addColumn('gif', function ($item) 
            {
                
                return  Html::image('/uploads/gif/'.$item->gif, 'test', ['width' => 60, 'height' => 60]);    
            })
		    ->addColumn('actions', function ($item) {
                return $item->action_buttons;
            })
		    ->make(true);
    }
}
