<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('DBI Requests') }}
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
                                <button class="btn btn-primary" type="submit"><a href="{{ route('dbi.selectdb', $dbiRequest->id) }}" class="btn btn-primary">Back</a></button>

                                    <div class="col-md-10">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3>SQL File</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-4">
                                                    <button class="btn btn-secondary" type="submit">
                                                        <a href="{{ route('dbi.selectdb', $dbiRequest->id) }}" class="btn-link">Back</a>
                                                    </button>
                                                </div>
                                                <div class="mb-4">
                                                    <p class="lead">DBI ID: {{ $dbiRequest->id }}</p>
                                                </div>
                                                @php
                                                    $sqlFilePath = 'source_code_files/' . $dbiRequest->sql_file_path;
                                                @endphp
                                                @if (Storage::disk('public')->exists($sqlFilePath))
                                                    <div class="mb-4">
                                                        <a href="{{ asset('storage/' . $sqlFilePath) }}" target="_blank" class="btn btn-primary">{{ $dbiRequest->sql_file_path }}</a>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">SQL Query</label>
                                                        <textarea name="query" rows="8" class="form-control" maxlength="2000" disabled>{{ $dbiRequest->source_code ?? '' }}</textarea>
                                                    </div>
                                                @else
                                                    <p class="text-danger">SQL file not found.</p>
                                                @endif
                                                <div class="mt-4">
                                                    <form action="{{ route('dbi.testDBI', $dbiRequest->id) }}" method="GET">
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
    </div>
</x-app-layout>
<style>
    .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    text-decoration: none;
}

.btn-primary:hover {
    background-color: #0069d9;
    border-color: #0062cc;
}

.card {
    border: 1px solid #ccc;
    border-radius: 0.25rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card-header {
    padding: 1rem;
    border-bottom: 1px solid #ccc;
}

.card-body {
    padding: 1.5rem;
}

textarea.form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
}
.card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

.card-header {
    background-color: #f8f9fa;
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
}

.card-header h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    color: #333;
}

.card-body {
    padding: 30px;
}

.btn-link {
    color: #fff;
    text-decoration: none;
}

.btn-link:hover {
    color: #fff;
    text-decoration: none;
}

.lead {
    font-size: 18px;
    font-weight: 500;
    color: #555;
}

.font-weight-bold {
    font-weight: 600;
}

.text-danger {
    color: #dc3545;
}

.btn-lg {
    padding: 12px 24px;
    font-size: 18px;
    border-radius: 6px;
}
</style>