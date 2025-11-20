<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\booking;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class BookingController extends Controller
{
   public function index()
{
    try {
        // Fetch data from Node.js API
        $response = Http::timeout(10)->get('https://back.unikaabeauty.com/api/admin/bookings');

        // Check if the request was successful
        if ($response->successful()) {
            $bookings = $response->json();
            // Some APIs return {"data": [...]}, handle that too
            if (isset($bookings['bookings']) && is_array($bookings['bookings'])) {
                $bookings = $bookings['bookings'];
            }
    //    dd($bookings);

            // Ensure it's a numeric array
            if (!is_array($bookings)) {
                $bookings = [];
            }
        } else {
            Log::error('API request failed', ['status' => $response->status()]);
            $bookings = [];
        }
    } catch (\Exception $e) {
        Log::error('API error: ' . $e->getMessage());
        $bookings = [];
    }
    // Get employees from local DB
    $employees = Employees::all();

    return view('admin.booking', compact('bookings', 'employees'));
}

   public function notification()
    {
        $today = now()->startOfDay();
        $tomorrow = now()->addDay()->endOfDay();

        // Fetch bookings for today and tomorrow
        $bookings = Booking::whereBetween('date', [$today, $tomorrow])->get();

        return view('layouts.header', compact('bookings'));
    }

    public function calendar()
    {
        try {
            // Fetch data from Node.js API
            $response = Http::timeout(10)->get('https://back.unikaabeauty.com/api/admin/bookings');

            // Check if the request was successful
            if ($response->successful()) {
                $bookings = $response->json();
                // Some APIs return {"data": [...]}, handle that too
                if (isset($bookings['bookings']) && is_array($bookings['bookings'])) {
                    $bookings = collect($bookings['bookings']);
                } elseif (isset($bookings['data']) && is_array($bookings['data'])) {
                    $bookings = collect($bookings['data']);
                } elseif (is_array($bookings)) {
                    $bookings = collect($bookings);
                } else {
                    $bookings = collect([]);
                }
            } else {
                Log::error('API request failed', ['status' => $response->status()]);
                $bookings = collect([]);
            }
        } catch (\Exception $e) {
            Log::error('API error: ' . $e->getMessage());
            $bookings = collect([]);
        }

        $bookingDates = $bookings->pluck('date')->filter()->toArray();
        return view('admin.booking_calendar', compact('bookings', 'bookingDates'));
    }

    public function destroy(booking $booking)
    {
        $booking->delete();
        flash()->success('Success','Booking has been deleted successfully!');
        return redirect()->route('booking.index')->with('success');
    }

    public function generateInvoice($id)
    {
        $booking = booking::findOrFail($id);
        $data = ['booking' => $booking];
        $pdf = Pdf::loadView('admin/booking_show', $data);
        $todayDate = Carbon::now()->format('d-m-y');

        return $pdf->download('invoice_' . $booking->id . '_' . $todayDate . '.pdf');
    }

    public function calendarEvents()
    {
        try {
            // Fetch data from Node.js API
            $response = Http::timeout(10)->get('https://back.unikaabeauty.com/api/admin/bookings');

            // Check if the request was successful
            if ($response->successful()) {
                $bookings = $response->json();
                // Some APIs return {"data": [...]}, handle that too
                if (isset($bookings['bookings']) && is_array($bookings['bookings'])) {
                    $bookings = $bookings['bookings'];
                } elseif (isset($bookings['data']) && is_array($bookings['data'])) {
                    $bookings = $bookings['data'];
                } elseif (!is_array($bookings)) {
                    $bookings = [];
                }

                // Transform API data to match expected format
                $transformedBookings = array_map(function($booking) {
                    $rawImage = $booking['serviceImage'] ?? $booking['service_image'] ?? $booking['image'] ?? $booking['serviceImg'] ?? null;
                    if ($rawImage && !preg_match('/^https?:\/\//i', $rawImage)) {
                        $rawImage = url(ltrim($rawImage, '/'));
                    }
                    return [
                        'id' => $booking['_id'] ?? $booking['id'] ?? null,
                        'name' => $booking['customerName'] ?? $booking['name'] ?? 'N/A',
                        'email' => $booking['email'] ?? 'N/A',
                        'phone' => $booking['phone'] ?? 'N/A',
                        'gender' => $booking['gender'] ?? 'N/A',
                        'service' => $booking['serviceName'] ?? $booking['service'] ?? 'N/A',
                        'location' => $booking['location'] ?? 'N/A',
                        'date' => $booking['date'] ?? null,
                        'time' => $booking['time'] ?? 'N/A',
                        'status' => $booking['status'] ?? null,
                        'artist' => $booking['stylist'] ?? $booking['artist'] ?? null,
                        'image' => $rawImage,
                    ];
                }, $bookings);

                return response()->json([
                    'data' => $transformedBookings
                ]);
            } else {
                Log::error('API request failed', ['status' => $response->status()]);
                return response()->json(['data' => []]);
            }
        } catch (\Exception $e) {
            Log::error('API error: ' . $e->getMessage());
            return response()->json(['data' => []]);
        }
    }

    public function getArtists()
    {
        $artists = \App\Models\Employees::all(['id', 'employee_name']);
        return response()->json($artists);
    }

    public function updateStatusArtist(Request $request, $id)
    {
        try {
            // Try to update via API first
            $response = Http::timeout(10)->post("https://back.unikaabeauty.com/api/admin/bookings/{$id}/update-status", [
                'status' => $request->input('status'),
                'stylist' => $request->input('artist'),
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true]);
            }

            // Fallback: try to find in local DB if API fails
            $booking = \App\Models\booking::find($id);
            if ($booking) {
                $booking->status = $request->input('status');
                $booking->artist = $request->input('artist');
                $booking->save();
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        } catch (\Exception $e) {
            Log::error('Update status error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, booking $booking)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'location' => 'nullable|string',
            'service' => 'nullable|string',
            'date' => 'nullable|date',
            'time' => 'nullable',
            'gender' => 'nullable|in:Male,Female,Other',
            'status' => 'nullable|string',
            'artist' => 'nullable',
            'emp_id' => 'nullable|exists:employees,id'
        ]);

        $booking->update($request->all());

        flash()->success('Success', 'Booking has been updated successfully!');
        return redirect()->route('booking.index');
    }
}
