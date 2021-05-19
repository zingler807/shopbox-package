<?php
namespace Laracle\ShopBox\Traits;

use Illuminate\Support\Facades\Schema;
use Image;
use Laracle\ShopBox\Models\Image as Media;
use Storage;

use Log;

trait ImageTrait
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder|static $query
     * @param string $keyword
     * @param boolean $matchAllFields
     */

     // URLS can be formed by visitng https://shopbox.dev/image/02032021115107601a8e2b47e8e.jpg
     // Size 400, 1024, thumb,
     public $imageModels = [];

     public function addImages($images){

        if (!isset($images[0])) { return; }

       $sizes = [400,1024];


       foreach ($images as $index => $image) {
         //Log::info($image);

         //Log::info($resize);
         $name = date('mdYHis') . uniqid();

         // Create original
         $resize = Image::make($image)->encode('jpg');
         $store  = Storage::disk('public')->put($name.'.jpg', $resize->__toString());
         self::addImage($name.'.jpg',$resize->width(),$resize->height(), $index);

         // Create thumbnail
         $resize = Image::make($image)->fit(100)->encode('jpg');
         $store  = Storage::disk('public')->put($name.'-thumb.jpg', $resize->__toString());
         //self::addImage($name.'-thumb.jpg',$resize->width(),$resize->height());

         // Create Other sizes
         foreach ($sizes as $size) {
           $resize = Image::make($image)->encode('jpg');
           $resize->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('jpg');
           $store  = Storage::disk('public')->put($name.'-'.$size.'.jpg', $resize->__toString());
           //self::addImage($name.'-'.$size.'.jpg',$resize->width(),$resize->height());
         }


       }


       $this->images()->attach($this->imageModels);


     }

     public function addImage($path,$width,$height, $index){
       $imageModel = Media::firstOrCreate([
         'path' => $path,
         'width' => $width,
         'height' => $height,
         'order' => $index
       ]);
       array_push($this->imageModels,$imageModel->id);
     }

     public function images(){
       return $this->morphToMany(Media::class,'imageable')->orderBy('order');
     }


}
 ?>
