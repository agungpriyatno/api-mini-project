<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    function find(Request $request, string $id)
    {
        $data = Sale::where('id', $id)->with('customers', 'sale_items.item')->first();
        if ($data == null) abort(404, 'Item not found');
        return response()->json([
            'message' => 'Item found successfully',
            'data' => $data
        ], 200);
    }

    public function findMany(Request $request)
    {
        $query = Sale::query()->join('customers', 'sales.customer_id', '=', 'customers.id')->with('customers', 'sale_items.item');

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
            'message' => 'Customers found successfully',
            'data' => $data->items(),
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    function create(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|string|exists:customers,id',
            'items' => 'array|min:1',
            'items.*.item_code' => 'required|string|exists:items,code',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $total_price = 0;

        foreach ($validated['items'] as $key => $item) {
            $validated['items'][$key]['item_code'] = $item['item_code'];
            $validated['items'][$key]['quantity'] = $item['quantity'];
            $validated['items'][$key]['total_price'] = $item['quantity'] * Item::where('code', $item['item_code'])->first()['price'];
            $total_price += $validated['items'][$key]['total_price'];
        }

        $sale = Sale::create([
            'customer_id' => $validated['customer_id'],
            'total_price' => $total_price
        ]);

        $sale->sale_items()->createMany($validated['items']);


        return response()->json([
            'message' => 'Sale created successfully',
            'data' => $sale,
        ]);
    }

    function delete(Request $request, string $id)
    {
        $data = Sale::where('id', $id)->delete();
        if ($data == 0) abort(404, 'Item not found');

        return response()->json([
            'message' => 'Item deleted successfully',
            'data' => $data
        ], 200);
    }
}
