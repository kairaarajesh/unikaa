<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\booking;

class BookingController extends Controller
{
    
    public function booking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'numeric'],
            'location' => ['nullable', 'string'],
            'gender' => ['nullable', 'string'],
            'service' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
            'time' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $booking = Booking::create($request->only([
            'name',
            'email',
            'phone',
            'location',
            'gender',
            'service',
            'date',
            'time',
        ]));

        return response()->json([
            'message' => 'Booking Created Successfully',
            'data' => $booking,
        ], 201);
    }
}