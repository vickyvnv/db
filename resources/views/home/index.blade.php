<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('DBI Home') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar -->
        <!-- Sidebar -->
        @include('partials.dbi-sidebar')

        <!-- Main Content -->
        <div class="w-3/4">
            <div class="content">
                <!-- Your content here -->
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <button class="btn btn-primary" type="submit"><a href="{{ route('dbi.index') }}" class="btn btn-primary">Back</a></button>
                                        <div class="col-md-10">
                                            
                                            <div class="card-body d-flex justify-content-center">
                                                <div class="card-header">DBI Request Details</div>

                                                <div class="card-body">
                                                <a href="{{ route('dbi.index') }}" class="btn btn-secondary mb-3">Back</a>

                                                    <!-- Display success or error messages if needed -->
                                                    @if (session('status'))
                                                        <div class="alert alert-success" role="alert">
                                                            {{ session('status') }}
                                                        </div>
                                                    @endif
                                                    @if ($errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif

                                                    <!-- Display DBI request details -->
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
    .container {
        float: left; /* Added to align main content to left */
    }

    .card {
        margin-top: 20px;
    }
</style>
