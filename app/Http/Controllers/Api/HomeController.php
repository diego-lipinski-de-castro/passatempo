<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;

class HomeController extends Controller
{
    //
    public function index()
    {
        $orders = Order::with(['owner'])->orderBy('created_at', 'desc')->limit(200)->get();
        return response()->json($orders);
    }
}
