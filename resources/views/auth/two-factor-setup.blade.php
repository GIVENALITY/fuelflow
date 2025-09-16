@extends('layouts.auth')

@section('title', 'Setup Two-Factor Authentication')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div
                        class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Secure Your Account</h2>
                    <p class="mt-2 text-sm text-gray-600">Add an extra layer of security with two-factor authentication</p>
                </div>

                <!-- Setup Steps -->
                <div class="space-y-6">
                    <!-- Step 1: Download App -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Step 1: Download an Authenticator App</h3>
                        <p class="text-sm text-blue-700 mb-3">We recommend using one of these apps:</p>
                        <div class="flex space-x-4">
                            <a href="#" class="flex items-center space-x-2 text-sm text-blue-600 hover:text-blue-800">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <span>Google Authenticator</span>
                            </a>
                            <a href="#" class="flex items-center space-x-2 text-sm text-blue-600 hover:text-blue-800">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <span>Authy</span>
                            </a>
                        </div>
                    </div>

                    <!-- Step 2: QR Code -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Step 2: Scan QR Code</h3>
                        <p class="text-sm text-gray-600 mb-4">Scan this QR code with your authenticator app:</p>

                        <div class="flex justify-center">
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrCodeUrl) }}"
                                    alt="QR Code" class="mx-auto">
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <p class="text-xs text-gray-500 mb-2">Can't scan? Enter this code manually:</p>
                            <div class="bg-gray-100 rounded px-3 py-2 font-mono text-sm text-gray-800">
                                {{ $secretKey }}
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Verification -->
                    <form method="POST" action="{{ route('two-factor.enable') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="secret" value="{{ $secretKey }}">

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                Step 3: Enter Verification Code
                            </label>
                            <input type="text" id="code" name="code" maxlength="6"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-lg font-mono tracking-widest @error('code') border-red-500 @enderror"
                                placeholder="000000" required>
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                            Enable Two-Factor Authentication
                        </button>
                    </form>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        Skip for now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-format the verification code input
        document.getElementById('code').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 6);
        });
    </script>
@endsection