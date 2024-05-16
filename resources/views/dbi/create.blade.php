<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('DBI') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('dbi.index') }}">DBI Home</a></li>
                <li><a href="#">Change Role</a></li>
                <li><a href="#">Search DBI</a></li>
                <li><a href="#">List My DBI</a></li>
                <li><a href="#">New DBI</a></li>
                <li><a href="#">Cleanup</a></li>
                <li><a href="#">Documentation</a></li>
                <!-- Add more sidebar links here -->
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">DBI Requests</div>

                        <div class="card-body">
                        <a href="{{ route('dbi.index') }}" class="btn btn-secondary mb-3">Back</a>
                        <form method="POST" action="{{ route('dbi.store') }}" class="custom-form">
                            @csrf

                            <!-- DBI Category -->
                            <div class="form-group">
                                <div class="input-group">
                                    <label>DBI Category:</label>
                                    @foreach ($categories as $category)
                                        &nbsp;&nbsp;<input type="radio" id="{{ $category->name }}" name="category" value="{{ $category->name }}"> 
                                        <label for="{{ $category->name }}">{{ $category->name }}</label>
                                    @endforeach
                                </div>
                                @error('category')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div class="form-group">
                                <label>Priority:</label>
                                <select name="priority_id" class="form-control">
                                    @foreach ($priorities as $priority)
                                        <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Market -->
                            <div class="form-group">
                                <label for="sw_version">Market:</label>
                                <select name="sw_version" class="form-control">
                                    @foreach ($markets as $market)
                                        <option value="{{ $market->id }}">{{ $market->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- DBI Type -->
                            <div class="form-group">
                                <label for="name">DBI Type:</label>
                                <select name="dbi_type" class="form-control">
                                    @foreach ($dbiTypes as $dbiType)
                                        <option value="{{ $dbiType->name }}">{{ $dbiType->name }}</option>
                                    @endforeach
                                </select>
                            </div>

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

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .sidebar {
        width: 250px; /* Adjust width as needed */
        height: 100%;
        background-color: #f4f4f4;
        padding: 20px;
        float: left; /* Added to align sidebar to left */
    }

    .sidebar-menu {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        margin-bottom: 10px;
    }

    .sidebar-menu li a {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #333;
        transition: background-color 0.3s;
    }

    .sidebar-menu li a:hover {
        background-color: #ddd;
    }

  
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

    .invalid-feedback {
        color:red;
    }
</style>
