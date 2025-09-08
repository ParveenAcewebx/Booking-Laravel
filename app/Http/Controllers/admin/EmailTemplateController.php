<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Http\Controllers\Controller;
Use Illuminate\Support\Str;

class EmailTemplateController extends Controller
{
    protected $allUsers;

    public function __construct()
    {
        $this->allUsers = User::all();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $templates = EmailTemplate::query();

            return DataTables::of($templates)
                ->addColumn('status_label', function ($row) {
                    return $row->status == config('constants.status.active')
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('email_content', function ($row) {
                    return $row->email_content;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';

                    if (auth()->user()->can('edit emails')) {
                        $btn .= '<a href="' . route('emails.edit', $row->id) . '" class="btn btn-icon btn-success" data-toggle="tooltip" title="Edit Email">
                                <i class="fas fa-pencil-alt"></i>
                             </a> ';
                    }

                    if (auth()->user()->can('delete emails')) {
                        $btn .= '<form action="' . route('emails.destroy', $row->id) . '" method="POST" id="delete-email-' . $row->id . '" style="display:inline;">
                                ' . csrf_field() . '
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" onclick="deleteEmailTemplate(' . $row->id . ')" class="btn btn-icon btn-danger" data-toggle="tooltip" title="Delete Email">
                                    <i class="feather icon-trash-2"></i>
                                </button>
                             </form>';
                    }

                    return $btn;
                })
                ->rawColumns(['status_label', 'action','email_content'])
                ->make(true);
        }

        return view('admin.email-template.index');
    }

    public function create()
    {
        $loginId = getOriginalUserId();
        $loginUser = $loginId ? User::find($loginId) : null;
        return view('admin.email-template.add', compact('loginUser'));
    }

    public function store(Request $request)
    {
        // dd($request->email_content);
        $validated = $request->validate([
            'title'          => 'required|string|max:255|unique:email_templates,title',
            'slug'           => 'required|string|max:255|unique:email_templates,slug',
            'macro'          => 'required|string|max:255|unique:email_templates,macro',
            'subject'        => 'nullable|string|max:255',
            'dummy_template' => 'nullable|string|max:255',
            'email_content'  => 'required|string',
            'status'         => 'required|in:' . config('constants.status.active') . ',' . config('constants.status.inactive'),
        ]);

        try {
            EmailTemplate::create([
                'title'          => $validated['title'],
                'slug'           => $validated['slug'],
                'macro'          => $validated['macro'],
                'subject'        => $validated['subject'] ?? null,
                'dummy_template' => $validated['dummy_template'] ?? null,
                'email_content'  => $request->email_content,
                'status'         => $validated['status'],
            ]);

            return redirect()
                ->route('emails.list')
                ->with('success', 'Email Created Successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $getEmailId = EmailTemplate::findorFail($id);
        return view('admin.email-template.edit', compact('getEmailId'));
    }

    public function update(Request $request, string $id)
    {
        $template = EmailTemplate::findOrFail($id);
        $validated = $request->validate([
            'title'          => 'required|string|max:255|unique:email_templates,title,' . $template->id,
            'slug'           => 'required|string|max:255|unique:email_templates,slug,' . $template->id,
            'macro'          => 'required|string|max:255|unique:email_templates,macro,' . $template->id,
            'subject'        => 'nullable|string|max:255',
            'dummy_template' => 'nullable|string|max:255',
            'email_content'  => 'required|string',
            'status'         => 'required|in:' . config('constants.status.active') . ',' . config('constants.status.inactive'),
        ]);

        try {
            // Update template
            $template->update([
                'title'          => $validated['title'],
                'slug'           => $validated['slug'],
                'macro'          => $validated['macro'],
                'subject'        => $validated['subject'] ?? null,
                'dummy_template' => $validated['dummy_template'] ?? null,
                'email_content'  => $validated['email_content'],
                'status'         => $validated['status'],
            ]);

            return redirect()
                ->route('emails.list')
                ->with('success', 'Email Updated Successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $emailTemplate = EmailTemplate::find($id);
        if ($emailTemplate) {
            $emailTemplate->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Email Template not found.']);
    }
}
