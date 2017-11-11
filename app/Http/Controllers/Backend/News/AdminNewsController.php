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

        if($request->file('news_image'))
        {
            $imageName  = rand(11111, 99999) . '_news.' . $request->file('news_image')->getClientOriginalExtension();
            $request->file('news_image')->move(base_path() . '/public/uploads/posts/', $imageName);
            $input = array_merge($request->all(), ['news_image' => $imageName]);
        }

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
        
        if($request->file('news_image'))
        {
            $imageName  = rand(11111, 99999) . '_news.' . $request->file('news_image')->getClientOriginalExtension();
            $request->file('news_image')->move(base_path() . '/public/uploads/posts/', $imageName);
            $input = array_merge($request->all(), ['news_image' => $imageName]);
        }
        
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
             ->addColumn('news_image', function ($item) 
            {
                if($item->news_image && file_exists(base_path() . '/public/uploads/posts/'.$item->news_image))
                {
                    return  Html::image('/uploads/posts/'.$item->news_image, $item->news_image, ['width' => 60, 'height' => 60]);
                }
                else
                {
                    return  Html::image('/uploads/posts/default.png', $item->news, ['width' => 60, 'height' => 60]);    
                }
            })
            ->addColumn('actions', function ($item)
            {
                return $item->action_buttons;
            })
		    ->make(true);
    }
}
