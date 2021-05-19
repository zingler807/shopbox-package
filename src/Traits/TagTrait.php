<?php
namespace Laracle\ShopBox\Traits;

use Illuminate\Support\Facades\Schema;
use Laracle\ShopBox\Models\Tag;
use Log;

trait TagTrait
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder|static $query
     * @param string $keyword
     * @param boolean $matchAllFields
     */
     public function syncTags($tags){


       if (!isset($tags[0])) { $this->tags()->detach(); return; }

       $tagArr = [];



       foreach ($tags as $tag) {

           if (!isset($tag['id'])) {
               $new = Tag::firstOrCreate([
                 'name' => $tag['name']
               ]);
               array_push($tagArr,$new);
           } else{
             array_push($tagArr,$tag);
           }
       }

       $tags = collect($tagArr)->pluck('id')->toArray();


       return $this->tags()->sync($tags);
     }

     public function tags(){
       return $this->morphToMany(Tag::class,'taggable');
     }

    //static::getSearchFields()
}
 ?>
