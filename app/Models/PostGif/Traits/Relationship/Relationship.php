<?php namespace App\Models\PostGif\Traits\Relationship;

use App\Models\Access\User\User;
use App\Models\Post\Post;

trait Relationship
{
	/**
	 * Relationship Mapping for Account
	 * @return mixed
	 */
	public function user()
	{
	    return $this->belongsTo(User::class, 'user_id');
	}

	/**
	 * Relationship Mapping for Post
	 * @return mixed
	 */
	public function post()
	{
	    return $this->belongsTo(Post::class, 'post_id');
	}
}