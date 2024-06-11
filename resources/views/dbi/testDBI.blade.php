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
        <div class="w-3/4 p-6">
            <div class="content">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <a href="{{ route('dbi.createsqlfile', $dbiRequest->id) }}" class="btn btn-primary mb-4">Back</a>
                                        <br><br><br>
                                        <div class="col-md-10">
                                            <div class="card">
                                                <div class="card-header bg-primary text-white">Test DBI</div>
                                                <div class="card-body">
                                                    <p class="mb-4">DBI ID: {{ $dbiRequest->id }}</p>
                                                    <form action="{{ route('dbi.testDbi', $dbiRequest->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="db_user" value="{{ $dbiRequest->db_user }}">
                                                        <input type="hidden" name="db_instance" value="{{ $dbiRequest->db_instance }}">
                                                        <input type="hidden" name="source_code" value="{{ $dbiRequest->source_code }}">
                                                        <input type="hidden" name="prodTest" value="No">
                                                        <button type="submit" class="btn btn-primary">Test DBI</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <textarea class="form-control" rows="10">{{ $dbiRequest->sql_logs_info }}</textarea>
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