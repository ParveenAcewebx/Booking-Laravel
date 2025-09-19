<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Import\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;

class UserImportController extends Controller
{
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

            $requiredHeaders = ['name', 'email', 'phone_number', 'status', 'password'];
            $headings = (new HeadingRowImport)->toArray($file);
            $headersInFile = array_map('strtolower', $headings[0][0]);

            foreach ($requiredHeaders as $header) {
                if (!in_array($header, $headersInFile)) {
                    return redirect()->route('user.list')
                        ->with('error', "Missing required column: {$header}. Please use the sample file format.");
                }
            }

            if (count($headersInFile) !== count($requiredHeaders)) {
                return redirect()->route('user.list')
                    ->with('error', "Invalid column count. Expected: " . implode(', ', $requiredHeaders));
            }

            try {
                Excel::import(new UsersImport($request->boolean('send_email')), $file);

                return redirect()->route('user.list')
                    ->with('success', 'Users Imported Successfully.');
            } catch (ExcelValidationException $e) {
                $failures = $e->failures();
                $messages = [];

                foreach ($failures as $failure) {
                    $messages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
                }

                return redirect()->route('user.list')
                    ->with('error', implode('<br>', $messages));
            }
        }

        return redirect()->back()->with('error', 'Please upload a valid file.');
    }

    public function sample()
    {
        $path = public_path('samples/user_import_sample.xlsx');
        return response()->download($path, 'user_import_sample.xlsx');
    }
}
