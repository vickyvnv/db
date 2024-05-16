<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('DBI') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar -->
        <div class="w-1/4 mr-8 hidden sm:block">
            <!-- Include your custom sidebar component -->
            <x-sidebar-dbi link1="dbi" link2="" link3="" link4="" link5="" />
        </div>

        <!-- Main Content -->
        <div class="w-3/4">
            <div class="content">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h2 class="text-lg font-semibold mb-4">DBI Requests</h2>

                            <!-- Display Success and Error Messages -->
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <!-- Display DBI Requests Table -->
                            <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Priority ID</th>
                                            <th>Software Version</th>
                                            <th>DBI Type</th>
                                            <th>TT ID</th>
                                            <th>SERF CR ID</th>
                                            <th>Reference DBI</th>
                                            <th>Brief Description</th>
                                            <th>Problem Description</th>
                                            <th>Business Impact</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dbiRequests as $dbiRequest)
                                            <tr>
                                                <td>{{ $dbiRequest->id }}</td>
                                                <td>{{ $dbiRequest->priority_id }}</td>
                                                <td>{{ $dbiRequest->sw_version }}</td>
                                                <td>{{ $dbiRequest->dbi_type }}</td>
                                                <td>{{ $dbiRequest->tt_id }}</td>
                                                <td>{{ $dbiRequest->serf_cr_id }}</td>
                                                <td>{{ $dbiRequest->reference_dbi }}</td>
                                                <td>{{ $dbiRequest->brief_desc }}</td>
                                                <td>{{ $dbiRequest->problem_desc }}</td>
                                                <td>{{ $dbiRequest->business_impact }}</td>
                                                <td>
                                                    <a href="{{ route('dbi.show', $dbiRequest->id) }}" class="btn btn-primary">View</a>
                                                    <a href="{{ route('dbi.edit', $dbiRequest->id) }}" class="btn btn-secondary">Edit</a>
                                                    <!-- Add delete button with form submission if needed -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<style>
    .edit-button {
        color: inherit;
    text-decoration: inherit;
    padding: 12px 20px;
    font-size: 0.875rem;
    color: #ffffff;
    background-color: Green;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;

    }
 /* Custom styles for the search form */
.form-container {
    max-width: 800px; /* Adjust the maximum width as needed */
    margin: 0 auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-container h2 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333333;
    margin-bottom: 20px;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.form-group {
    flex-basis: calc(33.33% - 10px); /* Adjust based on the number of columns */
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    color: #666666;
    margin-bottom: 8px;
}

.form-group input[type="text"] {
    width: 100%;
    padding: 10px;
    font-size: 0.875rem;
    border: 1px solid #cccccc;
    border-radius: 4px;
    transition: border-color 0.3s ease-in-out;
}

.form-group input[type="text"]:focus {
    outline: none;
    border-color: #007bff;
}

button {
    padding: 12px 20px;
    font-size: 0.875rem;
    color: #ffffff;
    background-color: #007bff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}

button:hover {
    background-color: #0056b3;
}

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


    .card {
        margin-top: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .table th {
        background-color: #f2f2f2;
    }

    .table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>