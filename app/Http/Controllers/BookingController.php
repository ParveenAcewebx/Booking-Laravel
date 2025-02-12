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
        // dd($request->all());
        // $request->validate([
        //     'service' => 'required|string|max:255',
        //     'form_name' => 'required',
        //     'selected_staff' => 'required',
        // ]);
        // echo '<pre>';
        // print_r($request);die;
    
        // Process booking_data from the hidden field
        $bookingData = json_decode($request->booking_data, true);
    
        // Save booking data to the database
        $booking = Booking::create([
            'service' => $request->service,
            'booking_form_id' => $request->booking_form_id,
            'customer_id' => $request->customer_id,
            'booking_datetime' => $request->booking_datetime,
            'booking_data' => json_encode($bookingData), // Store JSON data
            'selected_staff' => $request->selected_staff,
        ]);
    
        // Handle success or failure
        if ($booking) {
            return redirect('/bookings')->with('success', 'Booking Added successfully!');
        } else {
            return redirect()->back()->with('error', 'It failed. Please try again.');
        }
    }
    
    
    
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

    public function bookingDelete($id) {
        $booking = Booking::find($id);
        $booking->delete();
        return redirect('/bookings')->with('success', 'Booking Delete successfully!');
    }
}
