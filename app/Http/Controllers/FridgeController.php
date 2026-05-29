<?php

namespace App\Http\Controllers;

use App\Models\FridgeItem;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FridgeController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $items = FridgeItem::all();
        return $this->success($items, 'Products retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'string|max:50',
        ]);

        $item = FridgeItem::create($validated);
        return $this->success($item, 'Product added successfully', Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $item = FridgeItem::find($id);
        
        if (!$item) {
            return $this->error('Product not found', Response::HTTP_NOT_FOUND);
        }
        
        return $this->success($item, 'Product retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $item = FridgeItem::find($id);
        
        if (!$item) {
            return $this->error('Product not found', Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'quantity' => 'integer|min:0',
            'unit' => 'string|max:50',
        ]);

        $item->update($validated);
        return $this->success($item, 'Product updated successfully');
    }

    public function destroy($id)
    {
        $item = FridgeItem::find($id);
        
        if (!$item) {
            return $this->error('Product not found', Response::HTTP_NOT_FOUND);
        }
        
        $item->delete();
        return $this->success(null, 'Product deleted successfully', Response::HTTP_OK);
    }
}