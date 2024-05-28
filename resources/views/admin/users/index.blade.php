<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administration') }}
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
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h2 class="text-lg font-semibold mb-4">User List</h2>

                            <!-- Search Form -->
                            <form action="{{ route('users.index') }}" method="GET">
                                <div class="form-row">
                                    <!-- First Row -->
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" id="username" value="{{ request()->input('username') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" id="phone" value="{{ request()->input('phone') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="id">ID</label>
                                        <input type="text" name="id" id="id" value="{{ request()->input('id') }}">
                                    </div>
                                    <!-- Second Row -->
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" id="email" value="{{ request()->input('email') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="company">Company</label>
                                        <input type="text" name="company" id="company" value="{{ request()->input('company') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="department">Department</label>
                                        <input type="text" name="department" id="department" value="{{ request()->input('department') }}">
                                    </div>
                                    <!-- Third Row -->
                                    <div class="form-group">
                                        <label for="position">Position</label>
                                        <input type="text" name="position" id="position" value="{{ request()->input('position') }}">
                                    </div>
                                </div>
                                <button type="submit">Search</button>
                            </form>
                            </br>
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Display User List Table -->
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <th scope="row">{{ $user->id }}</th>
                                            <td>{{ $user->user_firstname }} {{ $user->user_lastname }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <!-- Action buttons -->
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary edit-button">Edit</a>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination Links -->
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



<style>
     .edit-button {
        color: inherit;
    text-decoration: inherit;
    padding: 12px 20px;
    font-size: 0.875rem;
    color: #ffffff;
    background-color: Green;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;

    }
 /* Custom styles for the search form */
.form-container {
    max-width: 800px; /* Adjust the maximum width as needed */
    margin: 0 auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-container h2 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333333;
    margin-bottom: 20px;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.form-group {
    flex-basis: calc(33.33% - 10px); /* Adjust based on the number of columns */
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    color: #666666;
    margin-bottom: 8px;
}

.form-group input[type="text"] {
    width: 100%;
    padding: 10px;
    font-size: 0.875rem;
    border: 1px solid #cccccc;
    border-radius: 4px;
    transition: border-color 0.3s ease-in-out;
}

.form-group input[type="text"]:focus {
    outline: none;
    border-color: #007bff;
}

button {
    padding: 12px 20px;
    font-size: 0.875rem;
    color: #ffffff;
    background-color: #007bff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}

button:hover {
    background-color: #0056b3;
}


    .card {
        margin-top: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .table th,
    .table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table thead th {
        background-color: #ed0929;
        color: white;
    }

    .table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .table tr:hover {
        background-color: #e6e6e6;
    }

    .table td:last-child {
        text-align: center;
    }

    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        border: 1px solid transparent;
        border-radius: 4px;
        text-decoration: none;
    }

    .btn-primary {
        color: #fff;
        background-color: #4CAF50;
        border-color: #4CAF50;
    }

    .btn-secondary {
        color: #333;
        background-color: #f2f2f2;
        border-color: #ccc;
    }

    .btn-primary:hover,
    .btn-secondary:hover {
        opacity: 0.8;
    }
</style>
