<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
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
            $posts = Post::with('user')->where("title", "like", "%$search%")->orderBy($sortBy, $sortDir)->paginate($perPage);
        } else {
            $posts = Post::with('user')->orderBy($sortBy, $sortDir)->paginate($perPage);
        }

        return inertia("Admin/Blog/Posts/Index", [
            'posts' => $posts,
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
        $categories = Category::all();
        $tags = Tag::all();
        return inertia('Admin/Blog/Posts/Create', ['categories' => $categories, 'tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:124|unique:posts',
            'category_id' => 'nullable|numeric',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'status' => 'required|boolean',
            'excerpt' => 'nullable|string|max:500',
            'feature' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'numeric'
        ]);

        $data['user_id'] = Auth::user()->id;
        $data['slug'] = Str::slug($request->title);

        $post = Post::create($data);


        if ($post) {

            if ($request->has('tags') && is_array($request->tags)) {
                $tags = Tag::whereIn('id', $request->tags)->get();
                $post->tags()->attach($tags);
            }

            return redirect()->to(route('admin.posts.index'))->with('success', 'The post has been created successfully.');
        }
        return redirect()->back()->with('error', 'Something is worng please try again.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('user', 'tags', 'category')->find($id);
        return inertia('Admin/Blog/Posts/Show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::with('category', 'tags')->findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        return inertia('Admin/Blog/Posts/Edit', ['post' => $post, 'categories' => $categories, 'tags' => $tags]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:124|unique:posts,title,' . $id,
            'category_id' => 'nullable|numeric',
            'image' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|boolean',
            'excerpt' => 'nullable|string|max:500',
            'feature' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'numeric'
        ]);

        $post = Post::find($id);

        $data['slug'] = Str::slug($request->title);

        DB::table('post_tags')->where('post_id', $post->id)->delete();


        if ($post->update($data)) {

            if ($request->has('tags') && is_array($request->tags)) {
                $tags = Tag::whereIn('id', $request->tags)->get();
                $post->tags()->attach($tags);
            }

            return redirect()->to(route('admin.posts.index'))->with('success','The post has been updated successfully.');
        }
        return redirect()->back()->with('error', 'Something is worng please try again.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        DB::table('post_tags')->where('post_id', $post->id)->delete();
        if ($post->delete()) {
            return redirect()->to(route('admin.posts.index'))->with('The post has been deleted successfully.');
        }
        return redirect()->back()->with('error', 'Something is worng please try again.');
    }
}
