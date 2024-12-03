<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function find(Request $request, string $code)
    {
        $data = Product::query()->where('code', $code)->with('order_products.orders')->first();
        if ($data == null) abort(404, 'Product not found');
        return response()->json([
            'message' => 'Product found successfully',
            'data' => $data
        ], 200);
    }

    public function findMany(Request $request)
    {
        $query = Product::query()->with('order_products.orders');
        if ($request->has('filter')) {
            $filters = $request->filter;
            foreach ($filters as $relation => $conditions) {
                if (is_array($conditions)) {
                    $query->whereHas($relation, function ($q) use ($conditions) {
                        foreach ($conditions as $field => $value) {
                            $q->where($field, 'like', '%' . $value . '%');
                        }
                    });
                } else {
                    $query->where($relation, 'like', '%' . $conditions . '%');
                }
            }
        }

        if ($request->has('sort')) {
            $sorts = $request->sort;
            foreach ($sorts as $field => $order) {
                if (is_array($order)) {
                    $key =  $field . '.' . array_keys($order)[0];
                    $value = array_pop($order);
                    $query->orderBy($key, $value);
                } else {
                    $query->orderBy($field, $order);
                }
            }
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $perPage = $request->get('per_page', 10);
        $data = $query->paginate($perPage);

        return response()->json([
            'message' => 'Products found successfully',
            'data' => $data->items(),
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }



    public function create(Request $request)
    {
         $request->validate([
            'code' => 'required',
            'name' => 'required',
            'category' => 'required',
            'price' => 'required',
        ]);

        $data = Product::create([
            'code' => $request->code,
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
        ]);
        
        return response()->json([
            'message' => 'Product created successfully',
            'data' => $data
        ], 200);
    }

    public function update(Request $request, string $code)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'price' => 'required',
        ]);

        $data = Product::where('code', $code)->update([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $data
        ], 200);
    }

    public function delete(Request $request, string $code)
    {
        $data = Product::where('code', $code)->delete();
        if($data == 0) abort(404, 'Product not found');
        return response()->json([
            'message' => 'Product deleted successfully',
            'data' => $data
        ], 200);
    }
}
