<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// use App\Helpers\Helpers;

class SettingsController extends Controller
{
    public function index()
    {

        return view('settings')->with([
            // 'countries' => Helpers::countryList()
            'countries' => countryList()
        ]);
    }

    public function update(Request $request)
    {

        $request->validate([
            'name'      => ['required', 'max:255', 'string'],
            'email'     => ['required', 'max:255', 'string'],
            'company'   => ['nullable', 'max:255', 'string'],
            'phone'     => ['nullable', 'max:255', 'string'],
            'country'   => ['nullable', 'max:255', 'string']
        ]);

        $user = User::find(Auth::id());

        $thumb = $user->thumbnail;

        if (!empty($request->file('thumbnail'))) {
            Storage::delete('public/uploads/' . $thumb);

            $filename = $request->file('thumbnail')->getClientOriginalName();

            $thumb = time() . '-' . $request->file('thumbnail')->getClientOriginalName();
            $request->file('thumbnail')->storeAs('public/uploads', $thumb);
        }


        if (!empty($request->file('invoice_logo'))) {
            $invoice_logo = 'invoice_logo.png';
            // $invoice_logo = 'invoice_logo.' . $request->file('invoice_logo')->getClientOriginalExtension();
            $request->file('invoice_logo')->storeAs('public/uploads', $invoice_logo);
        }

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'company'   => $request->company,
            'phone'     => $request->phone,
            'country'   => $request->country,
            'thumbnail' => $thumb
        ]);

        return redirect()->back()->with('success', 'User Updated!');
    }
}
