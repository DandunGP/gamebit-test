<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addProduct(Request $request){
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string'
        ]);

        $checkProductName = Product::where('name', $request->name)->first();

        if($checkProductName){
            return response()->json([
                'code' => 409,
                'status' => 'Product already exists',
                'result' => null
            ], 409);
        }

        $productNew = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        $productData = Product::find($productNew->id);

        return response()->json([
            'code' => 200,
            'status' => 'Add Product Successfull',
            'result' => $productData
        ], 200);
    }

    public function viewProduct(){
        $productData = Product::all();

        if($productData->count() == 0){
            return response()->json([
                'code' => 200,
                'status' => 'The product doesnt exist yet',
                'result' => null
            ], 200);
        }

        return response()->json([
            'code' => 200,
            'status' => 'Get Product Successfull',
            'result' => $productData
        ], 200);
    }

    public function deleteProduct(Request $request){
        $request->validate([
            'id_product' => 'required|numeric'
        ]);

        $checkProductId = Product::where('id', $request->id_product)->first();

        if(!$checkProductId){
            return response()->json([
                'code' => 404,
                'status' => 'Product not found',
                'result' => null
            ], 404);
        }

        Product::where('id', $request->id_product)->delete();

        return response()->json([
            'code' => 200,
            'status' => 'Delete product successfull',
            'result' => null
        ], 200);
    }
}
