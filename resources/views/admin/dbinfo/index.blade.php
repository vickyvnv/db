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
                                        <div class="col-md-10">
                                            <div class="card">
                                                <div class="card-header bg-blue-500 text-white">
                                                    Database Info List
                                                </div>
                                                
                                                <div class="card-body">
                                                    <!-- Display success or error messages if needed -->
                                                    @if ($errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif

                                                    @if (session('success'))
                                                        <div class="alert alert-success">
                                                            {{ session('success') }}
                                                        </div>
                                                    @endif

                                                    <a href="{{ route('database-info.create') }}" class="btn btn-sm btn-primary">Create Database Info</a></br></br></br>

                                                    <table class="table">
                                                        <thead class="bg-blue-100">
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">DB User Name</th>
                                                                <th scope="col">DB Name</th>
                                                                <!-- <th scope="col">Actions</th> -->
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($databaseInfos as $databaseInfo)
                                                                <tr>
                                                                    <th scope="row">{{ $databaseInfo->id }}</th>
                                                                    <td>{{ $databaseInfo->db_user_name }}</td>
                                                                    <td>{{ $databaseInfo->db_name }}</td>
                                                                    <!-- <td>
                                                                        <div class="btn-group" role="group">
                                                                            <a href="{{ route('database-info.edit', $databaseInfo->id) }}" class="btn btn-sm btn-primary">Edit</a></br>
                                                                            <form action="{{ route('database-info.destroy', $databaseInfo->id) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                </br>
                                                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this database info?')">Delete</button>
                                                                            </form>
                                                                        </div>
                                                                    </td> -->
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