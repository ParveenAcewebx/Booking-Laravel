<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use App\Models\StaffServiceAssociation;
use App\Models\Service;
use App\Models\Vendor;
use App\Models\VendorStaffAssociation;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StaffExport;

class ExportStaffController extends Controller
{
  public function exportstaff(){
   $staffRawdata= Staff::all();
   $staffdata = []; 
    foreach ($staffRawdata as $staffdetail) {
            $userdata = User::where('id', $staffdetail->user_id)->first();
            $serviceid= StaffServiceAssociation::where('staff_member', $staffdetail->user_id)->pluck('service_id')->first();
            $servicename = Service::where('id', $serviceid)->pluck('name')->first();
            $vendorid = VendorStaffAssociation::where('user_id',$staffdetail->user_id)->pluck('vendor_id')->first();
            $vendorname = Vendor::where('id',$vendorid)->pluck('name')->first();

            $staffdata[]=[
                  'StaffName'      => $userdata->name,
                    'Email'          => $userdata->email,
                    'PhoneNumber'    =>$userdata->phone_number,
                    'ServiceName'   =>$servicename,
                    'VendorName'   =>$vendorname,
                    'WorkHour'    =>$staffdetail->work_hours,
                    'DayOff'      =>$staffdetail->days_off,
            ];
    }
      return Excel::download(new StaffExport( $staffdata), 'Staff.xlsx');
  }
}
