<?php

namespace App\Http\Controllers;

use App\Models\FridgeItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FridgeController extends Controller
{
    // Получить все продукты
    public function index()
    {
        return response()->json(FridgeItem::all());
    }

    // Добавить продукт
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'string|max:50',
            'expires_at' => 'nullable|date',
        ]);

        $item = FridgeItem::create($validated);
        return response()->json($item, Response::HTTP_CREATED);
    }

    // Показать один продукт
    public function show($id)
    {
        $item = FridgeItem::findOrFail($id);
        return response()->json($item);
    }

    // Обновить продукт
    public function update(Request $request, $id)
    {
        $item = FridgeItem::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'quantity' => 'integer|min:0',
            'unit' => 'string|max:50',
            'expires_at' => 'nullable|date',
        ]);

        $item->update($validated);
        return response()->json($item);
    }

    // Удалить продукт
    public function destroy($id)
    {
        $item = FridgeItem::findOrFail($id);
        $item->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}