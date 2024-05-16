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
                <!-- Your content here -->
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <button class="btn btn-primary" type="submit"><a href="{{ route('dbi.index') }}" class="btn btn-primary">Back</a></button>
                                        <div class="col-md-10">
                                            
                                            <div class="card-body d-flex justify-content-center">
                                                <div class="card-header">DBI Request Details</div>

                                                <div class="card-body">
                                                <a href="{{ route('dbi.index') }}" class="btn btn-secondary mb-3">Back</a>

                                                    <!-- Display success or error messages if needed -->
                                                    <form method="POST" action="{{ route('users.store') }}">
                                                        @csrf

                                                        <!-- First Name -->
                                                        <div class="mt-4">
                                                            <label for="user_firstname" class="block font-medium text-sm text-gray-700">First Name</label>
                                                            <input id="user_firstname" type="text" name="user_firstname" value="{{ old('user_firstname') }}" required autofocus
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('user_firstname')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="mt-4">
                                                            <label for="user_lastname" class="block font-medium text-sm text-gray-700">Last Name</label>
                                                            <input id="user_lastname" type="text" name="user_lastname" value="{{ old('user_lastname') }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('user_lastname')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- User Department -->
                                                        <div class="mt-4">
                                                            <label for="user_department" class="block font-medium text-sm text-gray-700">User Department</label>
                                                            <input id="user_department" type="text" name="user_department" value="{{ old('user_department') }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('user_department')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- User Company -->
                                                        <div class="mt-4">
                                                            <label for="company" class="block font-medium text-sm text-gray-700">Company</label>
                                                            <input id="company" type="text" name="company" value="{{ old('company') }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('company')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- User Position -->
                                                        <div class="mt-4">
                                                            <label for="position" class="block font-medium text-sm text-gray-700">Position</label>
                                                            <input id="position" type="text" name="position" value="{{ old('position') }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('position')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Email Address -->
                                                        <div class="mt-4">
                                                            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('email')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- User phone -->
                                                        <div class="mt-4">
                                                            <label for="phone" class="block font-medium text-sm text-gray-700">Phone</label>
                                                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('phone')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Mobile -->
                                                        <div class="mt-4">
                                                            <label for="mobile" class="block font-medium text-sm text-gray-700">Mobile</label>
                                                            <input id="mobile" type="text" name="mobile" value="{{ old('mobile') }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('mobile')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- username -->
                                                        <div class="mt-4">
                                                            <label for="username" class="block font-medium text-sm text-gray-700">Username</label>
                                                            <input id="username" type="text" name="username" value="{{ old('username') }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('username')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Password -->
                                                        <div class="mt-4">
                                                            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                                                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('password')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Confirm Password -->
                                                        <div class="mt-4">
                                                            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm Password</label>
                                                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('password_confirmation')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>


                                                        <div class="flex items-center justify-end mt-4">
                                                            <!-- <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                                                                {{ __('Already registered?') }}
                                                            </a> -->

                                                            <x-primary-button class="ml-4">
                                                                {{ __('Save') }}
                                                            </x-primary-button>
                                                        </div>
                                                        @if(session('success'))
                                                            <div class="alert alert-success">
                                                                {{ session('success') }}
                                                            </div>
                                                        @endif
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
    </div>
</x-app-layout>

<style>
    .sidebar {
        width: 250px; /* Adjust width as needed */
        height: 100%;
        background-color: #f4f4f4;
        padding: 20px;
        float: left; /* Added to align sidebar to left */
    }

    .sidebar-menu {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        margin-bottom: 10px;
    }

    .sidebar-menu li a {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #333;
        transition: background-color 0.3s;
    }

    .sidebar-menu li a:hover {
        background-color: #ddd;
    }

    

    .card {
        margin-top: 20px;
    }

    .sidebar-menu ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    .sidebar-menu ul li {
        margin-left: 20px; /* Adjust indentation as needed */
    }

    .sidebar-menu ul li a {
        display: block;
        padding: 8px 15px;
        color: #666;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .sidebar-menu ul li a:hover {
        background-color: #f0f0f0;
    }

    .text-red-500 {
        color:red;
    }
</style>
