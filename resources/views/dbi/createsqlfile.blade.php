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
                                                <div class="card-header">SQL File</div>
                                                <div class="card-body">
                                                    <p>DBI ID: {{ $dbiRequest->id }}</p>

                                                    @php
                                                        $sqlFilePath = 'source_code_files/' . $dbiRequest->sql_file_path;
                                                    @endphp

                                                    @if (Storage::exists($sqlFilePath))
                                                        <a href="{{ Storage::url($sqlFilePath) }}" target="_blank">{{ $dbiRequest->sql_file_path }}</a>
                                                        <div class="form-group">
                                                            <label>SQL Query</label>
                                                            <textarea name="query" rows="4" class="form-control" maxlength="2000" disabled>{{ $dbiRequest->source_code ?? '' }}</textarea>
                                                        </div>

                                                    @else
                                                        <p>SQL file not found.</p>
                                                    @endif

                                                    <!-- Submit Button -->
                                                    <form action="{{ route('dbi.additionalinfo', $dbiRequest->id) }}" method="GET">
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
</style>