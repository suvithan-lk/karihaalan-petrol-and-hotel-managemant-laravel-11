<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Show contact form
    public function create()
    {
         // Get all contacts

         return view('contacts.addcontact');
    }

    public function show(){
        $contacts = Contact::all();
        return view('contacts.contacts', compact('contacts'));
    }

    // Store contact data
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        // Store data in database
        Contact::create($request->all());

        // Redirect with success message
        return redirect()->route('contact.create')->with('success', 'Contact added successfully!');
    }
}
