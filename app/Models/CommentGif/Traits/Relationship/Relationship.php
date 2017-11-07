<?php namespace App\Models\CommentGif\Traits\Relationship;

use App\Models\Access\User\User;
use App\Models\Post\PostComment;

trait Relationship
{
	/**
	 * Relationship Mapping for Post
	 * @return mixed
	 */
	public function comment()
	{
	    return $this->belongsTo(PostComment::class, 'comment_id');
	}
}