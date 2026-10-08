<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\Contact;

class MessageController extends Controller
{
    public function index()
    {
        $subscribers = Newsletter::latest()->paginate(10);

        $contacts = Contact::orderBy('is_read', 'asc')
            ->latest()
            ->paginate(10);

        return view('admin.messages.index', compact('subscribers', 'contacts'));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        // mark as read automatically
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.messages.show', compact('contact'));
    }
}
