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
                                        <button class="btn btn-primary" type="submit"><a href="{{ route('dbi.index') }}" class="btn btn-primary">Back</a></button>
                                        <div class="col-md-10">
                                            <div class="card-body d-flex justify-content-center">
                                                <div class="card-header">DBI Requests</div>
                                                <div class="card-body">
                                                    <form method="POST" action="{{ route('dbi.store') }}" class="custom-form">
                                                        @csrf

                                                        <div class="form-row">
                                                            <!-- DBI Category -->
                                                            <div class="form-group">
                                                                <label>DBI Category:</label>
                                                                <div class="input-group">
                                                                    @foreach ($categories as $category)
                                                                    <div class="radio-option">
                                                                        <input type="radio" id="{{ $category->subname }}" name="category" value="{{ $category->subname }}">
                                                                        <label for="{{ $category->name }}">{{ $category->name }}</label>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                                @error('category')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                            <!-- DBI Type -->
                                                            <div class="form-group" id="dbiTypeGroup">
                                                                <label for="name">DBI Type:</label>
                                                                <select name="dbi_type" class="form-control">
                                                                    @foreach ($dbiTypes as $dbiType)
                                                                    <option value="{{ $dbiType->subname }}">{{ $dbiType->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Priority -->
                                                            <div class="form-group">
                                                                <label>Priority:</label>
                                                                <select name="priority_id" class="form-control">
                                                                    @foreach ($priorities as $priority)
                                                                    <option value="{{ $priority->name }}">{{ $priority->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('priority_id')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <!-- TT ID -->
                                                            <div class="form-group">
                                                                <label>TT Number:</label>
                                                                <input type="text" name="tt_id" class="form-control @error('tt_id') is-invalid @enderror">
                                                                @error('tt_id')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                            <!-- Serf CR ID -->
                                                            <div class="form-group">
                                                                <label>Serf/CR:</label>
                                                                <input type="text" name="serf_cr_id" class="form-control @error('serf_cr_id') is-invalid @enderror">
                                                                @error('serf_cr_id')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                            <!-- Reference DBI -->
                                                            <div class="form-group">
                                                                <label>Reference DBI:</label>
                                                                <input type="text" name="reference_dbi" class="form-control @error('reference_dbi') is-invalid @enderror">
                                                                @error('reference_dbi')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <!-- Brief Description -->
                                                            <div class="form-group">
                                                                <label>Brief Description:</label>
                                                                <textarea name="brief_desc" rows="4" class="form-control @error('brief_desc') is-invalid @enderror" maxlength="200"></textarea>
                                                                @error('brief_desc')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                            <!-- Problem Description -->
                                                            <div class="form-group">
                                                                <label>Problem Description:</label>
                                                                <textarea name="problem_desc" rows="4" class="form-control @error('problem_desc') is-invalid @enderror" maxlength="1000"></textarea>
                                                                @error('problem_desc')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                            <!-- Business Impact -->
                                                            <div class="form-group">
                                                                <label>Business Impact:</label>
                                                                <textarea name="business_impact" rows="4" class="form-control @error('business_impact') is-invalid @enderror" maxlength="1000"></textarea>
                                                                @error('business_impact')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Submit Button -->
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary">Submit</button>
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
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .form-group {
        flex-basis: calc(33.33% - 10px);
        margin-bottom: 20px;
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

    @media (max-width: 768px) {
        .form-group {
            flex-basis: 100%;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var categoryRadios = document.querySelectorAll('input[name="category"]');
        var dbiTypeGroup = document.getElementById('dbiTypeGroup');

        function toggleDbiTypeVisibility() {
            var selectedCategory = document.querySelector('input[name="category"]:checked');
            if (selectedCategory && selectedCategory.value === 'SP') {
                dbiTypeGroup.style.display = 'none';
            } else {
                dbiTypeGroup.style.display = 'block';
            }
        }

        categoryRadios.forEach(function(radio) {
            radio.addEventListener('change', toggleDbiTypeVisibility);
        });

        toggleDbiTypeVisibility();
    });
</script>