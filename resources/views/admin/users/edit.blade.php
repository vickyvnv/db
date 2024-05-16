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
                <!-- Your content here -->
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <button class="btn btn-primary" type="submit"><a href="{{ route('dbi.index') }}" class="btn btn-primary">Back</a></button>
                                        <div class="col-md-10">

                                            <div class="card-body d-flex justify-content-center">
                                                <div class="card-header">Edit User Details</div>

                                                <div class="card-body">
                                                    <a href="{{ route('dbi.index') }}" class="btn btn-secondary mb-3">Back</a>

                                                    <!-- Display success or error messages if needed -->
                                                    @if(session('success'))
                                                        <div class="alert alert-success">
                                                            {{ session('success') }}
                                                        </div>
                                                    @endif

                                                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                                                        @csrf
                                                        @method('PUT')

                                                        <!-- First Name -->
                                                        <div class="mt-4">
                                                            <label for="user_firstname" class="block font-medium text-sm text-gray-700">First Name</label>
                                                            <input id="user_firstname" type="text" name="user_firstname" value="{{ $user->user_firstname }}" required autofocus
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('user_firstname')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="mt-4">
                                                            <label for="user_lastname" class="block font-medium text-sm text-gray-700">Last Name</label>
                                                            <input id="user_lastname" type="text" name="user_lastname" value="{{ $user->user_lastname }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('user_lastname')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div> 

                                                        <!-- User Department -->
                                                        <div class="mt-4">
                                                            <label for="user_department" class="block font-medium text-sm text-gray-700">User Department</label>
                                                            <input id="user_department" type="text" name="user_department" value="{{ $user->user_department }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('user_department')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- User Company -->
                                                        <div class="mt-4">
                                                            <label for="company" class="block font-medium text-sm text-gray-700">Company</label>
                                                            <input id="company" type="text" name="company" value="{{ $user->company }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('company')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- User Position -->
                                                        <div class="mt-4">
                                                            <label for="position" class="block font-medium text-sm text-gray-700">Position</label>
                                                            <input id="position" type="text" name="position" value="{{ $user->position }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('position')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Email Address -->
                                                        <div class="mt-4">
                                                            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                                            <input id="email" type="email" name="email" value="{{ $user->email }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('email')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- User phone -->
                                                        <div class="mt-4">
                                                            <label for="phone" class="block font-medium text-sm text-gray-700">Phone</label>
                                                            <input id="phone" type="text" name="phone" value="{{ $user->phone }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('phone')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Mobile -->
                                                        <div class="mt-4">
                                                            <label for="mobile" class="block font-medium text-sm text-gray-700">Mobile</label>
                                                            <input id="mobile" type="text" name="mobile" value="{{ $user->mobile }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('mobile')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- username -->
                                                        <div class="mt-4">
                                                            <label for="username" class="block font-medium text-sm text-gray-700">Username</label>
                                                            <input id="username" type="text" name="username" value="{{ $user->username }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('username')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- User Roles -->
                                                        <div class="mt-4">
                                                            <label for="roles" class="block font-medium text-sm text-gray-700">Roles</label>
                                                            <select id="roleSelect" name="role_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="updateRights(this.value)">
                                                                @foreach($roles as $role)
                                                                    <option value="{{ $role->id }}" {{ $user->userRoles && in_array($role->id, $user->userRoles->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $role->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('roles')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- User Rights -->
                                                        <div class="mt-4">
                                                            <label for="rights" class="block font-medium text-sm text-gray-700">Rights</label>
                                                            <select id="rightsSelect" name="right_id[]" disabled multiple class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                @foreach($user->userRights as $right)
                                                                    <option value="{{ $right->id }}" selected disabled>{{ $right->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('right_id')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>


                                                        <div class="mt-4">
                                                            <a href="{{ route('users.reset-password', $user->id) }}" class="text-blue-600 hover:underline">Change Password</a>
                                                        </div>

                                                        <div class="flex items-center justify-end mt-4">
                                                            <x-primary-button class="ml-4">
                                                                {{ __('Update') }}
                                                            </x-primary-button>
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

    .sidebar-menu ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    .sidebar-menu ul li {
        margin-left: 20px; /* Adjust indentation as needed */
    }

    .sidebar-menu ul li a {
        display: block;
        padding: 8px 15px;
        color: #666;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .sidebar-menu ul li a:hover {
        background-color: #f0f0f0;
    }

    .text-red-500 {
        color:red;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('roleSelect');
        const selectedRoleId = roleSelect.value;

        // If a role is selected, fetch and populate the remaining rights
        if (selectedRoleId) {
            updateRights(selectedRoleId);
        }
    });

    function updateRights(roleId) {
        // Clear the rights select element
        const rightsSelect = document.getElementById('rightsSelect');
        rightsSelect.innerHTML = '';

        // Check if a role is selected
        if (roleId) {
            // Fetch the rights associated with the selected role
            fetch(`/roles/${roleId}/rights`)
                .then(response => response.json())
                .then(data => {
                    // Add back the pre-selected rights as disabled options
                    
                    @foreach($user->userRights as $right)
                        const option = document.createElement('option');
                        option.value = {{ $right->id }};
                        option.text = '{{ $right->name }}';
                        option.disabled = true;
                        option.selected = true;
                        rightsSelect.add(option);
                    @endforeach

                    // Populate the rights select element with the remaining rights
                    data.forEach(right => {
                        const option = document.createElement('option');
                        option.value = right.id;
                        option.text = right.name;
                        rightsSelect.add(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching rights:', error);
                });
        }
    }
</script>