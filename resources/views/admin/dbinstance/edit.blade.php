<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit DB Instance') }}
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
                                        <button class="btn btn-primary mb-4" type="submit">
                                            <a href="{{ route('db-instances.index') }}" class="btn-link">Back</a>
                                        </button>
                                        <div class="col-md-10">
                                            <div class="card-body">
                                                <h4 class="mb-4">Edit DB Instance</h4>

                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                <form action="{{ route('db-instances.update', $dbInstance->id) }}" method="POST" class="form">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="prod" class="form-label">Prod</label>
                                                        <input type="text" name="prod" id="prod" class="form-control" value="{{ $dbInstance->prod }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="preprod" class="form-label">Preprod</label>
                                                        <input type="text" name="preprod" id="preprod" class="form-control" value="{{ $dbInstance->preprod }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="market_id" class="form-label">Market</label>
                                                        <select name="market_id" id="market_id" class="form-control" required>
                                                            <option value="">Select Market</option>
                                                            @foreach ($markets as $market)
                                                                <option value="{{ $market->id }}" {{ $dbInstance->market_id == $market->id ? 'selected' : '' }}>{{ $market->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
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
</x-app-layout>
<style>
    .form {
        max-width: 500px;
        margin: 0 auto;
    }

    .form-label {
        font-weight: bold;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .btn-primary {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-link {
        color: #fff;
        text-decoration: none;
    }
</style>