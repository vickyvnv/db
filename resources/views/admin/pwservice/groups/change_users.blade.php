<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Change Group Users') }}
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
                                            <div class="card-header bg-blue-500 text-white">Change Group Users</div>
                                            <div class="card-body">
                                                <a href="{{ route('pwgroups.index') }}" class="btn btn-secondary mb-4">
                                                    <i class="fas fa-arrow-left mr-2"></i>Back
                                                </a>
                                                <!-- Display success or error messages if needed -->
                                                <div class="mt-4">
                                                    <h4 class="text-lg font-semibold mb-2">Assigned Users</h4>
                                                    <ul class="list-none">
                                                        @forelse ($assignedUsers as $user)
                                                            <li class="flex items-center justify-between py-2 border-b border-gray-200">
                                                                <div class="flex items-center">
                                                                <span>{{ $user->id}}</span>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <span>{{ $user->user_firstname }} {{ $user->user_lastname }}</span>
                                                                </div>
                                                                <form action="{{ route('pwgroups.removeUser', [$pwgroup, $user]) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @empty
                                                            <li class="py-2">No assigned users found.</li>
                                                        @endforelse
                                                    </ul>
                                                </div>

                                                <div class="mt-8">
                                                    <h4 class="text-lg font-semibold mb-2">Available Users</h4>
                                                    <ul class="list-none">
                                                        @forelse ($availableUsers as $user)
                                                            <li class="flex items-center justify-between py-2 border-b border-gray-200">
                                                                <div class="flex items-center">
                                                                <span>{{ $user->id}}</span>&nbsp;&nbsp;&nbsp;&nbsp;                                                                    <span>{{ $user->user_firstname }} {{ $user->user_lastname }}</span>
                                                                </div>
                                                                <form action="{{ route('pwgroups.addUser', [$pwgroup, $user]) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @empty
                                                            <li class="py-2">No available users found.</li>
                                                        @endforelse
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