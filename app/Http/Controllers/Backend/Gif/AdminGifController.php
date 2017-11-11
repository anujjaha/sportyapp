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
    	return view('backend.gif.create');
    }

    /**
     * Store View
     * 
     * @return \Illuminate\View\View
     */
    public function store(Request $request)
    {
        $input = $request->all();

        if($request->file('gif'))
        {
            $imageName  = rand(11111, 99999) . '_gif.' . $request->file('gif')->getClientOriginalExtension();
            $request->file('gif')->move(base_path() . '/public/uploads/gif/', $imageName);
            $input = array_merge($request->all(), ['gif' => $imageName]);
        }
        else
        {
            return redirect()->route('admin.gif.index')->withFlashDanger('Unable to create New GIF ! Please try again.');            
        }

        $this->repository->create($input);

        return redirect()->route('admin.gif.index')->withFlashSuccess('GIF Created Successfully');
    }

    /**
     * Event View
     * 
     * @return \Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $gif = $this->repository->findOrThrowException($id);

        return view('backend.gif.edit')->with(['item' => $gif]);
    }

    /**
     * Event Update
     * 
     * @return \Illuminate\View\View
     */
    public function update($id, Request $request)
    {
        $input = $request->all();
        
        if($request->file('gif'))
        {
            $imageName  = rand(11111, 99999) . '_gif.' . $request->file('gif')->getClientOriginalExtension();
            $request->file('gif')->move(base_path() . '/public/uploads/gif/', $imageName);
            $input = array_merge($request->all(), ['gif' => $imageName]);
        }

        $status = $this->repository->update($id, $input);
        
        return redirect()->route('admin.gifs.index');
    }

    /**
     * Event Update
     * 
     * @return \Illuminate\View\View
     */
    public function destroy($id)
    {
        $status = $this->repository->destroy($id);
        
        return redirect()->route('admin.gif.index');
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
