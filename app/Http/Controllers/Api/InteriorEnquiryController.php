<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PropertyEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InteriorEnquiryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'propertyname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobilenumber' => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $enquiry = PropertyEnquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->mobilenumber,
            'message' => $request->description,
            'property_id' => null, // or some logic
            'enquiry_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Enquiry submitted successfully',
            'data' => $enquiry
        ], 201);
    }

    public function storePropertyEnquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string',
            'property_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $enquiry = PropertyEnquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'property_id' => $request->property_id,
            'enquiry_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Enquiry submitted successfully',
            'data' => $enquiry
        ], 201);
    }
}
