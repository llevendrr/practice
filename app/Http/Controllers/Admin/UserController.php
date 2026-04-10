<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(12);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_USER, User::ROLE_ADMIN])],
            'phone' => 'nullable|digits_between:9,15',
            'name' => 'required|string|max:255',
        ]);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', __('messages.admin.user.updated'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', __('messages.admin.user.delete_self'));
        }

        if ($user->orders()->exists() || $user->reviews()->exists()) {
            return redirect()->route('admin.users.index')->with('error', __('messages.admin.user.delete_blocked'));
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', __('messages.admin.user.deleted'));
    }
}
