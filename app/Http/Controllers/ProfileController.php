<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilePasswordRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $orders = auth()->user()
            ->orders()
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        return view('account.profile', compact('orders'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        auth()->user()->update($request->validated());

        return back()->with('status', __('messages.profile.updated'));
    }

    public function updatePassword(ProfilePasswordRequest $request)
    {
        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', __('messages.profile.password_updated'));
    }
}
