<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Bookingform;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use DB; 
use Carbon\Carbon; 
use Mail; 
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(){
        $allbooking = Booking::all();
        return view('booking.index', ['allbooking' => $allbooking]);
    }

    public function bookingAdd(){

        $allforms = Bookingform::all();
        $alluser  = User::all();
        return view('booking.add', ['allforms' => $allforms,'alluser'=>$alluser]);
    }

    public function bookingSave(Request $request)
    {
        // Validate input data
        $request->validate([
            'service' => 'required|string|max:255'        
        ]);
        $allforms = Bookingform::all();
        $alluser  = User::all();
        return view('booking.add', ['allforms' => $allforms,'alluser'=>$alluser]);
    }

    // public function bookingSave(Request $request)
    // {  
    //     // Validate input data
    //     $request->validate([
    //         'selected_staff' => 'required', 
    //         'service' => 'required',        
    //         'booking_datetime' => 'required'
    //     ]);  
    //     // Process booking_data from the hidden field
    //     $bookingData = json_decode($request->booking_data, true);
    
    //     // Save booking data to the database
    //     $booking = Booking::create([
    //         'service' => $request->service,
    //         'booking_form_id' => '1',
    //         'customer_id' => '1',
    //         'booking_datetime' => '2025-02-11 16:42:00',
    //         'booking_data' => 'No Data',
    //         'selected_staff' => 'No Staff',
    //         'customer_id' => '1',
    //     ]);
    
    //         // 'booking_form_id' = $request->booking_form_id,
    //         // 'customer_id' = $request->customer_id,
    //         // 'booking_datetime' = $request->booking_datetime,
    //         // 'booking_data' = json_encode($bookingData), // Store JSON data
    //         // 'selected_staff' = $request->selected_staff,
    //     // ]);
    
    //     // Handle success or failure
    //     if ($booking) {
    //         return redirect('/bookings')->with('success', 'Booking Added successfully!');
    //     } else {
    //         return redirect()->back()->with('error', 'It failed. Please try again.');
    //     }
    // } 
    
    public function bookingEdit($id=null)
    {
        if($id==null){
            $id= Auth::id();
        }
        $booking = Booking::findOrFail($id);
        return view('booking.edit', ['booking' => $booking]);
    }

    public function bookingUpdate(Request $request, $id=null)
    {
        $request->validate([
            'service' => 'required|string|max:255'        
        ]);
    
        $booking = Booking::findOrFail($id);
    
        // Update booking details
        $booking->update([
            'service' => $request->service
        ]);
        return redirect('/bookings')->with('success', 'Booking updated successfully!');
    }
    }
    
    // public function bookingEdit($id)
    // {
    //     $booking = Booking::findOrFail($id);
    //     $dynamicFields = [];
    //     if (!empty($booking->booking_data)) {
    //         $dynamicFields = json_decode($booking->booking_data, true);
    
    //         if (json_last_error() !== JSON_ERROR_NONE) {
    //             dd('JSON decode failed', json_last_error_msg(), $booking->booking_data);
    //         }
    //     }
    
    //     $staffList = User::all();
    //     $booking->booking_datetime = date('Y-m-d\TH:i', strtotime($booking->booking_datetime));
    
    //     return view('booking.edit', compact('booking', 'dynamicFields', 'staffList'));
    // }
    
    // public function bookingUpdate(Request $request, $id)
    // {
    //     $booking = Booking::findOrFail($id);
    
    //     // Extract dynamic fields
    //     $dynamicFields = $request->input('dynamic', []);
    
    //     // Save updated form data as JSON
    //     $booking->booking_data = json_encode($dynamicFields);
    
    //     // Save other fields
    //     $booking->selected_staff = $request->input('staff');
    //     $booking->booking_datetime = $request->input('booking_datetime');
        
    //     $booking->save();
    
    //     return redirect()->route('booking.list')->with('success', 'Booking updated successfully.');
    // }
    // public function bookingDelete($id) {
    //     $booking = Booking::find($id);
    //     $booking->delete();
    //     return redirect('/bookings')->with('success', 'Booking Delete successfully!');
    // }

