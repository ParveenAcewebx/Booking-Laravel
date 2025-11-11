<?php

namespace App\Http\Controllers\export;

use App\Http\Controllers\Controller;
use App\Exports\BookingsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingTemplate;
use App\Models\User;
use App\Models\Service;
use App\Models\Vendor;

class ExportBookingController extends Controller
{
    public function exportBookings()
    {
        $bookings = Booking::all();

        // Initialize an array to hold the booking data for export
        $bookingData = [];

        // Loop through each booking and collect data for the export
        foreach ($bookings as $booking) {
            $templateid = $booking->booking_template_id;
            $customerid = $booking->customer_id;
            $serviceid=  $booking->service_id;
            $vendorid= $booking->vendor_id;

            // Get Template Name
            $templatename = BookingTemplate::where('id', $templateid)->pluck('template_name')->first();

            // Get Customer Name
            $customername = User::where('id', $customerid)->pluck('name')->first();
            // get service name 
            
           $servicename=  Service::where('id',$serviceid)->pluck('name')->first();
           $vendorname=  Vendor::where('id', $vendorid)->pluck('name')->first();

            // Prepare row data for this booking
            $bookingData[] = [
                'bookingid'         => $booking->id,
                'bookingtemplatename'=> $templatename,
                'CustomerName'      => $customername,
                'BookingDateTime'   => $booking->booking_datetime,
                'status'            => $booking->status,
                'Firstname'         => $booking->first_name,
                'LastName'          => $booking->last_name,
                'PhoneNumber'       => $booking->phone_number,
                'Email'             => $booking->email,
                'ServiceName'       => $servicename,
                'VendorName'        => $vendorname,
                'SlotDetail'        => $booking->bookslots,
            ];
        }

        // Pass the data to the export class and return the Excel download
        return Excel::download(new BookingsExport($bookingData), 'bookings.xlsx');
    }
}
