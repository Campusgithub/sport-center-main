<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $query = Venue::with('category')->where('isDeleted', 0);
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $venues = $query->get();

        return response()->json([
            'success' => true,
            'data' => $venues
        ]);
    }

    public function show($id)
    {
        $venue = Venue::with('category')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $venue
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:100',
            'description' => 'required',
            'price' => 'required|integer|min:0',
            'image' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $venue = Venue::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $request->image,
            'discount' => $request->discount ?? 0,
            'CompanyCode' => 'SPORT001',
            'Status' => 1,
            'isDeleted' => 0,
            'CreatedBy' => $request->user()->id,
            'CreatedDate' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Venue created successfully',
            'data' => $venue
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:100',
            'description' => 'required',
            'price' => 'required|integer|min:0',
            'image' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $venue = Venue::findOrFail($id);
        
        $venue->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $request->image,
            'discount' => $request->discount ?? $venue->discount,
            'LastUpdatedBy' => $request->user()->id,
            'LastUpdatedDate' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Venue updated successfully',
            'data' => $venue
        ]);
    }

    public function destroy($id)
    {
        $venue = Venue::findOrFail($id);
        
        $venue->update([
            'isDeleted' => 1,
            'LastUpdatedBy' => auth()->id,
            'LastUpdatedDate' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Venue deleted successfully'
        ]);
    }
} 