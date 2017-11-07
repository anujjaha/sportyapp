<?php

namespace App\Http\Controllers\Backend\News;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\News\EloquentNewsRepository;
use Html;

/**
 * Class AdminGifController
 */
class AdminNewsController extends Controller
{
	/**
	 * __construct
	 * 
	 * @param EloquentEventRepository $eventRepository
	 */
	public function __construct()
	{
	   $this->repository = new EloquentNewsRepository;	
	}

    /**
     * Listing 
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('backend.news.index')->with(['repository' => $this->repository]);
    }

    /**
     * Event View
     * 
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('backend.news.create');
    }

    public function show()
    {

    }

    /**
     * Store View
     * 
     * @return \Illuminate\View\View
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $this->repository->create($input);

        return redirect()->route('admin.news.index')->withFlashSuccess('News Created Successfully');
    }

    /**
     * Event View
     * 
     * @return \Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $gif = $this->repository->findOrThrowException($id);

        return view('backend.news.edit')->with(['item' => $gif]);
    }

    /**
     * Event Update
     * 
     * @return \Illuminate\View\View
     */
    public function update($id, Request $request)
    {
        $input = $request->all();
        
        $status = $this->repository->update($id, $input);
        
        return redirect()->route('admin.news.index');
    }

    /**
     * Event Update
     * 
     * @return \Illuminate\View\View
     */
    public function destroy($id)
    {
        $status = $this->repository->destroy($id);
        
        return redirect()->route('admin.news.index');
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
            ->escapeColumns(['news', 'sort'])
            ->addColumn('actions', function ($item)
            {
                return $item->action_buttons;
            })
		    ->make(true);
    }
}
