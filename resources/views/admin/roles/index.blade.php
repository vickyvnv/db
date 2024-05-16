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
                                                    Roles List
                                                </div>
                                                
                                                <div class="card">
                                                    <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">Create Roles</a></br></br></br>

                                                    <div class="card-body">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ __('Name') }}</th>
                                                                    <th>{{ __('Rights') }}</th>
                                                                    <th>{{ __('Actions') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($roles as $role)
                                                                    <tr>
                                                                        <td>{{ $role->name }}</td>
                                                                        <td>
                                                                            @isset($role->rights)
                                                                                @foreach($role->rights as $right)
                                                                                    {{ $right->name }},
                                                                                @endforeach
                                                                            @else
                                                                                {{ __('No rights assigned.') }}
                                                                            @endisset
                                                                        </td>
                                                                        <td>
                                                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                                                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this role?') }}')">{{ __('Delete') }}</button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="3">{{ __('No roles found.') }}</td>
                                                                    </tr>
                                                                @endforelse
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
