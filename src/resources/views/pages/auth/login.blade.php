@extends('layouts.app')

@section('content')
    <section class="bg-gray-50 min-h-screen flex justify-center pt-12">
        <div class="w-full max-w-xl mx-auto px-4 py-10">
            <div class="bg-white shadow-md rounded-lg p-6 md:p-8">
                {{-- Tabs --}}
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                        <button
                            type="button"
                            class="tab-trigger border-b-2 border-emerald-600 text-emerald-600 px-1 pb-2 text-sm font-medium"
                        >
                            Login
                        </button>

                        <a
                            class="tab-trigger border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 px-1 pb-2 text-sm font-medium"
                            href="{{ route('register') }}"
                        >
                            Register
                        </a>
                    </nav>
                </div>

                {{-- Login Panel --}}
                <div id="panel-login">
                    @if ($errors->any())
                        <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700 border border-red-200">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="login-username" class="block text-sm font-medium text-gray-700 mb-1">
                                Email / Phone
                            </label>
                            <input
                                id="login-username"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >
                        </div>

                        <div>
                            <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password
                            </label>
                            <input
                                id="login-password"
                                type="password"
                                name="password"
                                required
                                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <label class="inline-flex items-center text-sm text-gray-600">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="h-4 w-4 rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                >
                                <span class="ml-2">Remember me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
                                    Forgot your password?
                                </a>
                            @endif
                        </div>

                        <div>
                            <button
                                type="submit"
                                class="w-full inline-flex justify-center items-center rounded-md border border-transparent bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                            >
                                Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
