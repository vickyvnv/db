<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('DBI Requests') }}
        </h2>
    </x-slot>
    <div class="flex">
        <!-- Sidebar -->
        @include('partials.dbi-sidebar')

        <!-- Main Content -->
        <div class="w-3/4 overflow-x-auto">
            <div class="content">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @if (!empty($logs) && isset($logs[0]) && !is_null($logs[0]->dbi_request_id))
                        <a href="{{ route('dbi.show', $logs[0]->dbi_request_id) }}" class="btn btn-secondary mb-3">Back</a>
                    @endif  
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h2 class="text-lg font-semibold mb-4">DBI Requests Logs And History</h2>


                            <!-- Display DBI Requests Table -->
                            <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Requestor Id</th>
                                            <th>History</th>
                                            <th>Environment</th>
                                            <th>DB Instance</th>
                                            <th>Date And Time</th>
                                            <th>Logs</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($logs as $dbiRequest)
                                            <tr>
                                                <td>{{ $dbiRequest->id }}</td>
                                                <td>{{ $dbiRequest->dbiRequest->requestor->user_firstname}} {{ $dbiRequest->dbiRequest->requestor->user_lastname}}</td>
                                                <td>{{ $dbiRequest->execution_status }}</td>
                                                <td>{{ $dbiRequest->env }}</td>
                                                <td>{{ $dbiRequest->db_instance }}</td>
                                                <td>{{ $dbiRequest->created_at }}</td>
                                                
                                                <td>
                                                    
                                                    <a href="{{ route('dbi.showLogs', $dbiRequest->id) }}" class="btn btn-primary"  target="_blank">Logs</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Add pagination links -->
                            <div class="pagination">
                                {{ $logs->links() }}
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


    .card {
        margin-top: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .table th,
    .table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: #ed0929;
        color: white;
    }

    .table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .table tr:hover {
        background-color: #e6e6e6;
    }

    .table td:last-child {
        text-align: center;
    }

    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        border: 1px solid transparent;
        border-radius: 4px;
        text-decoration: none;
    }

    .btn-primary {
        color: #fff;
        background-color: #4CAF50;
        border-color: #4CAF50;
    }

    .btn-secondary {
        color: #333;
        background-color: #f2f2f2;
        border-color: #ccc;
    }

    .btn-primary:hover,
    .btn-secondary:hover {
        opacity: 0.8;
    }
</style>