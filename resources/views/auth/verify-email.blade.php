{{-- resources/views/auth/verify-email.blade.php --}}
@extends('layouts.marketplace')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Verify your email address
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">A new verification link has been sent to your email address.</span>
            </div>
        @endif

        <div class="mt-8 space-y-6">
            <p class="text-center text-sm text-gray-600">
                If you didn't receive the email, we will gladly send you another.
            </p>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </span>
                    Resend Verification Email
                </button>
            </form>

            <div class="text-center">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="font-medium text-blue-600 hover:text-blue-500">
                        Log out
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-8 p-4 bg-blue-50 rounded-lg">
            <h3 class="text-sm font-medium text-blue-800 mb-2">Why verify your email?</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Secure your account</li>
                <li>• Receive order updates</li>
                <li>• Get exclusive offers</li>
                <li>• Reset your password if needed</li>
            </ul>
        </div>
    </div>
</div>
@endsection