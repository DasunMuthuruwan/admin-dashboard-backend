<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{

    /**
     * @OA\Get(
     *      path="/products",
     *      security={{"bearerAuth":{}}},
     *      tags={"Products"},
     *      @OA\Response(
     *          response=200,
     *          description="Products Collection"
     *      )
     * )
     */

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
         * @OA\Post(
         *      path="/products",
         *      security={{"bearerAuth":{}}},
         *      tags={"Products"},
         *      description="Store Product data",
         *      @OA\RequestBody(
         *          required=true,
         *          @OA\JsonContent(ref="#/components/schemas/ProductRequest")
         *      ),
         *      @OA\Response(
         *          response=200,
         *          description="Product create success",
         *          @OA\MediaType(
         *              mediaType="application/json",
         *          )
         *      )
         * )
         */

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
            'data' => $newProduct,
            'message' => 'Product created successfully'
        ]);
        // return response(new ProductResource($newProduct), Response::HTTP_CREATED);
    }


    /**
     * @OA\Get(
     *      path="/products/{id}",
     *      security={{"bearerAuth":{}}},
     *      tags={"Products"},
     *      @OA\Response(
     *          response=200,
     *          description="Get Single Products Details"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="Product ID",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      )
     * )
     */

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
     * @OA\Put(
     *      path="/products/{id}",
     *      security={{"bearerAuth":{}}},
     *      description="Update Product data",
     *      tags={"Products"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Product Updated Successfully",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Product ID",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *   )
     * )
     */

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
     * @OA\Delete(
     *      path="/products/{id}",
     *      security={{"bearerAuth":{}}},
     *      tags={"Products"},
     *      description="Delete Product data",
     *      @OA\Response(
     *          response=200,
     *          description="Product deleted successfully",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="Product ID",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *   )
     * )
     */

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
        
        if(DB::table('products')->where('id',$id)->exists()){
            Product::destroy($id);
            return response()->json([
                'status' => 1,
                'message'=> 'Product deleted successfully'
            ]);
        }

        return response()->json([
            'status' => 0,
            'message'=> 'Product Not Found'
        ]);
    }
}
