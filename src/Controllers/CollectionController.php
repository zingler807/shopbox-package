<?php

namespace Laracle\ShopBox\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laracle\ShopBox\Models\Collection;
use MeiliSearch\Client;
use Log;

class CollectionController extends Controller
{
  public function index(Request $request)
  {
    if (isset($request->filters) && isset($request->filters['search'])) {
      return Collection::search($request->filters['search'])->paginate(20);
    }
    return Collection::paginate(20);
  }

  public function search(Request $request)
  {   
      $client = new Client(Config('shopbox.meilisearch_host'), Config('shopbox.meilisearch_master_key'));
      $payload = [];
      $keywords = $request->input('keywords', '');
      try{
          $client->createIndex('collections', ['primaryKey' => 'collection_id']);
          $collections = Collection::where([])->get();
          foreach($collections as $collection)
              $payload[] = ['collection_id' => $collection->id, 'title' => $collection->name];
          $index = $client->index('collections');
          $result = $index->addDocuments($payload);
      } catch (\MeiliSearch\Exceptions\ApiException $e) {}
      $index = $client->index('collections');
      $hits = $index->search($keywords)->getHits();
      return $hits;
  }

  public function store(Request $request)
  {


    $validatedData = $request->validate([
        'name' => 'required|max:255',
    ],[
      'name.required' => 'Please give your collection a name',
    ]);



    $collection = Collection::updateOrCreate(
        ['id' => $request->id],
        $request->except('products','images','tags')
    );



    $collection->addImages($request->file('images'));


    if ($request->products[0] !== NULL){
        $products = [];
        foreach ($request->products as $index => $product) {
            $order = (isset($product['order'])) ? $product['order'] : $index;
            $products[$product['id']] = ['order'=>$order];
        }


        $collection->products()->sync($products);
    }

    $collection->syncTags($request->tags);

    return $collection;


  }

  public function show(Collection $collection, Request $request){
      return $collection->load('products');
  }

  public function destroy(Collection $collection, Request $request){
        $collection->delete();
        return [1=>2];
  }
}
