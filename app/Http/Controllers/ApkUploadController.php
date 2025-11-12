<?php

namespace App\Http\Controllers;

use App\Models\ApkUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApkUploadController extends Controller
{
    public function create()
    {
        $apkData = ApkUpload::first();
        return view('apk-upload',compact('apkData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'apkFile' => 'required|file|mimetypes:application/vnd.android.package-archive,application/octet-stream,application/zip|max:102400',
            'versionCode' => 'required',
            'versionName' => 'required',
        ]);

        $path = $request->file('apkFile')->store('apk_files', 'public');

        $apk = ApkUpload::where('id', $request->main_id)->first();

        if(!empty($apk)){
            $apk->update([
                'version_code' => $request->versionCode,
                'version_name' => $request->versionName,
                'file_path' => $path,
            ]);

        }else{
            ApkUpload::create([
                'version_code' => $request->versionCode,
                'version_name' => $request->versionName,
                'file_path' => $path,
            ]);
        }
        
        return back()->with('success', 'APK uploaded and saved successfully!')
                    ->with('file', $path);
    }
}
