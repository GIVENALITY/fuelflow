@extends('layouts.auth')

@section('title', 'Two-Factor Authentication')

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
                    <h2 class="text-3xl font-bold text-gray-900">Two-Factor Authentication</h2>
                    <p class="mt-2 text-sm text-gray-600">Enter the code from your authenticator app</p>
                </div>

                <!-- Verification Form -->
                <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            Verification Code
                        </label>
                        <input type="text" id="code" name="code" maxlength="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-2xl font-mono tracking-widest @error('code') border-red-500 @enderror"
                            placeholder="000000" required autofocus>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        Verify Code
                    </button>
                </form>

                <!-- Recovery Code Option -->
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
                        <a href="{{ route('two-factor.recovery') }}"
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-medium transition duration-200 text-center block">
                            Use Recovery Code Instead
                        </a>
                    </div>
                </div>

                <!-- Help Text -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Having trouble?
                        <a href="{{ route('two-factor.recovery') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            Use a recovery code
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-format the verification code input
        document.getElementById('code').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 6);

            // Auto-submit when 6 digits are entered
            if (e.target.value.length === 6) {
                e.target.form.submit();
            }
        });

        // Focus on input when page loads
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('code').focus();
        });
    </script>
@endsection