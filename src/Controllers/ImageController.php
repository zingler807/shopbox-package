<?php

namespace Laracle\ShopBox\Controllers;

use Laracle\ShopBox\Models\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

//https://github.com/spatie/laravel-translatable
//https://spatie.be/docs/laravel-medialibrary/v8/introduction

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {



    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $image)
    {
        //return $request;
        $path = storage_path('app/public/'.$image);
        if ($request->size) {
          $path = str_replace('.jpg','-'.$request->size.'.jpg',$path);
        }

        return response()->file($path);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
      $image->alt = $request->alt;
      $image->caption = $request->caption;
      $image->save();
      return $image;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {

        $image->delete();
        return ['success'=>1];
    }

    public function changeOrder(Request $request){


        foreach ($request->items as $image) {
          Image::find($image['id'])->update(['order'=>$image['order']]);
        }
          //$image->update($request->items);
    //  Log::info($request);
    }
}
