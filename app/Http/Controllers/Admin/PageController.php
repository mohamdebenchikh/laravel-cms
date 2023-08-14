<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search ?? false;
        $perPage = $request->per_page ?? 10;
        $sortBy = $request->sort_by ?? 'created_at';
        $sortDir = $request->sort_dir ?? 'desc';

        if ($search) {
            $pages = Page::where("title", "like", "%$search%")->orderBy($sortBy, $sortDir)->paginate($perPage);
        } else {
            $pages = Page::orderBy($sortBy, $sortDir)->paginate($perPage);
        }

        return inertia("Admin/Pages/Index", [
            'pages' => $pages,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia("Admin/Pages/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:124|unique:pages',
            'content' => 'required|string',
            'cover' => 'nullable|string',
            'status' => 'required|boolean',
            'description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($request->title);

        $page = Page::create($data);


        if ($page) {
            return redirect()->to(route('admin.pages.index'))->with('success', 'The page has been created successfully.');
        }
        return redirect()->back()->with('error', 'Something is worng please try again.');
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
        $page = Page::findOrFail($id);
        return inertia("Admin/Pages/Edit", ['page' => $page]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:124|unique:pages,title,' . $id,
            'cover' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|boolean',
            'description' => 'nullable|string|max:500',
            'feature' => 'nullable|boolean',
            'keywords' => 'nullable|string',
        ]);

        $page = Page::find($id);

        $data['slug'] = Str::slug($request->title);

        if ($page->update($data)) {

            return redirect()->to(route('admin.pages.index'))->with('success', 'The page has been updated successfully.');
        }
        return redirect()->back()->with('error', 'Something is worng please try again.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $page = Page::find($id);
        if ($page->delete()) {
            return redirect()->to(route('admin.pages.index'))->with('The page has been deleted successfully.');
        }
        return redirect()->back()->with('error', 'Something is worng please try again.');
    }
}
