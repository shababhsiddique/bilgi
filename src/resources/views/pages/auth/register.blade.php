@extends('layouts.app')

@section('content')
    <section class="bg-gray-50 min-h-screen flex justify-center pt-12">
        <div class="w-full max-w-xl mx-auto px-4 py-10">
            <div class="bg-white shadow-md rounded-lg p-6 md:p-8">
                {{-- Tabs --}}
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                        <a
                            class="tab-trigger border-b-2 border-transparent text-emerald-600 hover:border-gray-300  px-1 pb-2 text-sm font-medium"
                            href="{{route('login')}}"
                        >
                            Login
                        </a>
                        <button
                            type="button"
                            class="tab-trigger border-b-2 border-emerald-600 text-gray-500 hover:text-gray-700 px-1 pb-2 text-sm font-medium"
                            >
                            Register
                        </button>
                    </nav>
                </div>

                {{-- Register Panel --}}
                <div id="panel-register" class="">
                    @if ($errors->any())
                        <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700 border border-red-200">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label for="register-name" class="block text-sm font-medium text-gray-700 mb-1">
                                Full Name
                            </label>
                            <input
                                id="register-name"
                                type="text"
                                name="full_name"
                                value="{{ old('full_name') }}"
                                required
                                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >
                        </div>

                        <div>
                            <label for="register-email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>
                            <input
                                id="register-email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >
                        </div>

                        <div>
                            <label for="register-phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Phone
                            </label>
                            <input
                                id="register-phone"
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                required
                                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >
                        </div>

                        <div>
                            <label for="register-password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password
                            </label>
                            <input
                                id="register-password"
                                type="password"
                                name="password"
                                required
                                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >
                        </div>

                        <div>
                            <label for="register-password-confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm Password
                            </label>
                            <input
                                id="register-password-confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >
                        </div>

                        <div>
                            <button
                                type="submit"
                                class="w-full inline-flex justify-center items-center rounded-md border border-transparent bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                            >
                                Register
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
