<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administration') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar -->
        @include('partials.admin-sidebar')

        <!-- Main Content -->
        <div class="w-3/4">
            <div class="content">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <a href="{{ route('dbi.index') }}" class="btn btn-primary mb-4">Back</a>
                                        <div class="col-md-10">
                                            <div class="card-body">
                                                <h3 class="text-lg font-semibold mb-4">Create New User</h3>

                                                @if(session('success'))
                                                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                                                        {{ session('success') }}
                                                    </div>
                                                @endif

                                                <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                                                    @csrf

                                                    <div class="grid grid-cols-2 gap-6">
                                                        <!-- Left Column -->
                                                        <div class="space-y-6">
                                                            <!-- First Name -->
                                                            <div>
                                                                <label for="user_firstname" class="block font-medium text-sm text-gray-700">First Name</label>
                                                                <input id="user_firstname" type="text" name="user_firstname" value="{{ old('user_firstname') }}" required autofocus
                                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                @error('user_firstname')
                                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            <!-- User Department -->
                                                            <div>
                                                                <label for="user_department" class="block font-medium text-sm text-gray-700">User Department</label>
                                                                <input id="user_department" type="text" name="user_department" value="{{ old('user_department') }}" required
                                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                @error('user_department')
                                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            <!-- User Position -->
                                                            <div>
                                                                <label for="position" class="block font-medium text-sm text-gray-700">Position</label>
                                                                <input id="position" type="text" name="position" value="{{ old('position') }}" required
                                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                @error('position')
                                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            <!-- User phone -->
                                                            <div>
                                                                <label for="phone" class="block font-medium text-sm text-gray-700">Phone</label>
                                                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                @error('phone')
                                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            <!-- username -->
                                                            <div>
                                                                <label for="username" class="block font-medium text-sm text-gray-700">Username</label>
                                                                <input id="username" type="text" name="username" value="{{ old('username') }}" required
                                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                @error('username')
                                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            <!-- Password -->
                                                            <div>
                                                                <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                                                                <input id="password" type="password" name="password" required autocomplete="new-password"
                                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                @error('password')
                                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Right Column -->
                                                        <div class="space-y-6">
                                                            <!-- Last Name -->
                                                            <div>
                                                                <label for="user_lastname" class="block font-medium text-sm text-gray-700">Last Name</label>
                                                                <input id="user_lastname" type="text" name="user_lastname" value="{{ old('user_lastname') }}" required
                                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                @error('user_lastname')
                                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            <!-- User Company -->
                                                            <div>
                                                                <label for="company" class="block font-medium text-sm text-gray-700">Company</label>
                                                                <input id="company" type="text" name="company" value="{{ old('company') }}" required
                                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                @error('company')
                                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            <!-- Email Address -->
                                                            <div>
                                                                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                @error('email')
                                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            <!-- Mobile -->
                                                            <div>
                                                                <label for="mobile" class="block font-medium text-sm text-gray-700">Mobile</label>
                                                                <input id="mobile" type="text" name="mobile" value="{{ old('mobile') }}" required
                                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                @error('mobile')
                                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            <!-- Confirm Password -->
                                                            <div>
                                                                <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm Password</label>
                                                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                @error('password_confirmation')
                                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex items-center justify-end mt-6">
                                                        <x-primary-button class="ml-4">
                                                            {{ __('Save') }}
                                                        </x-primary-button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .text-red-500 {
        color: red;
    }
</style>