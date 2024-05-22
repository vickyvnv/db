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

                                                    <div class="form-row">
                                                        <!-- First Name -->
                                                        <div class="form-group mt-4">
                                                            <label for="user_firstname" class="block font-medium text-sm text-gray-700">First Name</label>
                                                            <input id="user_firstname" type="text" name="user_firstname" value="{{ old('user_firstname', $user->user_firstname) }}" required autofocus
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('user_firstname')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="form-group mt-4">
                                                            <label for="user_lastname" class="block font-medium text-sm text-gray-700">Last Name</label>
                                                            <input id="user_lastname" type="text" name="user_lastname" value="{{ old('user_lastname', $user->user_lastname) }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('user_lastname')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <!-- User Department -->
                                                        <div class="form-group mt-4">
                                                            <label for="user_department" class="block font-medium text-sm text-gray-700">User Department</label>
                                                            <input id="user_department" type="text" name="user_department" value="{{ old('user_department', $user->user_department) }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('user_department')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- User Company -->
                                                        <div class="form-group mt-4">
                                                            <label for="company" class="block font-medium text-sm text-gray-700">Company</label>
                                                            <input id="company" type="text" name="company" value="{{ old('company', $user->company) }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('company')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <!-- User Position -->
                                                        <div class="form-group mt-4">
                                                            <label for="position" class="block font-medium text-sm text-gray-700">Position</label>
                                                            <input id="position" type="text" name="position" value="{{ old('position', $user->position) }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('position')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Email Address -->
                                                        <div class="form-group mt-4">
                                                            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('email')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <!-- User phone -->
                                                        <div class="form-group mt-4">
                                                            <label for="phone" class="block font-medium text-sm text-gray-700">Phone</label>
                                                            <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('phone')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Mobile -->
                                                        <div class="form-group mt-4">
                                                            <label for="mobile" class="block font-medium text-sm text-gray-700">Mobile</label>
                                                            <input id="mobile" type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}" required
                                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            @error('mobile')
                                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <!-- username -->
                                                    <div class="mt-4">
                                                        <label for="username" class="block font-medium text-sm text-gray-700">Username</label>
                                                        <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}" required
                                                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        @error('username')
                                                            <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="mt-4">
                                                        <label for="teams" class="block font-medium text-sm text-gray-700">Teams</label>
                                                        <select id="team_id" name="team_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="updateAssignedUsers()">
                                                            @foreach($teams as $team)
                                                                <option value="{{ $team->id }}" {{ old('team_id', $user->team_id) == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('teams')
                                                            <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- User Roles -->
                                                    <div class="mt-4">
                                                        <label for="roles" class="block font-medium text-sm text-gray-700">Roles</label>
                                                        <select id="roleSelect" name="role_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="updateAssignedUsers()">
                                                            @foreach($roles as $role)
                                                                <option value="{{ $role->id }}" {{ old('role_id', optional($user->userRoles->first())->id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('roles')
                                                            <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Assigned User -->
                                                    <div class="mt-4" id="assignedUserContainer" style="display: none;">
                                                        <label for="assigned_user_id" class="block font-medium text-sm text-gray-700">Assigned User</label>
                                                        <select id="assigned_user_id" name="assigned_user_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                            <option value="">Select Assigned User</option>
                                                        </select>
                                                        @error('assigned_user_id')
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
    

    .text-red-500 {
        color:red;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-left: -10px;
        margin-right: -10px;
    }

    .form-group {
        flex: 1;
        padding-left: 10px;
        padding-right: 10px;
    }
</style>
<script>
    console.log("asd");
    function updateAssignedUsers() {
        var teamId = document.getElementById('team_id').value;
        var roleId = document.getElementById('roleSelect').value;
        var userId = {{ $user->id }};

        if (roleId == 5) {
            // Enable and show the assigned user container
            document.getElementById('assignedUserContainer').style.display = 'block';

            // Make an AJAX request to fetch the assigned users
            fetch('/admin/users/assigned-users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    team_id: teamId,
                    role_id: roleId,
                    user_id: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                // Clear the assigned user select options
                var assignedUserSelect = document.getElementById('assigned_user_id');
                assignedUserSelect.innerHTML = '<option value="">Select Assigned User</option>';

                // Populate the select options with the fetched users
                data.forEach(user => {
                    var option = document.createElement('option');
                    option.value = user.id;
                    option.text = user.user_firstname + ' ' + user.user_lastname;
                    if (user.id == {{ old('assigned_user_id', optional($user->assignedUser)->pluck('id')->first() ?? $user->id) }}) {
                        option.selected = true;
                    }
                    assignedUserSelect.add(option);
                });
            })
            .catch(error => {
                console.error('Error fetching assigned users:', error);
            });
        } else {
            // Disable and hide the assigned user container
            document.getElementById('assignedUserContainer').style.display = 'none';
        }
    }

    // Call the function on page load
    updateAssignedUsers();

    function updateSelectedTeam(selectElement) {
        var selectedValue = selectElement.value;
        var options = selectElement.options;

        for (var i = 0; i < options.length; i++) {
            if (options[i].value == selectedValue) {
                options[i].setAttribute('selected', 'selected');
            } else {
                options[i].removeAttribute('selected');
            }
        }
    }
</script>