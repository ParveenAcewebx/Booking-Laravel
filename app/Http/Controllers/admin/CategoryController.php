<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
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

                    if (auth()->user()->hasRole('Administrator') &&  $category->status != 0) {
                        $btn .= '<a href="' . url('/category/' . $category->slug) . '" class="btn btn-icon btn-info ml-1" title="View Booking" target="_blank">
                        <i class="feather icon-eye"></i>
                    </a>';
                    }

                    return $btn ?: '';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.category.index');
    }

    public function create()
    {
        return view('admin.category.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
        ]);
        Category::create([
            'category_name' => $request->category_name,
            'slug' => Str::uuid(),
            'status' => $request->status ?? 0,
        ]);
        return redirect()->route('category.list')->with('success', 'Category Created Successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'status' => 'required|in:' . config('constants.status.active') . ',' . config('constants.status.inactive'),
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'status' => $request->status,
            'slug' => $category->slug ?? Str::uuid(),
        ]);

        return redirect()->route('category.list')->with('success', 'Category Updated Successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['success' => true]);
    }
}
