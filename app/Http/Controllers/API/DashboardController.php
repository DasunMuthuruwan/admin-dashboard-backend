<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    //
    public function chart(){
        $data = Order::with('orderItems')->get();
        return $data;
    }
}
