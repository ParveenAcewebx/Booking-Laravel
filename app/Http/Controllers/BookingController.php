<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;
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
        return view('booking.add');
    }

    public function bookingSave(Request $request)
    {
        // Validate input data
        $request->validate([
            'service' => 'required|string|max:255'        
        ]);
    
        // Save booking data to the database
        $booking = Booking::create([
            'service' => $request->service,
            'booking_form_id' => '1',
            'customer_id' => '1',
            'booking_datetime' => '2025-02-11 16:42:00',
            'booking_data' => 'No Data',
            'selected_staff' => 'No Staff',
            'customer_id' => '1',
        ]);
    
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
