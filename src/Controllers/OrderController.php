<?php

namespace Laracle\ShopBox\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laracle\ShopBox\Models\Order;

class OrderController extends Controller
{
  public function index(Request $request)
  { 
    /*
    if (isset($request->filters) && isset($request->filters['search'])) {
      return Order::search($request->filters['search'])->paginate(20);
    }
    return Order::paginate(20);
    */

    $query = Order::query()->search($request->filters['search']);

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

  public function store(Request $request)
  {


    $order = Order::updateOrCreate(
        ['id' => $request->id],
        $request->except(['tags','customer','lines'])
    );

    $order->syncTags($request->tags);

    return $order;

  }

  public function show(Order $order, Request $request){
      return $order->load('lines');
  }

  public function destroy(Order $order, Request $request){
        $order->delete();
        return [1=>2];
  }

  public function addComment(Order $order, Request $request){
      $comment = $order->comment($request);
      return $comment;
  }
  public function getComments(Order $order, Request $request){
      return $order->comments()->orderBy('created_at','DESC')->paginate(8);
  }

  public function orderLookup(Request $request){

    if (!isset($request->id)) { return false; }
    return Order::where('ref','#'.$request->id)->firstOrFail();
  }
}
