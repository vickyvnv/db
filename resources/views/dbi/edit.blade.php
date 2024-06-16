<x-app-layout>
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
                                                <div class="card-body">
                                                    <form method="POST" action="{{ route('dbi.update', $dbiRequest->id) }}" class="custom-form">
                                                        @csrf
                                                        @method('PUT') 

                                                        <div class="form-row">
                                                            <!-- DBI Category -->
                                                            <div class="form-group">
                                                                <label>DBI Category:</label>
                                                                <div class="input-group">
                                                                    @foreach ($categories as $category)
                                                                    <div class="radio-option">
                                                                        <input type="radio" id="{{ $category->subname }}" name="category" value="{{ $category->subname }}" {{ $dbiRequest->category === $category->subname ? 'checked' : '' }}>
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
                                                                    <option value="{{ $dbiType->subname }}" {{ $dbiRequest->dbi_type === $dbiType->subname ? 'selected' : '' }}>{{ $dbiType->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Priority -->
                                                            <div class="form-group">
                                                                <label>Priority:</label>
                                                                <select name="priority_id" class="form-control">
                                                                    @foreach ($priorities as $priority)
                                                                    <option value="{{ $priority->name }}" {{ $dbiRequest->priority_id === $priority->name ? 'selected' : '' }}>{{ $priority->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('priority_id')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                            <!-- TT ID -->
                                                            <div class="form-group">
                                                                <label>TT Number:</label>
                                                                <input type="text" name="tt_id" class="form-control @error('tt_id') is-invalid @enderror" value="{{ $dbiRequest->tt_id }}">
                                                                @error('tt_id')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                            <!-- Serf CR ID -->
                                                            <div class="form-group">
                                                                <label>Serf/CR:</label>
                                                                <input type="text" name="serf_cr_id" class="form-control @error('serf_cr_id') is-invalid @enderror" value="{{ $dbiRequest->serf_cr_id }}">
                                                                @error('serf_cr_id')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                            <!-- Reference DBI -->
                                                            <div class="form-group">
                                                                <label>Reference DBI:</label>
                                                                <input type="text" name="reference_dbi" class="form-control @error('reference_dbi') is-invalid @enderror" value="{{ $dbiRequest->reference_dbi }}">
                                                                @error('reference_dbi')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <!-- Brief Description -->
                                                            <div class="form-group">
                                                                <label>Brief Description:</label>
                                                                <textarea name="brief_desc" rows="4" class="form-control @error('brief_desc') is-invalid @enderror" maxlength="200">{{ $dbiRequest->brief_desc }}</textarea>
                                                                @error('brief_desc')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                            <!-- Problem Description -->
                                                            <div class="form-group">
                                                                <label>Problem Description:</label>
                                                                <textarea name="problem_desc" rows="4" class="form-control @error('problem_desc') is-invalid @enderror" maxlength="1000">{{ $dbiRequest->problem_desc }}</textarea>
                                                                @error('problem_desc')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                            <!-- Business Impact -->
                                                            <div class="form-group">
                                                                <label>Business Impact:</label>
                                                                <textarea name="business_impact" rows="4" class="form-control @error('business_impact') is-invalid @enderror" maxlength="1000">{{ $dbiRequest->business_impact }}</textarea>
                                                                @error('business_impact')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Submit Button -->
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary">Update</button>
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

    .form-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .form-group {
        flex-basis: calc(33.33% - 10px);
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .form-group {
            flex-basis: 100%;
        }
    }
</style>