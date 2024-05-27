<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('DBI Request Details') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar -->
        @include('partials.dbi-sidebar')

        <!-- Main Content -->
        <div class="w-3/4 p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{ route('dbi.index') }}" class="btn btn-secondary mb-3">Back</a>

            <!-- Display success or error messages if needed -->
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
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

            <!-- Display DBI request details -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-bold mb-2">DBI Request Details</h3>
                    <div class="grid grid-cols-2 gap-2">
                    <div class="grid grid-cols-2 gap-6">
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-md">
                                @foreach ($assigned as $user)
                                    @if ($user['id'] == $dbiRequest->requestor_id && $user['user_roles'][0]['name'] == 'Requester')
                                        <h3 class="text-lg font-bold mb-2">Requestor</h3>
                                        <div>
                                            <p class="font-bold">Name:</p>
                                            <p>{{ $user['user_firstname'] }} {{ $user['user_lastname'] }}</p>
                                        </div>
                                        <div>
                                            <p class="font-bold">Email:</p>
                                            <p>{{ $user['email'] }}</p>
                                        </div>
                                        <div>
                                            <p class="font-bold">Role:</p>
                                            <p>{{ $user['user_roles'][0]['name'] }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-md">
                                @foreach ($assigned as $user)
                                    @if ($user['id'] == $dbiRequest->operator_id && $user['user_roles'][0]['name'] == 'SDE')
                                        <h3 class="text-lg font-bold mb-2">Operator</h3>
                                        <div>
                                            <p class="font-bold">Name:</p>
                                            <p>{{ $user['user_firstname'] }} {{ $user['user_lastname'] }}</p>
                                        </div>
                                        <div>
                                            <p class="font-bold">Email:</p>
                                            <p>{{ $user['email'] }}</p>
                                        </div>
                                        <div>
                                            <p class="font-bold">Role:</p>
                                            <p>{{ $user['user_roles'][0]['name'] }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            @if($assigned[0]['user_roles'][0]['name'] === 'DAT')
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-md">
                                @foreach ($assigned as $user)
                                    @if ($user['id'] == $dbiRequest->operator_id && $user['user_roles'][0]['name'] == 'DAT')
                                        <h3 class="text-lg font-bold mb-2">Operator & Requestor</h3>
                                        <div>
                                            <p class="font-bold">Name:</p>
                                            <p>{{ $user['user_firstname'] }} {{ $user['user_lastname'] }}</p>
                                        </div>
                                        <div>
                                            <p class="font-bold">Email:</p>
                                            <p>{{ $user['email'] }}</p>
                                        </div>
                                        <div>
                                            <p class="font-bold">Role:</p>
                                            <p>{{ $user['user_roles'][0]['name'] }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                        </div><br>
                        <div>
                            <p class="font-bold">DBI Category:</p>
                            <p>{{ $dbiRequest->category }}</p>
                        </div>
                        
                        <div>
                            <p class="font-bold">Priority:</p>
                            <p>{{ $dbiRequest->priority_id }}</p>
                        </div>
                        <div>
                            <p class="font-bold">Market:</p>
                            <p>{{ $dbiRequest->sw_version }}</p>
                        </div>
                        <div>
                            <p class="font-bold">DBI Type:</p>
                            <p>{{ $dbiRequest->dbi_type }}</p>
                        </div>
                        <div>
                            <p class="font-bold">TT Number:</p>
                            <p>{{ $dbiRequest->tt_id }}</p>
                        </div>
                        <div>
                            <p class="font-bold">Serf/CR:</p>
                            <p>{{ $dbiRequest->serf_cr_id }}</p>
                        </div>
                        <div>
                            <p class="font-bold">Reference DBI:</p>
                            <p>{{ $dbiRequest->reference_dbi }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-bold mb-2">Description</h3>
                    <div class="grid grid-cols-1 gap-2">
                        <div>
                            <p class="font-bold">Brief Description:</p>
                            <p>{{ $dbiRequest->brief_desc }}</p>
                        </div>
                        <div>
                            <p class="font-bold">Problem Description:</p>
                            <p>{{ $dbiRequest->problem_desc }}</p>
                        </div>
                        <div>
                            <p class="font-bold">Business Impact:</p>
                            <p>{{ $dbiRequest->business_impact }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-bold mb-2">Technical Details</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-bold">Source Code:</p>
                            <p>{{ $dbiRequest->source_code }}</p>
                        </div>
                        <div>
                            <p class="font-bold">DB Instance:</p>
                            <p>{{ $dbiRequest->db_instance }}</p>
                        </div>
                        <div>
                            <p class="font-bold">SQL File Path:</p>
                            <p>{{ $dbiRequest->sql_file_path }}</p>
                        </div>
                        <div>
                            <p class="font-bold">SQL Logs Info:</p>
                        </div>
                        <div class="mt-4">
                            <textarea class="form-control" rows="10" disabled>{{ $dbiRequest->sql_logs_info }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    textarea.form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
}
    .sidebar-menu li a:hover {
        background-color: #ddd;
    }

    .container {
        float: left;
    }

    .card {
        margin-top: 20px;
    }
    .font-bold {
        font-weight: bold;
    }

    

    .p-4 {
        padding: 1rem;
    }

    .rounded-lg {
        border-radius: 0.5rem;
    }

    .shadow-md {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>