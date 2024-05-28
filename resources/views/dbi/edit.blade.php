<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit DBI Request') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar -->
        @include('partials.dbi-sidebar')

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
                                            <div class="card">
                                                <div class="card-header">Edit DBI Request</div>

                                                <div class="card-body d-flex justify-content-center">
                                                    <form method="POST" action="{{ route('dbi.update', $dbiRequest->id) }}" class="custom-form">
                                                        @csrf
                                                        @method('PUT') <!-- This is required for Laravel to recognize the update request -->

                                                        <!-- Display Validation Errors -->
                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">
                                                                <ul>
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif

                                                        <!-- DBI Category -->
                                                        <div class="form-group">
                                                            <label>DBI Category:</label>
                                                            <!-- Populate the checked radio button based on the existing data -->
                                                            <div class="input-group">
                                                                <input type="radio" id="sql" name="category" value="SQL" {{ $dbiRequest->category === 'SQL' ? 'checked' : '' }}>
                                                                <label for="sql">SQL</label>
                                                                <input type="radio" id="external" name="category" value="External" {{ $dbiRequest->category === 'External' ? 'checked' : '' }}>
                                                                <label for="external">External</label>
                                                                <input type="radio" id="stored-procedure" name="category" value="Stored Procedure" {{ $dbiRequest->category === 'Stored Procedure' ? 'checked' : '' }}>
                                                                <label for="stored-procedure">Stored Procedure</label>
                                                            </div>
                                                        </div>

                                                        <!-- Priority -->
                                                        <div class="form-group">
                                                            <label>Priority:</label>
                                                            <!-- Populate the selected option based on the existing data -->
                                                            <select name="priority_id" class="form-control">
                                                                <option value="Normal" {{ $dbiRequest->priority_id === 'Normal' ? 'selected' : '' }}>Normal</option>
                                                                <option value="Emergency" {{ $dbiRequest->priority_id === 'Emergency' ? 'selected' : '' }}>Emergency</option>
                                                                <option value="Critical" {{ $dbiRequest->priority_id === 'Critical' ? 'selected' : '' }}>Critical</option>
                                                            </select>
                                                        </div>

                                                        <!-- Other form fields, populated with existing data -->
                                                        <!-- Market -->
                                                        <div class="form-group">
                                                            <label>Market:</label>
                                                            <select name="sw_version" class="form-control">
                                                                <option value="Credit" {{ $dbiRequest->sw_version === 'Credit' ? 'selected' : '' }}>Credit</option>
                                                                <option value="Debit" {{ $dbiRequest->sw_version === 'Debit' ? 'selected' : '' }}>Debit</option>
                                                                <option value="FN" {{ $dbiRequest->sw_version === 'FN' ? 'selected' : '' }}>FN</option>
                                                                <option value="NK" {{ $dbiRequest->sw_version === 'NK' ? 'selected' : '' }}>NK</option>
                                                            </select>
                                                        </div>

                                                        <!-- DBI Type -->
                                                        <div class="form-group">
                                                            <label>DBI Type:</label>
                                                            <select name="dbi_type" class="form-control">
                                                                <option value="OT" {{ $dbiRequest->dbi_type === 'OT' ? 'selected' : '' }}>One Time</option>
                                                                <option value="RE" {{ $dbiRequest->dbi_type === 'RE' ? 'selected' : '' }}>Recurring</option>
                                                                <option value="TP" {{ $dbiRequest->dbi_type === 'TP' ? 'selected' : '' }}>Template</option>
                                                            </select>
                                                        </div>

                                                        <!-- TT ID -->
                                                        <div class="form-group">
                                                            <label>TT Number:</label>
                                                            <input type="text" name="tt_id" class="form-control" value="{{ $dbiRequest->tt_id }}">
                                                        </div>

                                                        <!-- Serf CR ID -->
                                                        <div class="form-group">
                                                            <label>Serf/CR:</label>
                                                            <input type="text" name="serf_cr_id" class="form-control" value="{{ $dbiRequest->serf_cr_id }}">
                                                        </div>

                                                        <!-- Reference DBI -->
                                                        <div class="form-group">
                                                            <label>Reference DBI:</label>
                                                            <input type="text" name="reference_dbi" class="form-control" value="{{ $dbiRequest->reference_dbi }}">
                                                        </div>

                                                        <!-- Brief Description -->
                                                        <div class="form-group">
                                                            <label>Brief Description:</label>
                                                            <textarea name="brief_desc" rows="4" class="form-control" maxlength="200">{{ $dbiRequest->brief_desc }}</textarea>
                                                        </div>

                                                        <!-- Problem Description -->
                                                        <div class="form-group">
                                                            <label>Problem Description:</label>
                                                            <textarea name="problem_desc" rows="4" class="form-control" maxlength="1000">{{ $dbiRequest->problem_desc }}</textarea>
                                                        </div>

                                                        <!-- Business Impact -->
                                                        <div class="form-group">
                                                            <label>Business Impact:</label>
                                                            <textarea name="business_impact" rows="4" class="form-control" maxlength="1000">{{ $dbiRequest->business_impact }}</textarea>
                                                        </div>

                                                        <!-- Submit Button -->
                                                        <button type="submit" class="btn btn-primary">Update</button>
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
        <!-- Main Content -->
        
    </div>
</x-app-layout>

<style>
    
    .card {
        margin-top: 20px;
    }

    .custom-form .form-group {
        margin-bottom: 20px;
    }

    .custom-form label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    .custom-form input[type="radio"],
    .custom-form input[type="checkbox"] {
        margin-right: 10px;
    }

    .custom-form .input-group {
        display: flex;
        align-items: center;
    }

    .custom-form select,
    .custom-form input[type="text"],
    .custom-form textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    .custom-form select:focus,
    .custom-form input[type="text"]:focus,
    .custom-form textarea:focus {
        outline: none;
        border-color: #007bff;
    }

    .custom-form textarea {
        resize: vertical;
    }

    .custom-form button {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .custom-form button:hover {
        background-color: #0056b3;
    }
</style>
