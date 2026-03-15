<?php

namespace App\Http\Controllers\Settings;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\StoreSetting;
use App\Http\Controllers\Controller;

class StoreSettingController extends Controller
{
    public function index()
    {
        $setting = StoreSetting::first() ?: new StoreSetting([
            'name' => 'Sekolah Laravel',
            'address' => 'Jl Pegangsaan Timur No. 15',
            'phone' => '0858-0123-0123'
        ]);

        return Inertia::render('admin/settings/store/index', [
            "title" => 'POS | Store Settings',
            "additional" => [
                'setting' => $setting
            ]
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $setting = StoreSetting::first();
        if ($setting) {
            $setting->update($request->only('name', 'address', 'phone'));
        } else {
            StoreSetting::create($request->only('name', 'address', 'phone'));
        }

        return back()->with('success', 'Store settings updated successfully');
    }
}
