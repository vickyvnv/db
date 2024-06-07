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
                                                        <!-- Market -->
                                                        <div class="form-group">
                                                            <label for="sw_version">Market:</label>
                                                            <select name="sw_version" id="sw_version" class="form-control @error('sw_version') is-invalid @enderror">
                                                                <option value="">Select Market {{$selectedMarket}}</option>
                                                                @foreach ($markets as $market)
                                                                    <option value="{{ $market->id }}" {{ $selectedMarket == $market->id ? 'selected' : '' }}>{{ $market->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('sw_version')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <!-- Reference DBI -->
                                                        <div class="form-group">
                                                            <div class="form-group" id="dbList">
                                                                <label for="db_user">DB User:</label>
                                                                <input type="type" id="db-user-input1" value="{{ $selectedDbUser }}" disabled>
                                                                <input type="hidden" id="db-user-input" name="db_user" value="{{ $selectedDbUser }}">
                                                            </div>
                                                            @error('db_user')
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <!-- User Roles -->
                                                    <div class="mt-4">
                                                        <label for="roles" class="block font-medium text-sm text-gray-700">Prod Instance</label>
                                                        <select id="prod-instance-container" name="prod_instance" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('prod_instance') is-invalid @enderror">
                                                            <option value="">Please select Prod Instance</option>
                                                            <!-- Populate options based on the selected market -->
                                                        </select>
                                                        @error('prod_instance')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mt-4">
                                                        <label for="roles" class="block font-medium text-sm text-gray-700">Test Instance</label>
                                                        <input type="type" id="test-instance-container1" value="{{ $selectedTestInstance }}" disabled>
                                                        <input type="hidden" id="test-instance-container" name="test_instance" value="{{ $selectedTestInstance }}">
                                                        <label></label>
                                                        @error('test_instance')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <!-- Source Code -->
                                                    <div class="form-group">
                                                        <label>Source Code:</label>
                                                        <textarea name="source_code" rows="4" class="form-control @error('source_code') is-invalid @enderror">{{ $sourceCode }}</textarea>
                                                        @error('source_code')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
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
<script>
$(document).ready(function() {
    // Trigger the change event on page load to fetch the DB user and populate the form fields
    $(document).ready(function() {
  $('#sw_version').trigger('change');
});

    $('#sw_version').change(function() {
        var swVersion = $(this).val();
        var dbUserSelect = $('#db_user');
        // Clear existing options
        dbUserSelect.empty();
        // Disable the db_user select if no sw_version is selected
        if (swVersion === '') {
            dbUserSelect.append('<option value="">Select DB User</option>');
            dbUserSelect.prop('disabled', true);
            return;
        }
        // Enable the db_user select
        dbUserSelect.prop('disabled', false);
        // Make a POST request to fetch the db_user based on sw_version
        fetch('/dbi-tool/dbi/get-db-user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                sw_version: swVersion
            })
        })
        .then(response => response.json())
        .then(data => {
            // Clear the db_user select options
            dbUserSelect.innerHTML = '';
            // Populate the db_user select options
            if (data) {
                const dbUserInput = document.getElementById('db-user-input');
                const dbUserInput1 = document.getElementById('db-user-input1');
                if (data) {
                    console.log(data);
                    dbUserInput.value = data.dbuser[0].db_user_name;
                    dbUserInput1.value = data.dbuser[0].db_user_name;
                    dbUserInput1.disabled = true;
                    dbUserInput.name = "db_user";
                    dbUserInput.type = "hidden";

                    if (data.error) {
                        console.error('Error fetching market data:', data.error);
                        // Display an error message to the user or handle the error as needed
                        alert(data.error);
                    } else {
                        // Get references to the prod_instance and test_instance select elements
                        const prodInstanceSelect = document.getElementById('prod-instance-container');
                        const testInstanceSelect = document.getElementById('test-instance-container');
                        const testInstanceSelect1 = document.getElementById('test-instance-container1');

                        // Clear existing options
                        prodInstanceSelect.innerHTML = '';
                        testInstanceSelect.innerHTML = '';
                        
                        const prodOption1 = document.createElement('option');
                        prodOption1.value = "";
                        prodOption1.text = "Please select prod instance";
                        prodInstanceSelect.add(prodOption1);
                        
                        testInstanceSelect.value = '';
                        
                        // Loop through the marketDB array
                        data.marketDB.forEach(instance => {
                            // Create options for prod_instance
                            const prodOption = document.createElement('option');
                            prodOption.value = instance.prod;
                            prodOption.text = instance.prod;
                            prodInstanceSelect.add(prodOption);

                            // Create options for test_instance
                            testInstanceSelect.value = instance.preprod;
                            testInstanceSelect1.value = instance.preprod;
                            //testInstanceSelect.value = instance.test_instance;
                        });

                        // Set the selected prod instance and test instance
                        prodInstanceSelect.value = '{{ $selectedProdInstance }}';
                        // testInstanceSelect.value = '{{ $selectedTestInstance }}';
                        // testInstanceSelect1.value = '{{ $selectedTestInstance }}';

                        // Trigger the change event on the prod_instance select element to update the test_instance
                        prodInstanceSelect.dispatchEvent(new Event('change'));
                    }
                } else {
                    dbUserInput.value = '';
                    alert('No DB User found');
                }
            } else {
                var option = document.createElement('option');
                option.value = '';
                option.text = 'No DB User found';
                dbUserSelect.add(option);
            }
        })
        .catch(error => {
            console.error('Error fetching DB User:', error);
            var option = document.createElement('option');
            option.value = '';
            option.text = 'Error fetching DB User';
            dbUserSelect.add(option);
        });
    });
});
</script>