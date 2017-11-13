<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Repositories\Post\EloquentPostRepository;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('backend.dashboard');
    }

    public function timeline(Request $request)
    {
    	$userId = Auth()->user()->id;
    	$obj = new EloquentPostRepository();
    	$posts = $obj->getAllPosts($userId);

    	return view('backend.posts.index')->with(['posts' => $posts]);
    }
}
