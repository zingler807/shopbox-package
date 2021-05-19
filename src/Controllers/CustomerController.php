<?php
namespace Laracle\ShopBox\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laracle\ShopBox\Models\Customer;
use Laracle\ShopBox\Models\Comment;
use Log;


class CustomerController extends Controller
{
  public function index(Request $request)
  {

    $query = Customer::query()->search($request->filters['search']);

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

    $validatedData = $request->validate([
        'first_name' => 'required|max:255',
        'email' => 'required|email|unique:customers,email,'.$request->id,
    ],[
      'first_name.required' => 'Please enter a first name for the customer',
      'email.required' => 'Please enter a valid email for the customer',
      'email.unique' => 'A customer with that email address already exists'
    ]);

    $customer = Customer::updateOrCreate(
        ['id' => $request->id],
        $request->except('tags')
    );

    $customer->syncTags($request->tags);

    return $customer;

  }

  public function show(Customer $customer, Request $request){
      return $customer;
  }

  public function destroy(Customer $customer, Request $request){
        $customer->delete();
        return [1=>2];
  }

  public function addComment(Customer $customer, Request $request){
      $comment = $customer->comment($request);
      return $comment;
  }
  public function getComments(Customer $customer, Request $request){
      return $customer->comments()->orderBy('created_at','DESC')->paginate(8);
  }
}
