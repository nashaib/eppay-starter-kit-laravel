<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by verification status
        if ($request->filled('verified')) {
            if ($request->verified === 'yes') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['orders', 'reviews']);
        
        $stats = [
            'total_orders' => $user->orders()->count(),
            'completed_orders' => $user->orders()->where('payment_status', 'completed')->count(),
            'total_spent' => $user->orders()->where('payment_status', 'completed')->sum('total_amount'),
            'reviews_given' => $user->reviews()->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'verified' => 'required|boolean',
        ]);

        $user->update($request->only(['name', 'email', 'phone']));

        // Update verification status
        if ($request->verified && !$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        } elseif (!$request->verified && $user->hasVerifiedEmail()) {
            $user->email_verified_at = null;
            $user->save();
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }
}