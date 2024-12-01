<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;

use function Laravel\Prompts\select;

class SaleItemController extends Controller
{
    public function update(Request $request, string $sale_id)
    {
        $validated = $request->validate([
            'item_code' => 'required|string|exists:items,code',
            'quantity' => 'required|integer|min:1',
        ]);

        $sale = Sale::where('id', $sale_id)->first();
        $item = Item::where('code', $validated['item_code'])->select('price')->first();
        $total_price_item = $item['price'] * $validated['quantity'];
        $total_price = $sale->total_price + $total_price_item;
        $sale->update(['total_price' => $total_price]);
        $sale->sale_items()->create([
            'item_code' => $validated['item_code'],
            'quantity' => $validated['quantity'],
            'total_price' => $total_price_item,
        ]);
        
        return response()->json([
            'message' => 'Item updated successfully',
            'data' => $sale
        ], 200);
    }

    public function delete(Request $request, string $sale_id, string $item_code)
    {
        $sale = Sale::where('id', $sale_id)->with('sale_items')->first();
        $sale_item = SaleItem::where('sale_id', $sale_id)->where('item_code', $item_code)->select('total_price')->first();
        $total_price = $sale->total_price - $sale_item['total_price'];
        $sale->update(['total_price' => $total_price]);
        $sale->sale_items()->where('item_code', $item_code)->delete();

        return response()->json([
            'message' => 'Item deleted successfully',
            'data' => $sale
        ], 200);
    }
}
