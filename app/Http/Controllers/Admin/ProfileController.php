<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return inertia('Admin/Profile/Index');
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate(['photo' => 'nullable|string']);

        if ($request->photo) {
            $request->user()->update(['photo' => $request->photo]);
            return redirect()->back()->with('success', 'Your profile photo has been updated successfully');
        } else {
            $request->user()->update(['photo' => null]);
            return redirect()->back()->with('success', 'Your profile photo has been removed successfully');
        }
    }

    public function updatePersonalInfo(Request $request)
    {
        $id = $request->user()->id;

        $data = $request->validate([
            "name" => "required|string|max:30",
            "about" => "nullable|string",
            "email" => "required|email|unique:users,email,$id",
        ]);

        $request->user()->update($data);
        return redirect()->back()->with('success', 'Your personal information has been updated successfully');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string',
        ]);

        if (Hash::check($request->current_password, $request->user()->password)) {
            $newPassword = Hash::make($request->new_password);
            $request->user()->update(['password' => $newPassword]);
            return redirect()->back()->with('success', 'Your password  has been change successfully');
        }

        return redirect()->back()->withErrors(['current_password' => trans('validation.current_password')]);
    }
}
