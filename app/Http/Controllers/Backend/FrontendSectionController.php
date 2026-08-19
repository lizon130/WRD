<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\FrontendSection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Storage; 

class FrontendSectionController extends Controller
{
    public function journey()
    {
        return view('backend.pages.setting.journey-unity-content');
    }

    public function update(Request $request){
        $data = [];

        // foreach($request->file() as $key => $val){
        //     if ($request->hasFile($key)) {
        //         // if (file_exists(public_path('uploads/settings/'.Helper::getSettings($key)))) {
        //         //     unlink(public_path('uploads/settings/'.Helper::getSettings($key)));
        //         // }
        //         $image = $request->file($key);
        //         $filename = time().uniqid().$image->getClientOriginalName();
        //         $image->move(public_path('uploads/settings'), $filename);
        //         $data[$key] = "uploads/settings". $filename;
        //     }
        // }


        foreach ($request->file() as $key => $val) {
            if ($request->hasFile($key)) {
                // Ensure directory exists
                $directory = public_path('uploads/settings');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true, true);
                }

                // Generate a unique filename
                $image = $request->file($key);
                $filename = time() . uniqid() . '.' . $image->getClientOriginalName();
                
                // Move file to destination
                $image->move($directory, $filename);

                // Store file path correctly
                $data[$key] = "uploads/settings/" . $filename;
            }
        }


        foreach ($request->input() as $key => $val) {
            if (!is_array($val)) {
                $request->validate([
                    $val => 'nullable | string'
                ]);
                $data[$key] = $val;
            } else {
                $data[$key] = implode(',', $val);
            }
        }
        unset($data['_token']);

        foreach ($data as $key => $val) {
            $settings = FrontendSection::updateOrCreate(
                ['key' =>  $key],
                [
                    'value' => $val,
                    'status' => 1,
                    'identifier' => $key
                ]
            );
        }
        session()->flash('success', 'Settings Successfully Updated!');
        return back();
    }
}
