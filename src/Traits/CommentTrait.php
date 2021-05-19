<?php
namespace Laracle\ShopBox\Traits;

use Illuminate\Support\Facades\Schema;
use Laracle\ShopBox\Models\Comment;
use Log;

trait CommentTrait
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder|static $query
     * @param string $keyword
     * @param boolean $matchAllFields
     */
     public function comment($comment){
       $comment = Comment::create($comment->all());
       $comment->load('user');
       $this->comments()->attach($comment->id);
        return $comment;
     }

     public function comments(){
       return $this->morphToMany(Comment::class,'commentable');
     }
}
 ?>
