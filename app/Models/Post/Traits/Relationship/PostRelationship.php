<?php namespace App\Models\Post\Traits\Relationship;

use App\Models\Access\User\User;
use App\Models\Post\Post;

trait PostRelationship
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
	 * Relationship Mapping for Account
	 * @return mixed
	 */
	public function post_detail()
	{
	    return $this->belongsTo(Post::class, 'post_id');
	}
}

