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
                                        <button class="btn btn-primary mb-4" type="submit">
                                            <a href="{{ route('database-info.index') }}" class="btn-link">Back</a>
                                        </button>
                                        <div class="col-md-10">
                                            <div class="card-body">
                                                <h4 class="mb-4">Create Database Info</h4>
                                                <form action="{{ route('database-info.store') }}" method="POST" class="form">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="db_user_name" class="form-label">DB User Name</label>
                                                        <input type="text" name="db_user_name" id="db_user_name" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="db_user_password" class="form-label">DB User Password</label>
                                                        <input type="password" name="db_user_password" id="db_user_password" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="db_name" class="form-label">DB Name</label>
                                                        <input type="text" name="db_name" id="db_name" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Create</button>
                                                    </div>
                                                </form>
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
    .form {
        max-width: 500px;
        margin: 0 auto;
    }

    .form-label {
        font-weight: bold;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .btn-primary {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-link {
        color: #fff;
        text-decoration: none;
    }
</style>