<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class CategoryController extends Controller
{
    protected $allUsers;
    protected $originalUserId;
    public function __construct()
    {
        $this->allUsers = User::all();
        $this->originalUserId = session('impersonate_original_user') ?? Cookie::get('impersonate_original_user');
    }
    public function index(Request $request)
    {

        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;
        $loginId = session('impersonate_original_user');
        if ($request->ajax()) {
            $categories = Category::select('id', 'category_name', 'status', 'created_at', 'slug');

            return DataTables::of($categories)
                ->addIndexColumn()
                ->editColumn('status', function ($category) {
                    return $category->status == config('constants.status.active')
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->editColumn('created_at', function ($category) {
                    return $category->created_at ? $category->created_at->format('Y-m-d H:i:s') : '-';
                })
                ->editColumn('status', function ($category) {
                    return $category->status == config('constants.status.active')
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('action', function ($category) {
                    $btn = '';

                    if (auth()->user()->can('edit categories')) {
                        $btn .= '<a href="' . route('category.edit', $category->id) . '" class="btn btn-icon btn-success" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a> ';
                    }

                    if (auth()->user()->can('delete categories')) {
                        $btn .= '
                        <form id="delete-category-' . $category->id . '" action="' . route('category.destroy', $category) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="button" class="btn btn-icon btn-danger" onclick="deleteCategory(' . $category->id . ')">
                                <i class="feather icon-trash-2"></i>
                            </button>
                        </form>
                    ';
                    }
                    return $btn ?: '';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.category.index', compact('loginUser'));
    }

    public function create()
    {
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;
        return view('admin.category.add', compact('loginUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('cat-thumbnails', 'public');
        }
        Category::create([
            'category_name' => $request->category_name,
            'slug' => Str::uuid(),
            'thumbnail' => $thumbnailPath,
            'status' => $request->status ? config('constants.status.active') : config('constants.status.inactive'),
        ]);

        return redirect()->route('category.list')->with('success', 'Category Created Successfully.');
    }

    public function edit(Category $category)
    {
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;
        return view('admin.category.edit', compact('category', 'loginUser'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'status' => 'required|in:' . config('constants.status.active') . ',' . config('constants.status.inactive'),
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'category_name' => $request->category_name,
            'status' => $request->status,
            'slug' => $category->slug ?? Str::uuid(),
        ];

        // If a new image is uploaded, store and replace
        if ($request->hasFile('thumbnail')) {
            if ($category->thumbnail && Storage::disk('public')->exists($category->thumbnail)) {
                Storage::disk('public')->delete($category->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('cat-thumbnails', 'public');
        }
        // If user removed existing image and didn't upload a new one
        elseif ($request->remove_existing_thumbnail == '1') {
            if ($category->thumbnail && Storage::disk('public')->exists($category->thumbnail)) {
                Storage::disk('public')->delete($category->thumbnail);
            }
            $data['thumbnail'] = null;
        }

        $category->update($data);

        return redirect()->route('category.list')->with('success', 'Category Updated Successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['success' => true]);
    }
}
