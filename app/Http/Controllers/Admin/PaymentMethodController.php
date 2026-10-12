<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::latest()->get();
        $currencies = Currency::where('is_active', true)->get();

        return view('admin.settings.payment_methods.index', compact('methods', 'currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_mm' => 'nullable|string|max:255',
            'type' => 'required|string|max:50',

            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',

            'currency_code' => 'required|string|max:10',

            'qr_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // icon (must be 512x512)
            'icon' => 'nullable|image|mimes:png,webp,jpg,jpeg|max:2048|dimensions:width=512,height=512',
        ]);

        $data = $request->only([
            'name_en',
            'name_mm',
            'type',
            'account_name',
            'account_number',
            'currency_code',
        ]);

        $data['is_active'] = $request->input('is_active') == 1;

        if ($request->hasFile('qr_image')) {
            $data['qr_image'] = $request->file('qr_image')->store('payment_methods/qr', 'public');
        }

        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('payment_methods/icons', 'public');
        }

        PaymentMethod::create($data);

        return redirect()->back()->with('success', 'Payment method created successfully.');
    }

    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_mm' => 'nullable|string|max:255',
            'type' => 'required|string|max:50',

            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',

            'currency_code' => 'required|string|max:10',

            'qr_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // icon (must be 512x512)
            'icon' => 'nullable|image|mimes:png,webp,jpg,jpeg|max:2048|dimensions:width=512,height=512',
        ]);

        $data = $request->only([
            'name_en',
            'name_mm',
            'type',
            'account_name',
            'account_number',
            'currency_code',
        ]);

        $data['is_active'] = (bool) $request->input('is_active');

        if ($request->hasFile('qr_image')) {
            if ($method->qr_image && Storage::disk('public')->exists($method->qr_image)) {
                Storage::disk('public')->delete($method->qr_image);
            }

            $data['qr_image'] = $request->file('qr_image')->store('payment_methods/qr', 'public');
        }

        if ($request->hasFile('icon')) {
            if ($method->icon && Storage::disk('public')->exists($method->icon)) {
                Storage::disk('public')->delete($method->icon);
            }

            $data['icon'] = $request->file('icon')->store('payment_methods/icons', 'public');
        }

        $method->update($data);

        return redirect()->back()->with('success', 'Payment method updated successfully.');
    }

    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);

        if ($method->qr_image && Storage::disk('public')->exists($method->qr_image)) {
            Storage::disk('public')->delete($method->qr_image);
        }

        if ($method->icon && Storage::disk('public')->exists($method->icon)) {
            Storage::disk('public')->delete($method->icon);
        }

        $method->delete();

        return redirect()->back()->with('success', 'Payment method deleted successfully.');
    }
}
