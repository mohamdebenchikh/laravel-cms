<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return inertia('Admin/Settings/Index', ['settings' => $settings]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:settings',
            'value' => 'required|string',
        ]);

        if (Setting::create($data)) {
            return redirect()->back()->with('success', 'The setting has been added successfully.');
        }
        return redirect()->back()->with('error', 'Something wrong please try again.');
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:settings,key,' . $id,
            'value' => 'required|string',
        ]);

        $setting = Setting::findOrFail($id);

        if ($setting->update($data)) {
            return redirect()->back()->with('success', 'The setting has been updated successfully.');
        }
        return redirect()->back()->with('error', 'Something wrong please try again.');
    }

    public function destroy(string $id)
    {
        $setting = Setting::findOrFail($id);
        if ($setting->delete()) {
            return redirect()->back()->with('success', 'The setting$setting has been deleted successfully.');
        }
        return redirect()->back()->with('error', 'Something wrong please try again.');
    }
}
