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

    .table thead th {
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
