<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pwconnect Users') }}
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
                                                <div class="card-header">Pwconnect Users</div>
                                                <div class="card-body">
                                                    <a href="{{ route('pwconnects.index') }}" class="btn btn-secondary mb-3">Back</a>

                                                    <div class="mt-4">
                                                        <h4>Assigned Users</h4>
                                                        <ul>
                                                            @foreach ($assignedUsers as $user)
                                                                <li>
                                                                    {{ $user->name }}
                                                                    <form action="{{ route('pwconnects.removeUser', [$pwconnect, $user]) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                                                    </form>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>

                                                    <div class="mt-4">
                                                        <h4>Available Users</h4>
                                                        <ul>
                                                            @foreach ($availableUsers as $user)
                                                                <li>
                                                                    {{ $user->name }}
                                                                    <form action="{{ route('pwconnects.assignUser', [$pwconnect, $user]) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-primary btn-sm">Add</button>
                                                                    </form>
                                                                </li>
                                                            @endforeach
                                                        </ul>
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