<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:product-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $products = Product::latest()->get();

        $data['products'] = $products;
        return $this->sendResponse($data, 'Product information');
    }

    public function create()
    {
        $data = array();
        return $this->sendResponse($data, 'Product information');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'detail' => 'required',
        ]);

        try {
            $product = new Product();
            $product->name = $request->name;
            $product->detail = $request->detail;

            if ($product->save()) {
                $data['product'] = $product;
                return $this->sendResponse($data, 'The product has been saved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $product = Product::find($id);

        $data['product'] = $product;
        return $this->sendResponse($data, 'Product information');
    }

    public function edit($id)
    {
        $product = Product::find($id);

        $data['product'] = $product;
        return $this->sendResponse($data, 'Product information');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'detail' => 'required',
        ]);

        try {
            $product = Product::findOrFail($id);
            $product->name = $request->name;
            $product->detail = $request->detail;

            if ($product->save()) {
                $data['product'] = $product;
                return $this->sendResponse($data, 'The product has been updated successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->delete()) {
                $data['product'] = $product;
                return $this->sendResponse($data, 'The product has been deleted successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }
}
