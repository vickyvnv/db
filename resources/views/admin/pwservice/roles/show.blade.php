<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pwrole Details') }}
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
                                                <div class="card-header">Pwrole Details</div>
                                                <div class="card-body">
                                                    <a href="{{ route('pwroles.index') }}" class="btn btn-secondary mb-3">Back</a>

                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <th>Name</th>
                                                                <td>{{ $pwrole->pwr_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Description</th>
                                                                <td>{{ $pwrole->pwr_description }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Group</th>
                                                                <td>{{ $pwrole->pwc_group }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Type</th>
                                                                <td>{{ $pwrole->pwr_type }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <a href="{{ route('pwroles.edit', $pwrole) }}" class="btn btn-primary">Edit</a>
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