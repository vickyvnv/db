<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('DBI') }}
        </h2>
    </x-slot>

    <div class="flex">
    <!-- Sidebar -->
    @include('partials.dbi-sidebar')

    <!-- Main Content -->
    <div class="w-3/4">
        <div class="content">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <button class="btn btn-primary" type="submit"><a href="{{ route('dbi.index') }}" class="btn btn-primary">Back</a></button>
                                    <div class="col-md-10">
                                        <div class="card-body d-flex justify-content-center">
                                            <div class="card-header">DBI Requests</div>
                                            <div class="card-body">
                                                <h1>Select Database</h1>
                                                <p>DBI ID: {{ $dbiRequest->id }}</p>
                                                <form method="POST" action="{{ route('dbi.updateSelectDb', $dbiRequest->id) }}" class="custom-form">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="form-row">
                                                        <!-- Reference DBI -->
                                                        <div class="form-group">
                                                            <div class="form-group" id="dbList">
                                                                <label for="db_user">DB User:</label>
                                                                <select name="db_user" class="form-control">
                                                                    @foreach ($dbList as $db)
                                                                    <option value="{{ $db->db_user_name }}">{{ $db->db_user_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @error('db_user')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <!-- Source Code -->
                                                    <div class="form-group">
                                                        <label>Source Code:</label>
                                                        <textarea name="source_code" rows="4" class="form-control @error('source_code') is-invalid @enderror"></textarea>
                                                        @error('source_code')
                                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-row">
                                                        <!-- DBI Type -->
                                                        <div class="form-group" id="dbList">
                                                            <label for="dbList">Prod Instance:</label>
                                                            <select name="db_instance" class="form-control">
                                                                @foreach ($dbList as $db)
                                                                    @php
                                                                        $dbNames = is_string($db->db_names) ? explode(',', $db->db_names) : $db->db_names;
                                                                    @endphp
                                                                    @foreach ($dbNames as $dbName)
                                                                        <option value="{{ $dbName }}">{{ $dbName }}</option>
                                                                    @endforeach
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Submit Button -->
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Next</button>
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
</div>
</x-app-layout>

<style>
    .custom-form {
        max-width: 800px;
        margin: 0 auto;
        padding: 30px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 1;
        margin-right: 20px;
    }

    .form-group:last-child {
        margin-right: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input[type="text"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    .form-group input[type="text"]:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .radio-option {
        display: inline-block;
        margin-right: 10px;
    }

    .radio-option input[type="radio"] {
        margin-right: 5px;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 14px;
    }
</style>