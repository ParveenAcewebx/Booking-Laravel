<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Helpers\CheckSlugHelper;

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
            try {
                $templates = EmailTemplate::query();

                return DataTables::of($templates)
                    ->addColumn('status_label', function ($row) {
                        return $row->status == config('constants.status.active')
                            ? '<span class="badge badge-success">Active</span>'
                            : '<span class="badge badge-danger">Inactive</span>';
                    })

                    
                    ->addColumn('checkbox', function ($row) {
                        $canDelete = CheckSlugHelper::canDelete($row->slug);
                        $disabled = ($row->status == config('constants.status.active') && !$canDelete) ? 'disabled' : '';
                        return '<input type="checkbox" class="selectRow" value="' . $row->id . '" ' . $disabled . '>';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '';

                        // Edit button
                        if (auth()->user()->can('edit emails')) {
                            $btn .= '<a href="' . route('emails.edit', $row->id) . '" class="btn btn-icon btn-success" data-toggle="tooltip" title="Edit Email">
                                    <i class="fas fa-pencil-alt"></i>
                                 </a> ';
                        }

                        if (auth()->user()->can('delete emails')) {
                            $canDelete = CheckSlugHelper::canDelete($row->slug);

                            if ($row->status == config('constants.status.active') && !$canDelete) {

                                $btn .= '<button type="button" class="btn btn-icon btn-secondary" disabled data-toggle="tooltip" title="Email template is in use">
                                        <i class="feather icon-trash-2"></i>
                                     </button>';
                            } else {
                                $btn .= '<form action="' . route('emails.destroy', $row->id) . '" method="POST" id="delete-email-' . $row->id . '" style="display:inline;">
                                        ' . csrf_field() . '
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="button" onclick="deleteEmailTemplate(' . $row->id . ')" class="btn btn-icon btn-danger" data-toggle="tooltip" title="Delete Email">
                                            <i class="feather icon-trash-2"></i>
                                        </button>
                                     </form>';
                            }
                        }

                        return $btn;
                    })
                    ->rawColumns(['status_label', 'checkbox', 'action', 'macro'])
                    ->make(true);
            } catch (\Exception $e) {
                // Return proper JSON error for DataTables
                return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
            }
        }

        return view('admin.email-template.index');
    }



    public function create()
    {
        $loginId = getOriginalUserId();
        $loginUser = $loginId ? User::find($loginId) : null;
        $macros = config('constants.macro');
        return view('admin.email-template.add', compact('loginUser','macros'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255|unique:email_templates,title',
            'subject'        => 'nullable|string|max:255',
            'email_content'  => 'required|string',
            'status'         => 'required|in:' . config('constants.status.active') . ',' . config('constants.status.inactive'),
        ]);

        try {
            $stringWithUnderscores = str_replace(" ", "_", $validated['title']);
            $slugCreation = strtolower($stringWithUnderscores);

            EmailTemplate::create([
                'title'          => $validated['title'],
                'slug'           => $slugCreation,
                'subject'        => $validated['subject'] ?? null,
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
        $getEmailId = EmailTemplate::findOrFail($id);
        $macros = config('constants.macro');
        return view('admin.email-template.edit', compact('getEmailId','macros'));
    }

    public function update(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);

        $validated = $request->validate([
            'title'          => 'required|string|max:255|unique:email_templates,title,' . $template->id,
            'subject'        => 'nullable|string|max:255',
            'email_content'  => 'required|string',
            'status'         => 'required|in:' . config('constants.status.active') . ',' . config('constants.status.inactive'),
        ]);

        try {
            $stringWithUnderscores = str_replace(" ", "_", $validated['title']);
            $slugCreation = strtolower($stringWithUnderscores);

            $template->update([
                'title'          => $validated['title'],
                'slug'           => $slugCreation,
                'subject'        => $validated['subject'] ?? null,
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

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No Records Selected.'], 400);
        }

        EmailTemplate::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => 'Selected Emails Deleted Successfully.']);
    }
}
