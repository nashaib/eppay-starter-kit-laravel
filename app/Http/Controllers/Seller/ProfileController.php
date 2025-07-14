<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $seller = auth()->guard('seller')->user();
        return view('seller.profile.edit', compact('seller'));
    }

    public function update(Request $request)
    {
        $seller = auth()->guard('seller')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('sellers')->ignore($seller->id)],
            'phone' => 'required|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Verify current password if changing password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $seller->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
        }

        // Update basic info
        $seller->name = $request->name;
        $seller->email = $request->email;
        $seller->phone = $request->phone;

        // Update password if provided
        if ($request->filled('new_password')) {
            $seller->password = Hash::make($request->new_password);
        }

        $seller->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateShop(Request $request)
    {
        $seller = auth()->guard('seller')->user();

        $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_slug' => ['required', 'string', 'max:255', Rule::unique('sellers')->ignore($seller->id)],
            'description' => 'nullable|string',
            'address' => 'required|string',
            'eppay_wallet_address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($seller->logo) {
                Storage::disk('public')->delete($seller->logo);
            }
            $seller->logo = $request->file('logo')->store('sellers/logos', 'public');
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            // Delete old banner
            if ($seller->banner) {
                Storage::disk('public')->delete($seller->banner);
            }
            $seller->banner = $request->file('banner')->store('sellers/banners', 'public');
        }

        // Update shop info
        $seller->shop_name = $request->shop_name;
        $seller->shop_slug = Str::slug($request->shop_slug);
        $seller->description = $request->description;
        $seller->address = $request->address;
        $seller->eppay_wallet_address = $request->eppay_wallet_address;

        $seller->save();

        return back()->with('success', 'Shop information updated successfully.');
    }
}