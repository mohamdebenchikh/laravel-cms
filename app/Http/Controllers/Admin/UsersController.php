<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
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

        if ($search) {
            $users = User::where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orderBy($sortBy, $sortDir)
                ->paginate($perPage);
        } else {
            $users = User::orderBy($sortBy, $sortDir)->paginate($perPage);
        }

        return inertia('Admin/Users/Index', ['users' => $users, 'sortDir' => $sortDir, 'sortBy' => $sortBy, 'perPage' => $perPage]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Admin/Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string|max:30",
            "about" => "nullable|string",
            "photo" => "nullable|string",
            "email" => "required|email|unique:users",
            "password" => "required|string|min:8|confirmed",
            "password_confirmation" => "required|string",
        ]);

        $data['password'] = Hash::make($request->password);

        if (User::create($data)) {
            return redirect()->to(route('admin.users.index'))->with('success', 'The user has been created successfully.');
        }

        return redirect()->back()->with('error', 'Something is wrong please try again.');
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
        $user = User::findOrFail($id);
        return inertia('Admin/Users/Edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            "name" => "required|string|max:30",
            "about" => "nullable|string",
            'photo' => 'nullable|string',
            "email" => "required|email|unique:users,email," . $id,
            "password" => "nullable|string|min:8|confirmed",
            "password_confirmation" => "nullable|string",
        ]);

        $user = User::findOrFail($id);

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            $data['password'] = $user->password;
        }

        if ($user->update($data)) {
            return redirect()->to(route('admin.users.index'))->with('success', 'The user has been updated successfully.');
        }

        return redirect()->back()->with('error', 'Something is wrong please try again.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->delete()) {
            return redirect()->back()->with('success', 'The user has been deleted successfully.');
        }

        return redirect()->back()->with('error', 'Something is wrong please try again.');
    }
}
