    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Groups') }}
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
                                            <div class="col-md-10">
                                                <div class="card-body d-flex justify-content-center">
                                                    <div class="card-header">Groups</div>
                                                    <div class="card-body">
                                                        <a href="{{ route('pwgroups.create') }}" class="btn btn-primary mb-3">Create Group</a>
                                                        <!-- Display success or error messages if needed -->
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Description</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($pwgroups as $group)
                                                                    <tr>
                                                                        <td>{{ $group->name }}</td>
                                                                        <td>{{ $group->description }}</td>
                                                                        <td>
                                                                            <a href="{{ route('pwgroups.show', $group) }}" class="btn btn-info btn-sm">View</a>
                                                                            <a href="{{ route('pwgroups.edit', $group) }}" class="btn btn-primary btn-sm">Edit</a>
                                                                            <a href="{{ route('pwgroups.changeUsers', $group) }}" class="btn btn-warning btn-sm">To Changed By</a>
                                                                            <form action="{{ route('pwgroups.destroy', $group) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this group?')">Delete</button>
                                                                            </form>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
    <style>
    
        .table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .table th,
        .table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }

        .table td {
            background-color: #fff;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

    </style>