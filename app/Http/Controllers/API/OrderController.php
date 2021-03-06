<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderItemsResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use phpseclib3\Crypt\Random;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{

          /**
         * @OA\Get(
         *      path="/orders",
         *      security={{"bearerAuth":{}}},
         *      tags={"Orders"},
         *      @OA\Response(
         *          response=200,
         *          description="Order Collection"
         *      ),
         *      @OA\Parameter(
         *          name="page",
         *          description="Page Pagination",
         *          in="query",
         *          @OA\Schema(
         *              type="integer"
         *          )
         *      )
         * )
         */

    // get orders details
    public function index(){
        Gate::authorize('view','orders');
        return response()->json([
            'orders' => OrderResource::collection(Order::paginate(8))
        ]);
    }

    /**
         * @OA\Get(
         *      path="/orders/{id}",
         *      security={{"bearerAuth":{}}},
         *      tags={"Orders"},
         *      @OA\Response(
         *          response=200,
         *          description="Get Single User Details"
         *      ),
         *      @OA\Parameter(
         *          name="id",
         *          description="Order ID",
         *          in="path",
         *          @OA\Schema(
         *              type="integer"
         *          )
         *      )
         * )
         */


    // get single order details
    public function show($id){
        Gate::authorize('view','orders');
        return response()->json([
            'order' => new OrderResource(Order::find($id))
        ]);
    }

    /**
     * @OA\Get(
     *      path="/export_csv",
     *      security={{"bearerAuth":{}}},
     *      tags={"Orders"},
     *      @OA\Response(
     *          response=200,
     *          description="Downloaded Orders csv file"
     *      )
     * )
     */

    // export orders csv file
    public function export(){
        Gate::authorize('view','orders');
        $headers = [
            "content-type" => "text/csv",
            "content-disposition" => "attachment; filename=orders.csv",
            "pragma" => "no-cache",
            "cache-control" => "must-revalidate, post-check=0, pre-check=0",
            "expires" => 0
        ];

        $callback = function(){
            $orders = Order::all();
            $file = fopen('php://output','w');

            // headers row
            fputcsv($file,['ID', 'Name', 'Email', 'Product_Title', 'Price', 'Quantity', 'TotalQuantityPrice']);

            // body
            foreach($orders as $order){
                fputcsv($file,[$order->id, $order->name, $order->email, '', '', '']);

                foreach($order->orderItems as $orderItem){
                    fputcsv($file,['', '', '', $orderItem->product_title, $orderItem->price, $orderItem->quantity, $orderItem->item_quantity_price]);
                }
            }


            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
