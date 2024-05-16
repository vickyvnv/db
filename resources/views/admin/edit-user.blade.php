<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar content -->
        </div>

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
                                        <div class="col-md-10">
                                            <div class="card-body d-flex justify-content-center">
                                                <div class="card-header">Edit User Details</div>
                                                <div class="card-body">
                                                    <form method="POST" action="{{ route('user.update', $user->id) }}">
                                                        @csrf
                                                        @method('PUT')

                                                        <!-- First Name -->
                                                        <div class="mt-4">
                                                            <label for="user_firstname" class="block font-medium text-sm text-gray-700">First Name</label>
                                                            <input id="user_firstname" type="text" name="user_firstname" value="{{ $user->user_firstname }}" required autofocus class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('user_firstname')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="mt-4">
                                                            <label for="user_lastname" class="block font-medium text-sm text-gray-700">Last Name</label>
                                                            <input id="user_lastname" type="text" name="user_lastname" value="{{ $user->user_lastname }}" required class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('user_lastname')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- User Department -->
                                                        <div class="mt-4">
                                                            <label for="user_department" class="block font-medium text-sm text-gray-700">User Department</label>
                                                            <input id="user_department" type="text" name="user_department" value="{{ $user->user_department }}" required class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('user_department')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- User Company -->
                                                        <!-- Add more input fields as needed -->

                                                        <!-- Role Selection -->
                                                        <div class="mt-4">
                                                            <label for="role" class="block font-medium text-sm text-gray-700">Role</label>
                                                            <select id="role" name="role" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                <option value="">Select Role</option>
                                                                @foreach($roles as $role)
                                                                    <option value="{{ $role->id }}" @if($role->id == $user->role_id) selected @endif>{{ $role->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Rights Selection -->
                                                        <div class="mt-4">
                                                            <label for="rights" class="block font-medium text-sm text-gray-700">Rights</label>
                                                            <select id="rights" name="rights[]" multiple class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                @foreach($rights as $right)
                                                                    <option value="{{ $right->id }}" @if(in_array($right->id, $user->rights->pluck('id')->toArray())) selected @endif>{{ $right->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Save Button -->
                                                        <div class="flex items-center justify-end mt-4">
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

    .container {
        float: left; /* Added to align main content to left */
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