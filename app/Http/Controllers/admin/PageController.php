<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\User;
use DataTables;
use Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Pages::all();
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="selectRow" value="' . $row->id . '">';
                })
                ->addColumn('title', function ($row) {
                    return $row->title;
                })
                ->addColumn('created_by', function ($row) {
                    $created_by = $row->created_by ? $row->created_by : '';
                    $create_by_name = User::where('id', $created_by)->pluck('name')->first();
                    return $create_by_name;
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i');
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 'publish'
                        ? '<span class="badge badge-success">Published</span>'
                        : '<span class="badge badge-danger">Draft</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('page.edit', [$row->id]) . '" class="btn btn-icon btn-success" data-toggle="tooltip" title="Edit Page">
                               <i class="fas fa-pencil-alt"></i>
                            </a> ';

                    // Add Delete button if necessary
                    $btn .= '<form action="' . route('page.delete', [$row->id]) . '" method="POST" style="display:inline;" id="deletePage-' . $row->id . '">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '<button type="button" onclick="deletePage(' . $row->id . ', event)" class="btn btn-icon btn-danger" data-toggle="tooltip" title="Delete Page">
                <i class="feather icon-trash-2"></i>
                </button>';
                    $btn .= '<a href="' . url('/' . $row->slug) . '" class="btn btn-icon btn-info ml-1" title="View Booking" target="_blank">
                                        <i class="feather icon-eye"></i>
                                    </a>';
                    $btn .= '</form>';
                    return $btn;
                })
                ->rawColumns(['checkbox', 'title', 'status', 'action'])
                ->make(true);
        }

        // Return view with the login user data
        return view('admin.pages.index');
    }
    public function PageAdd()
    {
        return view('admin.pages.add');
    }
    public function PageSave(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|max:255',
            'description'   => 'required',
            'feature_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'meta_title'    => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Pages::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '_' . $counter++;
        }

        $featureImagePath = null;
        if ($request->hasFile('feature_image')) {
            $featureImagePath = $request->file('feature_image')->store('pages', 'public');
        }
        $page = Pages::create([
            'title'            => $validated['title'],
            'content'          => $validated['description'],
            'slug'             => $slug,
            'status'           => $request['status'],
            'feature_image'    => $featureImagePath,
            'created_by'       => auth()->id(),
            'meta_title'       => $validated['meta_title'],
            'meta_keywords'    => $validated['meta_keywords'],
            'meta_description' => $validated['meta_description'],
        ]);

        return redirect()->route('page.list')->with('success', 'page Added Successfully.');
    }
    public function pageEdit(Request $request, $id)
    {
        $page = Pages::where('id', $id)->get()->first();
        return view('admin.pages.edit', compact('page'));
    }
    public function pageUpdate(Request $request, $id)
    {
        $page = Pages::findOrFail($id);
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required',
            'slug'          => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'feature_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'image_stored'=>'nullable|string',
            'meta_title'    => 'nullable|string|max:255',
            'meta_description'=> 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);
        $slug = Str::slug($validated['slug']);
         $featureImagePath='';
        if($validated['image_stored']){
        $featureImagePath = $page->feature_image;
        }
        if ($request->hasFile('feature_image')) {
            $featureImagePath = $request->file('feature_image')->store('pages', 'public');
        }
        $page->update([
            'title'         => $validated['title'],
            'content'       => $validated['description'],
            'slug'          => $slug,
            'status'        => $request['status'],
            'feature_image' => $featureImagePath,
            'created_by'    => auth()->id(),
            'meta_title'       => $validated['meta_title'],
            'meta_keywords'    => $validated['meta_keywords'],
            'meta_description' => $validated['meta_description'],
        ]);
        return redirect()->route('page.list')->with('success', 'Page updated successfully!');
    }

    public function pagedelete($id)
    {
        $page = pages::find($id);
        if ($page) {
            $page->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => 'login', 'message' => 'Item not found']);
        }
    }
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No Records Selected.'], 400);
        }

        pages::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => 'Selected Users Deleted Successfully.']);
    }
}
