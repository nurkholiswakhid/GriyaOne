<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LoginSettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::getAllAsArray();
        return view('admin.login-settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'login_badge'            => 'nullable|string|max:100',
            'login_heading'          => 'nullable|string|max:100',
            'login_heading_highlight'=> 'nullable|string|max:100',
            'login_description'      => 'nullable|string|max:500',
            'login_feature_1_title'  => 'nullable|string|max:100',
            'login_feature_1_desc'   => 'nullable|string|max:200',
            'login_feature_2_title'  => 'nullable|string|max:100',
            'login_feature_2_desc'   => 'nullable|string|max:200',
            'login_feature_3_title'  => 'nullable|string|max:100',
            'login_feature_3_desc'   => 'nullable|string|max:200',
            'login_welcome'          => 'nullable|string|max:100',
            'login_welcome_sub'      => 'nullable|string|max:200',
            'login_app_tagline'      => 'nullable|string|max:100',
            'site_name'              => 'nullable|string|max:100',
            'login_wa_number'        => 'nullable|string|max:30',
            'login_logo'             => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
        ]);

        // Text fields
        $textFields = [
            'login_badge', 'login_heading', 'login_heading_highlight', 'login_description',
            'login_feature_1_title', 'login_feature_1_desc',
            'login_feature_2_title', 'login_feature_2_desc',
            'login_feature_3_title', 'login_feature_3_desc',
            'login_welcome', 'login_welcome_sub', 'login_app_tagline',
            'login_wa_number', 'site_name',
        ];

        foreach ($textFields as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        // Logo upload
        if ($request->hasFile('login_logo')) {
            // Delete old logo if exists
            $oldPath = Setting::get('login_logo_path', '');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('login_logo')->store('settings', 'public');
            Setting::set('login_logo_path', $path);
        }

        // Remove logo if checkbox ticked
        if ($request->boolean('remove_logo')) {
            $oldPath = Setting::get('login_logo_path', '');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            Setting::set('login_logo_path', '');
        }

        return redirect()->route('admin.login-settings.edit')
            ->with('success', 'Pengaturan halaman login berhasil disimpan.');
    }
}
