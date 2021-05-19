<?php 
namespace Laracle\ShopBox\Controllers;

use Illuminate\Http\Request;
use Laracle\ShopBox\Models\Order;
use Laracle\ShopBox\Models\Customer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        

        if($request->range == 'week'){
            $start = Carbon::now()->subDays(7);
        } else if ($request->range == 'month'){
            $start = Carbon::now()->subMonths(1);
        } else if ($request->range == 'quarter'){
            $start = Carbon::now()->firstOfQuarter();
        }  else if ($request->range == 'year'){
            $start = Carbon::now()->subYears(1);
        } else if ($request->range == 'all'){

            $start = ($order = Order::oldest()->first()) ? $order->created_at : now();

        }

        $end = now();

        $orders = Order::whereBetween('created_at',[$start,$end])->get();

        $order_chart = $orders->sortBy('created_at')->groupBy(function($val){
                return Carbon::parse($val->created_at)->format('c');
              })->map->count()->toArray();
        

        $customers = Customer::with('orders')->whereBetween('created_at',[$start,$end])->get();
        $repeat_customers = Customer::has('orders', '>' , 1)->with('orders')->whereBetween('created_at',[$start,$end])->count();
        

        return [
            'sales' => $orders->sum('total'),
            'orders' => $orders->count(),
            'new_customers' => $customers->count(),
            'repeat_customers' => $repeat_customers,
            'avg_order_value' => $orders->avg('total'),
            'order_chart' => $order_chart,
            'range' => $request->range
        ];


    }

    
}

?>