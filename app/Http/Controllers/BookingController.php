<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\booking;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class BookingController extends Controller
{
    public function index()
    {
        $bookings = booking::all();
        $employees = Employees::all();
        return view('admin.booking',compact('bookings', 'employees'));
    }

    public function calendar()
    {
        $bookings = booking::all();
        $bookingDates = $bookings->pluck('date')->toArray();
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
        $bookings = booking::all();
        return response()->json([
            'data' => $bookings
        ]);
    }

    public function getArtists()
    {
        $artists = \App\Models\Employees::all(['id', 'employee_name']);
        return response()->json($artists);
    }

    public function updateStatusArtist(Request $request, $id)
    {
        $booking = \App\Models\booking::findOrFail($id);
        $booking->status = $request->input('status');
        $booking->artist = $request->input('artist');
        $booking->save();
        return response()->json(['success' => true]);
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