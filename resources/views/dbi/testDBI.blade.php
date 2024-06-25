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
                                                <div class="card-header bg-primary text-white">Pre Production Run</div>
                                                <div class="card-body">
                                                    <p class="mb-4">DBI ID: {{ $dbiRequest->id }}</p>
                                                    <form action="{{ route('dbi.testDbi', $dbiRequest->id) }}" method="POST" id="testDbiForm">
                                                        @csrf
                                                        <input type="hidden" name="db_user" value="{{ $dbiRequest->db_user }}">
                                                        <input type="hidden" name="db_instance" value="{{ $dbiRequest->db_instance }}">
                                                        <input type="hidden" name="source_code" value="{{ $dbiRequest->source_code }}">
                                                        <input type="hidden" name="prodTest" value="No">
                                                        <button type="submit" class="btn btn-primary" id="testDbiBtn">Test DBI</button>
                                                    </form>
                                                    <!-- Loader -->
                                                    <div id="loader" class="loader" style="display: none;">
                                                        <div class="spinner"></div>
                                                        <p>Processing... Please wait.</p>
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
.loader {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 9999;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('testDbiForm');
    const loader = document.getElementById('loader');

    form.addEventListener('submit', function(e) {
        // Show the loader
        loader.style.display = 'flex';

        // Disable the submit button to prevent multiple submissions
        document.getElementById('testDbiBtn').disabled = true;
    });
});
</script>