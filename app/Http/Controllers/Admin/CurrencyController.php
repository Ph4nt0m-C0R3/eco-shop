<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::orderBy('code')->get();
        return view('admin.settings.currencies.index', compact('currencies'));
    }

    public function update(Request $request, $id)
    {
        $currency = Currency::findOrFail($id);

        if ($currency->is_base) {
            return redirect()->back()->with('error', 'Base currency cannot be modified.');
        }

        $request->validate([
            'rate' => 'required|numeric|min:0',
        ]);

        $currency->update([
            'rate' => $request->rate,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Currency updated successfully.');
    }
}
