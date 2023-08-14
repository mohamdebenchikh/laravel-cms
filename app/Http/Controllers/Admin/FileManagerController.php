<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    public function index(Request $request)
    {
        $folder = $request->folder ?? 'public';

        $folderDetails = $this->getFolderDetails($folder);
        $imageFiles = $this->getImageFiles($folder);
        $breadcrumbs = $this->generateBreadcrumbs($folder);


        return inertia('Admin/FileManager/Index', [
            'folders' => $folderDetails,
            'files' => $imageFiles,
            'breadcrumbs' => $breadcrumbs,
            'currentFolder' => $folder,
        ]);
    }

    private function getFolderDetails($folder)
    {
        $folders = Storage::directories($folder);

        $folderDetails = [];
        foreach ($folders as $folderPath) {
            $folderName = $folderPath;
            $timestamp = Storage::lastModified($folderPath);
            $size = File::size(Storage::path($folderPath));

            $folderDetails[] = [
                'name' => $folderName,
                'timestamp' => $timestamp,
                'size' => $size,
            ];
        }

        return $folderDetails;
    }

    private function getImageFiles($folder)
    {
        $files = Storage::files($folder);

        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
        $imageFiles = [];
        foreach ($files as $filePath) {
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if (in_array($extension, $imageExtensions)) {
                $timestamp = Storage::lastModified($filePath);
                $size = File::size(Storage::path($filePath));

                $imageFiles[] = [
                    'path' => $filePath,
                    'timestamp' => $timestamp,
                    'size' => $size,
                ];
            }
        }

        return $imageFiles;
    }

    private function generateBreadcrumbs($folder)
    {
        $breadcrumbs = [];
        $segments = explode('/', $folder);

        $currentPath = '';
        foreach ($segments as $segment) {
            $currentPath .= $segment . '/';
            $breadcrumbs[] = [
                'name' => $segment,
                'path' => $currentPath,
            ];
        }

        return $breadcrumbs;
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => "required|string",
            'path' => "required|string"
        ]);

        $folderPath = $request->path;
        $newFolderName = $request->name;

        $newFolderPath = $folderPath . '/' . $newFolderName;

        if (!Storage::exists($newFolderPath)) {
             Storage::makeDirectory($newFolderPath);
           
            return redirect()->back()->with('success', 'Folder created successfully');
        }

        return redirect()->back()->withErrors(['name' => 'Folder already exists']);
    }
}
