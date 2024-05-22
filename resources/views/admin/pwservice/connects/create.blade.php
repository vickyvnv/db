<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Pwconnect') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar -->
        @include('partials.admin-sidebar')

        <!-- Main Content -->
        <div class="w-3/4">
            <div class="content">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-md-10">
                                            <div class="card-body d-flex justify-content-center">
                                                <div class="card-header">Create Pwconnect</div>
                                                <div class="card-body">
                                                    <a href="{{ route('pwconnects.index') }}" class="btn btn-secondary mb-3">Back</a>

                                                    <!-- Display success or error messages if needed -->
                                                    @if (session('error'))
                                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                                    @endif

                                                    @if ($errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif

                                                    <form action="{{ route('pwconnects.store') }}" method="POST">
                                                        @csrf
                                                        <div class="form-row">
                                                            <div class="form-group">
                                                                <label for="PWC_NAME">Name</label>
                                                                <input type="text" name="PWC_NAME" id="PWC_NAME" class="form-control" value="{{ old('PWC_NAME') }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="PWC_USER">User</label>
                                                                <input type="text" name="PWC_USER" id="PWC_USER" class="form-control" value="{{ old('PWC_USER') }}" required>
                                                            </div>
                                                        </div></br>
                                                        <div class="form-row">
                                                            <div class="form-group">
                                                                <label for="PWC_PW">Password</label>
                                                                <input type="password" name="PWC_PW" id="PWC_PW" class="form-control" required>
                                                            </div>
                                                            
                                                        </div></br>
                                                        <div class="form-row">
                                                            <div class="form-group">
                                                                <label for="PWC_CAT">Category</label>
                                                                <select name="PWC_CAT" id="PWC_CAT" class="form-control" required>
                                                                    <option value="UX">Unix</option>
                                                                    <option value="ORA">Oracle</option>
                                                                    <option value="TRD">Terradata</option>
                                                                    <option value="CSD">Casendra</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="PWC_CHANGE_TYP">Change Type</label>
                                                                <select name="PWC_CHANGE_TYP" id="PWC_CHANGE_TYP" class="form-control" required>
                                                                    <option value="R">Repository Only</option>
                                                                    <option value="D">Database and Repository</option>
                                                                </select>
                                                            </div>
                                                        </div></br>
                                                        <div class="form-row">
                                                            <div class="form-group">
                                                                <label for="PWC_GROUP">Password Group</label>
                                                                <select name="PWC_GROUP" id="PWC_GROUP" class="form-control">
                                                                    <option value="" disabled>Select Password Group</option>
                                                                    @foreach ($pwGroups as $pwGroup)
                                                                        <option value="{{ $pwGroup->name }}">{{ $pwGroup->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            
                                                        </div></br>
                                                        <div class="form-row">
                                                            <div class="form-group">
                                                                <label for="PWC_CHANGE_COND">Change Condition</label>
                                                                <input type="text" name="PWC_CHANGE_COND" id="PWC_CHANGE_COND" class="form-control" value="{{ old('PWC_CHANGE_COND') }}">
                                                            </div>
                                                        </div>
                                                        </br>
                                                        <button type="submit" class="btn btn-primary">Create</button>
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
    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-left: -15px;
        margin-right: -15px;
    }

    .form-group {
        flex: 1;
        padding-left: 15px;
        padding-right: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .btn-primary {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
</style>