<?php

namespace App\Http\Controllers\admin\Import;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\form;
use App\Models\Staff;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PasswordResetToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Import\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class UserImportController extends Controller
{
    protected $allUsers;
    public function __construct()
    {
        $this->allUsers = User::all();
    }

    public function showImportView()
    {
        return view('admin.import.user-import');
    }

    public function importSave(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        if ($request->hasFile('excel_file')) {
            $file = $request->file('excel_file');
        
            $requiredHeaders = ['name', 'email', 'password', 'phone_number', 'status'];

            $headings = (new HeadingRowImport)->toArray($file);
            $headersInFile = array_map('strtolower', $headings[0][0]); 

            foreach ($requiredHeaders as $header) {
                if (!in_array($header, $headersInFile)) {
                    return redirect()->back()
                        ->with('error', "Missing required column: {$header}. Please use the sample file format.");
                }
            }
            Excel::import(new UsersImport($request->input('send_email') ?? 1), $file);
            return redirect()->route('user.list')->with('success', 'Users Imported Successfully.');
        }

        return redirect()->back()->with('error', 'Please upload a valid file.');
    }


    public function sample()
    {
        $path = public_path('samples/user_import_sample.xlsx');
        return response()->download($path, 'user_import_sample.xlsx');
    }
}
