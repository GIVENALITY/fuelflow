@extends('layouts.auth')

@section('title', 'Two-Factor Authentication Enabled')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Success Header -->
                <div class="text-center mb-8">
                    <div
                        class="mx-auto h-16 w-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Success!</h2>
                    <p class="mt-2 text-sm text-gray-600">Two-factor authentication is now enabled for your account</p>
                </div>

                <!-- Recovery Codes -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Important: Save Your Recovery Codes</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>These recovery codes can be used to access your account if you lose your authenticator
                                    device. Store them in a safe place.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recovery Codes List -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Your Recovery Codes:</h4>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($recoveryCodes as $code)
                            <div class="bg-white rounded px-3 py-2 font-mono text-sm text-gray-800 text-center border">
                                {{ $code }}
                            </div>
                        @endforeach
                    </div>
                    <button onclick="copyRecoveryCodes()"
                        class="mt-3 w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded text-sm font-medium transition duration-200">
                        Copy All Codes
                    </button>
                </div>

                <!-- Security Tips -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2">Security Tips:</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Store recovery codes in a secure password manager</li>
                        <li>• Never share your authenticator app or recovery codes</li>
                        <li>• Use a different device for your authenticator app</li>
                        <li>• Test your setup by logging out and back in</li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <a href="{{ route('dashboard') }}"
                        class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 text-center block">
                        Continue to Dashboard
                    </a>

                    <button onclick="printRecoveryCodes()"
                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded-lg font-medium transition duration-200">
                        Print Recovery Codes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyRecoveryCodes() {
            const codes = @json($recoveryCodes);
            const text = codes.join('\n');

            navigator.clipboard.writeText(text).then(function () {
                // Show success message
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                button.classList.add('bg-green-200', 'text-green-800');

                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-200', 'text-green-800');
                }, 2000);
            });
        }

        function printRecoveryCodes() {
            const codes = @json($recoveryCodes);
            const printWindow = window.open('', '_blank');

            printWindow.document.write(`
            <html>
                <head>
                    <title>Recovery Codes - {{ config('app.name') }}</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .codes { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin: 20px 0; }
                        .code { border: 1px solid #ccc; padding: 10px; text-align: center; font-family: monospace; }
                        .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 20px 0; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>Two-Factor Authentication Recovery Codes</h1>
                        <p>Account: {{ auth()->user()->email }}</p>
                        <p>Generated: {{ now()->format('M d, Y \a\t g:i A') }}</p>
                    </div>

                    <div class="warning">
                        <strong>Important:</strong> Store these codes in a safe place. Each code can only be used once.
                    </div>

                    <div class="codes">
                        ${codes.map(code => `<div class="code">${code}</div>`).join('')}
                    </div>

                    <p><strong>Instructions:</strong> If you lose access to your authenticator app, use one of these codes to log in. After using a code, it will be removed from your account.</p>
                </body>
            </html>
        `);

            printWindow.document.close();
            printWindow.print();
        }
    </script>
@endsection