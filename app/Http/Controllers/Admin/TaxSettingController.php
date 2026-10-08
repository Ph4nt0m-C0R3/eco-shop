<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxSetting;
use Illuminate\Http\Request;

class TaxSettingController extends Controller
{
    public function index()
    {
        $tax = TaxSetting::firstOrCreate([]);
        return view('admin.settings.tax.index', compact('tax'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'percent' => 'required|numeric|min:0|max:100'
        ]);

        $tax = TaxSetting::first();
        $tax->update(['percent' => $request->percent]);

        return back()->with('success','Tax updated successfully.');
    }
}
