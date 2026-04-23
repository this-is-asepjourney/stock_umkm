<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        // Update settings logic here
        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui');
    }
}