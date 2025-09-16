@extends('layouts.auth')

@section('title', 'Recovery Code')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-orange-50 to-red-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div
                        class="mx-auto h-16 w-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Recovery Code</h2>
                    <p class="mt-2 text-sm text-gray-600">Enter one of your recovery codes to access your account</p>
                </div>

                <!-- Warning -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Important</h3>
                            <div class="mt-1 text-sm text-yellow-700">
                                <p>Each recovery code can only be used once. After using a code, it will be removed from
                                    your account.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recovery Form -->
                <form method="POST" action="{{ route('two-factor.recovery.verify') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="recovery_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Recovery Code
                        </label>
                        <input type="text" id="recovery_code" name="recovery_code"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-center text-lg font-mono tracking-widest @error('recovery_code') border-red-500 @enderror"
                            placeholder="XXXXXXXX" required autofocus>
                        @error('recovery_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-200">
                        Use Recovery Code
                    </button>
                </form>

                <!-- Back to 2FA -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('two-factor.login') }}"
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-medium transition duration-200 text-center block">
                            Back to Authenticator Code
                        </a>
                    </div>
                </div>

                <!-- Help Text -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Don't have recovery codes?
                        <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-800 font-medium">
                            Contact support
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-format the recovery code input
        document.getElementById('recovery_code').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/[^A-Z0-9]/g, '').toUpperCase().substring(0, 8);
        });

        // Focus on input when page loads
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('recovery_code').focus();
        });
    </script>
@endsection