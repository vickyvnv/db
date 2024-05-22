<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Pwrole') }}
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
                                                <div class="card-header">Create Pwrole</div>
                                                <div class="card-body">
                                                    <a href="{{ route('pwroles.index') }}" class="btn btn-secondary mb-3">Back</a>

                                                    <!-- Display success or error messages if needed -->
                                                    @if (session('error'))
                                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                                    @endif

                                                    <form action="{{ route('pwroles.store') }}" method="POST">
                                                        @csrf
                                                        <div class="form-row">
                                                            <div class="form-group">
                                                                <label for="pwr_name">Name</label>
                                                                <input type="text" name="pwr_name" id="pwr_name" class="form-control" value="{{ old('pwr_name') }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="pwr_description">Description</label>
                                                                <textarea name="pwr_description" id="pwr_description" class="form-control">{{ old('pwr_description') }}</textarea>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Create</button>
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
    </div>
</x-app-layout>

<style>
    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-left: -15px;
        margin-right: -15px;
    }

    .form-group {
        flex: 1;
        padding-left: 15px;
        padding-right: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form-group textarea {
        resize: vertical;
    }

    .btn-primary {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
</style>