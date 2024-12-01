<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function find(Request $request, string $id)
    {
        $data = Customer::where('id', $id)->with('sales')->first();
        if ($data == null) abort(404, 'Customer not found');
        return response()->json([
            'message' => 'Customer found successfully',
            'data' => $data
        ], 200);
    }

    public function findMany(Request $request)
    {
        $query = Customer::query()->with('sales');

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
    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'domicile' => 'required',
            'gender' => ['required', 'in:MALE,FEMALE'],
        ]);

        $data = Customer::create([
            'name' => $request->name,
            'domicile' => $request->domicile,
            'gender' => $request->gender,
        ]);

        return response()->json([
            'message' => 'Customer created successfully',
            'data' => $data
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'domicile' => 'required',
            'gender' => ['required', 'in:MALE,FEMALE'],
        ]);

        $data = Customer::where('id', $id)->update([
            'name' => $request->name,
            'domicile' => $request->domicile,
            'gender' => $request->gender,
        ]);

        return response()->json([
            'message' => 'Customer updated successfully',
            'data' => $data
        ], 200);
    }

    public function delete(Request $request, $id)
    {
        $data = Customer::where('id', $id)->delete();
        return response()->json([
            'message' => 'Customer deleted successfully',
            'data' => $data
        ], 200);
    }
}
