<?php namespace App\Models\Post\Traits\Relationship;

use App\Models\Access\User\User;
use App\Models\Post\PostComment;

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
	 * Post Likes
	 * 
	 * @return mixed
	 */
	public function post_likes()
	{
	    return $this->belongsToMany(User::class, 'post_likes', 'post_id',  'user_id');
	}

	/**
	 * Post Comments
	 * 
	 * @return mixed
	 */
	public function post_comments()
	{
	    return $this->hasMany(PostComment::class, 'post_id');
	}
}