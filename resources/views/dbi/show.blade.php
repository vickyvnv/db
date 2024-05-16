<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('DBI Request Details') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('dbi.index') }}">DBI Home</a></li>
                <li><a href="#">Change Role</a></li>
                <li><a href="#">Search DBI</a></li>
                <li><a href="#">List My DBI</a></li>
                <li><a href="#">New DBI</a></li>
                <li><a href="#">Cleanup</a></li>
                <li><a href="#">Documentation</a></li>
                <!-- Add more sidebar links here -->
            </ul>
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
                                        <button class="btn btn-primary" type="submit"><a href="{{ route('dbi.index') }}" class="btn btn-primary">Back</a></button>
                                        <div class="col-md-10">
                                            
                                            <div class="card-body d-flex justify-content-center">
                                                <div class="card-header">DBI Request Details</div>

                                                <div class="card-body">
                                                <a href="{{ route('dbi.index') }}" class="btn btn-secondary mb-3">Back</a>

                                                    <!-- Display success or error messages if needed -->
                                                    @if (session('status'))
                                                        <div class="alert alert-success" role="alert">
                                                            {{ session('status') }}
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
                                                    <p>DBI Category: {{ $dbiRequest->category }}</p>
                                                    <p>Priority: {{ $dbiRequest->priority_id }}</p>
                                                    <p>Market: {{ $dbiRequest->sw_version }}</p>
                                                    <p>DBI Type: {{ $dbiRequest->dbi_type }}</p>
                                                    <p>TT Number: {{ $dbiRequest->tt_id }}</p>
                                                    <p>Serf/CR: {{ $dbiRequest->serf_cr_id }}</p>
                                                    <p>Reference DBI: {{ $dbiRequest->reference_dbi }}</p>
                                                    <p>Brief Description: {{ $dbiRequest->brief_desc }}</p>
                                                    <p>Problem Description: {{ $dbiRequest->problem_desc }}</p>
                                                    <p>Business Impact: {{ $dbiRequest->business_impact }}</p>
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
</style>
