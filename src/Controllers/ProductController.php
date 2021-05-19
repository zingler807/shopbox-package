<?php

namespace Laracle\ShopBox\Controllers;

use Laracle\ShopBox\Models\Product;
use Laracle\ShopBox\Models\Variant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use DB;

//https://github.com/spatie/laravel-translatable
//https://spatie.be/docs/laravel-medialibrary/v8/introduction

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {



      $query = Product::query()->search($request->filters['search']);

      if (!isset($request->filters['filter'])) {
        return $query->paginate(20);
      }


      if ($request->filters['filter']['key'] !== 'all' &&  $request->filters['filter']['key'] !== 'trashed' ) {
            $filter = [
              $request->filters['filter']['key'],
              $request->filters['filter']['operator'],
              $request->filters['filter']['value']
          ];
      }

      if ($request->filters['filter']['key'] == 'all') {
          $query->withTrashed();
      } else if($request->filters['filter']['key'] == 'trashed'){
        $query->onlyTrashed();
      } else {
        $query->where([$filter]);
      }

      $query->orderBy($request->filters['sort']['key'],$request->filters['sort']['order']);

      return $query->paginate(20);


    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      $validatedData = $request->validate([
          'title' => 'required|max:255'
      ],[
        'title.required' => 'Give your product a title',
        'title.max' => 'The title can be no larger than 255 characters'
      ]);



      $product = Product::updateOrCreate(
          ['id' => $request->id],
          $request->only(['title','description','status','has_variants','options','seo_title','seo_handle','seo_description','vendor','product_type'])
      );


      $product->addImages($request->file('images'));
      $product->syncTags($request->tags);


      $data = $request->only(['title','price','compare_price','sku','track_qty','qty','continue_selling','weight','weight_unit']);
      $defaultVariant = Variant::updateOrCreate(
          ['product_id' => $product->id,'default_variant'=>TRUE],
          $request->only(['title','price','compare_price','sku','track_qty','qty','continue_selling','weight','weight_unit'])
      );



      if ($request->variants[0] == NULL) { return $product->fresh(); }

      $allVariants = Variant::where('product_id',$product->id)->where('default_variant',false)->pluck('id');
      $updatedVariants = [];
      foreach ($request->variants as $variant) {


            $variant['title'] = $product->title;
            $variant['product_id'] = $product->id;

            if (isset($variant['id'])) {
              Variant::find($variant['id'])->update($variant);
              array_push($updatedVariants,$variant['id']);
            } else {
              Variant::create($variant);
            }
      }

      // Delete any variants that have been removed
      $dif = array_diff($allVariants->toArray(),$updatedVariants);
      Variant::whereIn('id',$dif)->delete();


      // Add variants

      /*
      Variant::where('product_id',$product->id)->where('default_variant',false)->delete();

      if (isset($request->variants) && $request->variants[0] !== NULL) {
        $varArr = [];
        foreach ($request->variants as $variant) {
          $variant['option'] = $variant['title'];
          $variant['title'] = $product->title;
          array_push($varArr, new Variant($variant));
        }
        $product->variants()->saveMany($varArr);
      }
      */


      return $product->fresh();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {

      return $product;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {

      $validatedData = $request->validate([
          'title' => 'required|max:255'
      ],[
        'title.required' => 'Give your product a title',
        'title.max' => 'The title can be no larger than 255 characters'
      ]);

      $product->update($request->all());

      return $product->fresh();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }

    public function types(){

      return DB::table('products')->whereNotNull('product_type')->distinct('product_type')->get('product_type')->pluck('product_type');
    }

    public function vendors(){
      return DB::table('products')->whereNotNull('vendor')->distinct('vendor')->get('vendor')->pluck('vendor');
    }
}
