<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Product;
use App\Http\Resources\Product as ProductResource;

class ProductController extends BaseController
{   
    public function index(){
        $product = Product::all();
        return $this->sendResponse(ProductResource::collection($product),"ok");
    }

    public function store(Request $request){
        //inputba vannak a requestből kiszedett adatok
        $input = $request->all();
        $validator = Validator::make($input,[
            "name" =>"required",
            "price"=> "required"
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $product = Product::create($input);

        return $this->sendResponse(new ProductResource($product), "adat felvéve");
    }

    public function show($id){
        $product = Product::find($id);

        if(is_null($product)){
            return $this->sendError("Product nem létezik");
        }

        return $this->sendResponse(new ProductResource($product),"termék betöltve");
    }

    public function update(Request $request,$id){
        $input = $request->all();

        $validator = Validator::make($input, [
            "name"=>"required",
            "price"=>"required"
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $product = Product::find($id);
        $product->update($request->all());

        return $this->sendResponse(new ProductResource($product),"termék frissitve");
    }

    public function destroy($id){
        Product::destroy($id);

        return $this->sendResponse([],"Post törölve");
    }
}
