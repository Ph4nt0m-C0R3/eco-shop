<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShippingZoneController extends Controller
{
    public function index()
    {
        // Only show Myanmar zones
        $zones = ShippingZone::where('country', 'Myanmar')
                    ->orderBy('id', 'asc')
                    ->get();

        return view('admin.settings.shipping.index', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('shipping_zones')->where('country', 'Myanmar'),
            ],
            'fee_mmk' => 'required|numeric|min:0',
        ]);

        ShippingZone::create([
            'country'   => 'Myanmar',
            'name'      => $request->name,
            'fee_mmk'   => $request->fee_mmk,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'New Myanmar shipping zone created');
    }

    public function update(Request $request, $id)
    {
        $zone = ShippingZone::where('country', 'Myanmar')->findOrFail($id);

        $request->validate([
            'fee_mmk' => 'required|numeric|min:0',
        ]);

        $zone->update([
            'fee_mmk'   => $request->fee_mmk,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success','Shipping updated successfully.');
    }
}
