<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User') }}
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
                                                <div class="card-header">Edit User Details</div>

                                                <div class="card-body">
                                                    <a href="{{ route('dbi.index') }}" class="btn btn-secondary mb-3">Back</a>

                                                    <!-- Display success or error messages if needed -->
                                                    @if(session('success'))
                                                        <div class="alert alert-success">
                                                            {{ session('success') }}
                                                        </div>
                                                    @endif

                                                    <form method="POST" action="{{ route('users.update-password', $user->id) }}">
                                                        @csrf
                                                        @method('PUT')

                                                        <!-- Password -->
                                                        <div class="mt-4">
                                                            <label for="password" class="block font-medium text-sm text-gray-700">New Password</label>
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
                                                        </div>

                                                        <div class="flex items-center justify-end mt-4">
                                                            <x-primary-button class="ml-4">
                                                                {{ __('Submit') }}
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
