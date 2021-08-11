<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('view','products');
        // DISPLAY ALL PRODUCTS
        $products = Product::paginate(8);
        return response()->json([
            'data' => ProductResource::collection($products)
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        Gate::authorize('edit','products');
        $newProduct = Product::create($request->only('title','description','image','price'));

        return response()->json([
            'data' => $newProduct
        ]);
        // return response(new ProductResource($newProduct), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        Gate::authorize('view','products');

        $product = Product::find($id);
        return response()->json([
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        Gate::authorize('edit','products');
        // find single product want to update
        $product = Product::find($id);

        $updateProduct = $product->update($request->only('title','description','image','price'));

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $updateProduct
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Gate::authorize('edit','products');
        Product::destroy($id);
        return response()->json([
            'status' => 1,
            'message'=> 'Product deleted successfully'
        ]);
    }
}
