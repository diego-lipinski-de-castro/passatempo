<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\User;

class MetricsController extends Controller
{
    //
    public function index(Request $request)
    {

        $from = $request->from;
        $to = $request->to;
        // $k = $request->k;

        // $orders = Order::all();

        dd(User::withCount('orders')->toSql());

        $users = User::withCount(['orders' => function ($q) use ($request) {
            dd($q->toSql());
            $q->whereBetween('date', [$request->from, $request->to])->get();
        }])->get();

        return response()->json($users);

    }
}
