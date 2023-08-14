<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search ?? false;
        $sortDir = $request->sort_dir ?? 'desc';
        $sortBy = $request->sort_by ?? 'created_at';
        $perPage = $request->per_page ?? 5;

        if ($search) {
            $categories = Category::orderBy($sortBy, $sortDir)->where("name", "like", "%$search%")->paginate($perPage);
        } else {
            $categories = Category::orderBy($sortBy, $sortDir)->paginate($perPage);
        }

        return inertia('Admin/Blog/Categories/Index', ['categories' => $categories, 'sortDir' => $sortDir, 'sortBy' => $sortBy, 'perPage' => $perPage]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:25|unique:categories',
            'description'=>'nullable|string|max:500'
        ]);

        $data['slug'] = Str::slug($request->name);

        if(Category::create($data)){
            return redirect()->back()->with('success','The category has been creaetd successfully.');
        }
        return redirect()->back()->with('error','Something wrong please try again.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name'=>'required|string|max:25|unique:categories,name,'.$id,
            'description'=>'nullable|string|max:500'
        ]);

        $data['slug'] = Str::slug($request->name);

        $category = Category::findOrFail($id);

        if($category->update($data)){
            return redirect()->back()->with('success','The category has been updated successfully.');
        }
        return redirect()->back()->with('error','Something wrong please try again.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        if($category->delete()){
            return redirect()->to(route('admin.categories.index'))->with('success','The category has been deleted successfully.');
        }
        return redirect()->back()->with('error','Something wrong please try again.');
    }
}
