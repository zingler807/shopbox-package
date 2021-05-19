<?php

namespace Laracle\ShopBox\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laracle\ShopBox\Models\Discount;
use Log;
use Carbon\Carbon;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
      if (isset($request->filters) && isset($request->filters['search'])) {
        return Discount::search($request->filters['search'])->paginate(20);
      }
      return Discount::paginate(20);
    }

    public function store(Request $request)
    {



      $validatedData = $request->validate([
          'code' => 'required|max:255|unique:discounts,code,'.$request->id,
          'type' => 'required',
          'usage_limit' => 'required',
          'value' => 'required_if:type,fixed',
          'percentage' => 'required_if:type,percentage|numeric|between:1,100',
          'start_date' => 'required',
          'end_date' => 'required_if:add_expiry,1',
      ],[
        'code.required' => 'Give your discount a code',
        'value.required_if' => 'Please enter an amount for this discount',
        'percentage.required_if' => 'Please enter a percentage',
        'percentage.between' => 'Please enter a percentage between 1 & 100'
      ]);


      $request->merge([
          'start_date' => Carbon::parse($request->start_date)->toDateTimeString(),
          'end_date' => Carbon::parse($request->end_date)->toDateTimeString(),
      ]);


      $discount = Discount::updateOrCreate(
          ['id' => $request->id],
          $request->all()
      );

      return $discount;

    }

    public function show(Discount $discount, Request $request){
        return $discount;
    }

    public function destroy(Discount $discount, Request $request){
          $discount->delete();
          return [1=>2];
    }
}
