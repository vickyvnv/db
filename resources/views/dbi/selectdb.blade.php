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
                                    <button class="btn btn-primary" type="submit"><a href="{{ route('dbi.edit', $dbiRequest->id) }}" class="btn btn-primary">Back</a></button>
                                    <div class="col-md-10">
                                        <div class="card-body d-flex justify-content-center">
                                            <form method="POST" action="{{ route('dbi.updateSelectDb', $dbiRequest->id) }}" class="custom-form">
                                                @csrf
                                                @method('PUT')

                                                <div class="form-row">
                                                    <!-- Market -->
                                                    <!-- Market -->
                                                    <div class="form-group">
                                                        <label for="sw_version">Market:</label>
                                                        <select name="sw_version" id="sw_version" class="form-control @error('sw_version') is-invalid @enderror">
                                                            <option value="">Select Market</option>
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
                                                <!-- Prod Instance -->
                                                <div class="mt-4">
                                                    <label for="prod_instance" class="block font-medium text-sm text-gray-700">Prod Instance</label>
                                                    <select id="prod-instance-container" name="prod_instance" class="form-control @error('prod_instance') is-invalid @enderror">
                                                        <option value="">Select Prod Instance</option>
                                                        @if($selectedProdInstance)
                                                            <option value="{{ $selectedProdInstance }}" selected>{{ $selectedProdInstance }}</option>
                                                        @endif
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
<script>
$(document).ready(function() {
    const swVersionSelect = $('#sw_version');
    const dbUserInput = $('#db-user-input');
    const dbUserInput1 = $('#db-user-input1');
    const prodInstanceSelect = $('#prod-instance-container');
    const testInstanceInput = $('#test-instance-container');
    const testInstanceInput1 = $('#test-instance-container1');

    function toggleFieldsState() {
        const isSwVersionEmpty = swVersionSelect.val() === '';
        const isDbUserEmpty = dbUserInput.val() === '';
        const isProdInstanceEmpty = prodInstanceSelect.val() === '';

        // Disable fields if they have a value
        swVersionSelect.prop('disabled', !isSwVersionEmpty);
        dbUserInput1.prop('disabled', !isDbUserEmpty);
        prodInstanceSelect.prop('disabled', !isProdInstanceEmpty);

        // Test instance is always disabled
        testInstanceInput1.prop('disabled', true);
    }

    // Initial state
    toggleFieldsState();

    swVersionSelect.change(function() {
        var swVersion = $(this).val();
        
        if (swVersion === '') {
            dbUserInput.val('');
            dbUserInput1.val('');
            prodInstanceSelect.html('<option value="">Select Prod Instance</option>');
            testInstanceInput.val('');
            testInstanceInput1.val('');
            toggleFieldsState();
            return;
        }

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
            console.log(data);
            if (data && data.dbuser && data.dbuser.length > 0) {
                dbUserInput.val(data.dbuser[0].db_user_name);
                dbUserInput1.val(data.dbuser[0].db_user_name);

                prodInstanceSelect.html('<option value="">Select Prod Instance</option>');
                data.marketDB.forEach(instance => {
                    prodInstanceSelect.append($('<option>', {
                        value: instance.prod,
                        text: instance.prod
                    }));
                });

                // Set the prod instance to the previously selected value if it exists
                if ('{{ $selectedProdInstance }}') {
                    prodInstanceSelect.val('{{ $selectedProdInstance }}');
                }

                if (data.marketDB.length > 0) {
                    const selectedProdInstance = prodInstanceSelect.val();
                    const selectedInstance = data.marketDB.find(instance => instance.prod === selectedProdInstance);
                    if (selectedInstance) {
                        testInstanceInput.val(selectedInstance.preprod);
                        testInstanceInput1.val(selectedInstance.preprod);
                    }
                }
            } else {
                dbUserInput.val('');
                dbUserInput1.val('');
                prodInstanceSelect.html('<option value="">Select Prod Instance</option>');
                testInstanceInput.val('');
                testInstanceInput1.val('');
                alert('No DB User found');
            }

            toggleFieldsState();
        })
        .catch(error => {
            console.error('Error fetching DB User:', error);
            dbUserInput.val('');
            dbUserInput1.val('');
            prodInstanceSelect.html('<option value="">Select Prod Instance</option>');
            testInstanceInput.val('');
            testInstanceInput1.val('');
            alert('Error fetching DB User');
            toggleFieldsState();
        });
    });

    prodInstanceSelect.change(function() {
        const selectedProd = $(this).val();
        if (selectedProd && window.data && window.data.marketDB) {
            const selectedInstance = window.data.marketDB.find(instance => instance.prod === selectedProd);
            if (selectedInstance) {
                testInstanceInput.val(selectedInstance.preprod);
                testInstanceInput1.val(selectedInstance.preprod);
            }
        } else {
            testInstanceInput.val('');
            testInstanceInput1.val('');
        }
        toggleFieldsState();
    });

    // Trigger change event on page load to set initial state
    swVersionSelect.trigger('change');
});
</script>
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
