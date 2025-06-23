<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('isDeleted', 0)->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function show($id)
    {
        $category = Category::with('venues')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'image' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::create([
            'title' => $request->title,
            'image' => $request->image,
            'CompanyCode' => 'SPORT001',
            'Status' => 1,
            'isDeleted' => 0,
            'CreatedBy' => $request->user()->id,
            'CreatedDate' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'image' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::findOrFail($id);
        
        $category->update([
            'title' => $request->title,
            'image' => $request->image,
            'LastUpdatedBy' => $request->user()->id,
            'LastUpdatedDate' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        $category->update([
            'isDeleted' => 1,
            'LastUpdatedBy' => auth()->id,
            'LastUpdatedDate' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
} 