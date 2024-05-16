<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('DBI Home') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="#">User</a>
                    <ul> <!-- Sublist -->
                        <li><a href="{{ route('register') }}">Register User</a></li>
                        <li><a href="{{ route('users.index') }}">User List</a></li>
                    </ul>
                </li>
                <li><a href="#">Navigation</a>
                    <ul> <!-- Sublist -->
                        <li><a href="#">Main Menu or Application</a></li>
                        <li><a href="#">Submenus</a></li>
                    </ul>
                </li>
                <li><a href="#">Rights and Roles</a>
                    <ul> <!-- Sublist -->
                        <li><a href="{{ route('rights.index') }}">Rights</a></li>
                        <li><a href="#">Role</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="w-3/4">
            <div class="content">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-md-10">
                                            <div class="card-body d-flex justify-content-center">
                                                <div class="card-header">User List</div>
                                                <div class="card-body">
                                                    <a href="{{ route('dbi.index') }}" class="btn btn-secondary mb-3">Back</a>
                                                    @if (session('success'))
                                                        <div class="alert alert-success" role="alert">
                                                            {{ session('success') }}
                                                        </div>
                                                    @endif
                                                    @if ($errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    <!-- Display DBI request details -->
                                                    <!-- Add your user list code here -->
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
</style>
