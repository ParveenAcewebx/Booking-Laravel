<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportUserController extends Controller
{
    public function exportuser(){
         return Excel::download(new UserExport, 'users.xlsx');
    }
}
