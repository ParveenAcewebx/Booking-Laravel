<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\EmailTemplate;
use App\Http\Controllers\Controller;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $templates = EmailTemplate::select([
                'id',
                'title',
                'slug',
                'subject',
                'email_content',
                'dummy_template',
                'status'
            ]);

            return DataTables::of($templates)
                ->addColumn('status_label', function ($row) {
                    return $row->status ? '<span class="text-green-600 font-bold">Active</span>'
                        : '<span class="text-red-600 font-bold">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $edit = '<a href="' . route("email-templates.edit", $row->id) . '" class="text-blue-600">Edit</a>';
                    $delete = '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-red-600 delete-btn">Delete</a>';
                    return $edit . ' | ' . $delete;
                })
                ->rawColumns(['status_label', 'action'])
                ->make(true);
        }

        return view('admin.email-template.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
