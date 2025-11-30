<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/test-api', function() {
    return response()->json(['message' => 'API routes working!', 'time' => now()]);
});

Route::get('/products/{id}/modifiers', function ($id) {
    try {
        $modifierIds = DB::table('product_modifier')
            ->where('product_id', $id)
            ->pluck('modifier_id')
            ->toArray();
        
        if (empty($modifierIds)) {
            return response()->json([]);
        }
        
        $modifiers = DB::table('modifiers')
            ->whereIn('id', $modifierIds)
            ->get();
        
        $result = [];
        
        foreach ($modifiers as $modifier) {
            $options = DB::table('modifier_options')
                ->where('modifier_id', $modifier->id)
                ->orderBy('price', 'asc')
                ->get();
            
            if ($options->count() === 0) {
                continue;
            }
            
            $formattedOptions = [];
            foreach ($options as $option) {
                $formattedOptions[] = [
                    'id' => $option->id,
                    'name' => $option->name,
                    'price' => (float) $option->price
                ];
            }
            
            $result[] = [
                'id' => $modifier->id,
                'name' => $modifier->name,
                'type' => $modifier->type,
                'is_multiple' => (bool) $modifier->is_multiple,
                'options' => $formattedOptions
            ];
        }
        
        return response()->json($result);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => basename($e->getFile())
        ], 500);
    }
});