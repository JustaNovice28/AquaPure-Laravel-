<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // ============================================
    // POST /contact — Save new message (public)
    // ============================================
    public function store(Request $request)
    {
        $request->validate([
            'full_name'     => 'required|string|max:100',
            'phone_number'  => 'required|string|max:20',
            'email_address' => 'nullable|email|max:100',
            'subject'       => 'required|string|max:100',
            'message'       => 'required|string',
        ]);

        Message::create([
            'full_name'     => $request->full_name,
            'phone_number'  => $request->phone_number,
            'email_address' => $request->email_address,
            'subject'       => $request->subject,
            'message'       => $request->message,
            'status'        => 'unread',
        ]);

        return back()->with('success', 'Message sent successfully! We will get back to you soon.');
    }

    // ============================================
    // PUT /admin/messages/{id} — Update status (admin)
    // ============================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:unread,read,replied',
        ]);

        $message = Message::findOrFail($id);
        $message->status = $request->status;
        $message->save();

        return back()->with('success', 'Message status updated!');
    }

}