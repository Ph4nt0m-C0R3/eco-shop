<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    // ===== SETTINGS (SUPERADMIN ONLY) =====
    public function settings()
    {
        // Middleware already guarantees superadmin
        return view('admin.settings.index');
    }

    // ===== VIEW ADMINS =====
    public function admins(Request $request)
    {
        $query = User::where('role', 'admin')
            ->orderByOnlineStatus();

        if ($request->filled('search')) {
            $query->whereAny(
                ['users.id', 'users.name', 'users.email', 'users.phone', 'users.address'],
                'like',
                '%' . $request->search . '%'
            );
        }

        $admins = $query->paginate(10)->withQueryString();

        // AJAX response
        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.settings.admins.partials.admin_table', compact('admins'))->render(),
                'pagination' => view('admin.settings.admins.partials.admin_pagination', compact('admins'))->render(),
            ]);
        }

        return view('admin.settings.admins.list', compact('admins'));
    }

    // ===== CREATE ADMIN PAGE =====
    public function createAdminPage()
    {
        return view('admin.settings.admins.create');
    }

    // ===== CREATE ADMIN =====
    public function createAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:15',
            'password_confirmation' => 'required|min:8|same:password|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'createAdmin')
                ->withInput();
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
            'email_verified_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('admin.settings.admins')
            ->with('success', 'Admin account created successfully.');
    }

    // ===== REMOVE ADMIN =====
    public function removeAdmin($id)
    {
        $admin = User::where('id', $id)
            ->where('role', 'admin')
            ->firstOrFail();

        if ($admin->isOnline()) {
            return back()->with('warning', 'You cannot remove an admin who is currently online.');
        }

        $admin->delete();

        return back()->with('success', 'Admin removed successfully.');
    }

    // ===== VIEW USERS =====
    public function users(Request $request)
    {
        $query = User::where('role', 'user')
            ->orderByOnlineStatus();

        // Search
        if ($request->filled('search')) {
            $query->whereAny(
                ['users.id', 'users.name', 'users.email', 'users.phone', 'users.address', 'users.provider'],
                'like',
                '%' . $request->search . '%'
            );
        }

        // Register method filter
        if ($request->filled('register_method')) {
            $query->where('provider', $request->register_method);
        }

        $users = $query
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'local'  => User::where('role', 'user')->where('provider', 'local')->count(),
            'google' => User::where('role', 'user')->where('provider', 'google')->count(),
            'github' => User::where('role', 'user')->where('provider', 'github')->count(),
        ];

        $totalUsers = User::where('role', 'user')->count();

        // AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'table' => view(
                    'admin.settings.users.partials.user_table',
                    compact('users')
                )->render(),
                'pagination' => view(
                    'admin.settings.users.partials.user_pagination',
                    compact('users')
                )->render(),
            ]);
        }

        return view(
            'admin.settings.users.list',
            compact('users', 'counts', 'totalUsers')
        );
    }

    // ===== REMOVE USER =====
    public function removeUser($id)
    {
        User::where('id', $id)
            ->where('role', 'user')
            ->delete();

        return back()->with('success', 'User removed successfully.');
    }

    // ===== SYSTEM SETTINGS PAGE =====
    public function systemSettingsPage()
    {
        // You can load from DB later if needed
        return view('admin.settings.system.index');
    }

    // ===== UPDATE SYSTEM SETTINGS =====
    public function updateSystemSettings(Request $request)
    {
        $request->validate([
            'app_name'  => 'required|string|max:255',
            'app_email' => 'required|email',
            'app_phone' => 'nullable|string|max:50',
            'address'   => 'nullable|string|max:255',
            'favicon' => 'nullable|mimes:png,jpg,ico',
            'logo'      => 'nullable|image|mimes:png,jpg,svg',
            'logo_text' => 'nullable|image|mimes:png,jpg,svg',
        ]);

        SystemSetting::updateOrCreate(
            ['key' => 'app_name'],
            ['value' => $request->app_name]
        );

        SystemSetting::updateOrCreate(
            ['key' => 'contact_email'],
            ['value' => $request->app_email]
        );

        SystemSetting::updateOrCreate(
            ['key' => 'contact_phone'],
            ['value' => $request->app_phone]
        );

        SystemSetting::updateOrCreate(
            ['key' => 'address'],
            ['value' => $request->address]
        );

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            SystemSetting::updateOrCreate(['key' => 'favicon'], ['value' => $path]);
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            SystemSetting::updateOrCreate(['key' => 'logo'], ['value' => $path]);
        }

        if ($request->hasFile('logo_text')) {
            $path = $request->file('logo_text')->store('settings', 'public');
            SystemSetting::updateOrCreate(['key' => 'logo_text'], ['value' => $path]);
        }

        Cache::forget('system_settings');

        return back()->with('success', 'Settings updated!');
    }

}
