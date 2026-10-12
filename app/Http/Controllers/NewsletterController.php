<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $email = $request->email;

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()
                ->with('subscribe_error', 'invalid');
        }

        // Check if already subscribed
        if (Newsletter::where('email', $email)->exists()) {
            return redirect()->back()
                ->with('subscribe_error', 'exists');
        }

        // Create subscription
        Newsletter::create([
            'email' => $email
        ]);

        return redirect()->back()
            ->with('subscribe_success', true);
    }
}
