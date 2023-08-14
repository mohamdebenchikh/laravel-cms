<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search ?? false;
        $sortBy = $request->sort_by ?? 'created_at';
        $sortDir = $request->sort_dir ?? 'desc';
        $perPage = $request->per_page ?? 5;

        if($search){
            $tags = Tag::orderBy($sortBy,$sortDir)->where("name","like","%$search%")->paginate($perPage);
        }else{
            $tags = Tag::orderBy($sortBy,$sortDir)->paginate($perPage);
        }

        return inertia("Admin/Blog/Tags/Index",[
            'tags' => $tags,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
            'perPage' => $perPage
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:tags',
        ]);

        $tag = Tag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        if($tag){
            return redirect()->back()->with('success','The tag has been created successfully.');
        }

        return redirect()->back()->with('error','Someting wrong please try again.');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:tags,name,'.$id,
        ]);

        $tag = Tag::findOrFail($id);

        $tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        if($tag){
            return redirect()->back()->with('success','The tag has been updated successfully.');
        }

        return redirect()->back()->with('error','Someting wrong please try again.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tag = Tag::findOrFail($id);
        if($tag->delete()){
            return redirect()->back()->with('success','The tag has been deleted successfully.');
        }

        return redirect()->back()->with('error','Someting wrong please try again.');
    }
}
