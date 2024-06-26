<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('DB Instances') }}
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

                                                <div class="card-body">
                                                    <a href="{{ route('db-instances.create') }}" class="btn btn-primary mb-3">Create DB Instance</a>
                                                    <br><br>
                                                    @if (session('success'))
                                                        <div class="alert alert-success">
                                                            {{ session('success') }}
                                                        </div>
                                                    @endif

                                                    @if (session('error'))
                                                        <div class="alert alert-danger">
                                                            {{ session('error') }}
                                                        </div>
                                                    @endif

                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Prod</th>
                                                                <th>Preprod</th>
                                                                <th>Market</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($dbInstances as $dbInstance)
                                                                <tr>
                                                                    <td>{{ $dbInstance->prod }}</td>
                                                                    <td>{{ $dbInstance->preprod }}</td>
                                                                    <td>{{ $dbInstance->market->name }}</td>
                                                                    <td>
                                                                        <a href="{{ route('db-instances.show', $dbInstance->id) }}" class="btn btn-primary btn-sm">View</a>
                                                                        <a href="{{ route('db-instances.edit', $dbInstance->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                                                        <form action="{{ route('db-instances.destroy', $dbInstance->id) }}" method="POST" style="display: inline-block;">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this DB instance?')">Delete</button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Add pagination links -->
                                                <div class="pagination">
                                                    {{ $dbInstances->links() }}
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