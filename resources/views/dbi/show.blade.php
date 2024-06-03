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
                <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold mb-4">DBI Request Details</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @if($assigned[0]['user_roles'][0]['name'] === 'Requester')
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                @foreach ($assigned as $user)
                                    @if ($user['id'] == $dbiRequest->requestor_id && $user['user_roles'][0]['name'] == 'Requester')
                                        <h3 class="text-lg font-bold mb-4">Requestor</h3>
                                        <div class="mb-2">
                                            <p class="font-bold text-gray-600">Name:</p>
                                            <p class="text-gray-800">{{ $user['user_firstname'] }} {{ $user['user_lastname'] }}</p>
                                        </div>
                                        <div class="mb-2">
                                            <p class="font-bold text-gray-600">Email:</p>
                                            <p class="text-gray-800">{{ $user['email'] }}</p>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-600">Role:</p>
                                            <p class="text-gray-800">{{ $user['user_roles'][0]['name'] }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @if((
                            (isset($assigned[0]['user_roles'][0]['name']) && !empty($assigned[0]['user_roles'][0]['name']) && $assigned[0]['user_roles'][0]['name'] == 'SDE') ||
                            (isset($assigned[1]['user_roles'][0]['name']) && !empty($assigned[1]['user_roles'][0]['name']) && $assigned[1]['user_roles'][0]['name'] == 'SDE')
                        ))
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                @foreach ($assigned as $user)
                                    @if ($user['id'] == $dbiRequest->operator_id && $user['user_roles'][0]['name'] == 'SDE')
                                        <h3 class="text-lg font-bold mb-4">Operator</h3>
                                        <div class="mb-2">
                                            <p class="font-bold text-gray-600">Name:</p>
                                            <p class="text-gray-800">{{ $user['user_firstname'] }} {{ $user['user_lastname'] }}</p>
                                        </div>
                                        <div class="mb-2">
                                            <p class="font-bold text-gray-600">Email:</p>
                                            <p class="text-gray-800">{{ $user['email'] }}</p>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-600">Role:</p>
                                            <p class="text-gray-800">{{ $user['user_roles'][0]['name'] }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        @if($assigned[0]['user_roles'][0]['name'] === 'DAT')
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            @foreach ($assigned as $user)
                                @if ($user['id'] == $dbiRequest->operator_id && $user['user_roles'][0]['name'] == 'DAT')
                                    <h3 class="text-lg font-bold mb-4">Operator & Requestor</h3>
                                    <div class="mb-2">
                                        <p class="font-bold text-gray-600">Name:</p>
                                        <p class="text-gray-800">{{ $user['user_firstname'] }} {{ $user['user_lastname'] }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <p class="font-bold text-gray-600">Email:</p>
                                        <p class="text-gray-800">{{ $user['email'] }}</p>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-600">Role:</p>
                                        <p class="text-gray-800">{{ $user['user_roles'][0]['name'] }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    
                </div>
                <div class="grid grid-cols-3 gap-6">
                    <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-lg">
                        <div class="mb-2">
                            <p class="font-bold text-gray-600">DBI Category:</p>
                            <p class="text-gray-800">{{ $dbiRequest->category }}</p>
                        </div>
                        <div class="mb-2">
                            <p class="font-bold text-gray-600">Priority:</p>
                            <p class="text-gray-800">{{ $dbiRequest->priority_id }}</p>
                        </div>
                        <div class="mb-2">
                            <p class="font-bold text-gray-600">Market:</p>
                            <p class="text-gray-800">{{ $dbiRequest->sw_version }}</p>
                        </div>
                        <div class="mb-2">
                            <p class="font-bold text-gray-600">DBI Type:</p>
                            <p class="text-gray-800">{{ $dbiRequest->dbi_type }}</p>
                        </div>
                        <div class="mb-2">
                            <p class="font-bold text-gray-600">TT Number:</p>
                            <p class="text-gray-800">{{ $dbiRequest->tt_id }}</p>
                        </div>
                        <div class="mb-2">
                            <p class="font-bold text-gray-600">Serf/CR:</p>
                            <p class="text-gray-800">{{ $dbiRequest->serf_cr_id }}</p>
                        </div>
                        <div>
                            <p class="font-bold text-gray-600">Reference DBI:</p>
                            <p class="text-gray-800">{{ $dbiRequest->reference_dbi }}</p>
                        </div>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-lg">
                        <h3 class="text-xl font-bold mb-4">Description</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="mb-4">
                                <p class="font-bold text-gray-600">Brief Description:</p>
                                <p class="text-gray-800">{{ $dbiRequest->brief_desc }}</p>
                            </div>
                            <div class="mb-4">
                                <p class="font-bold text-gray-600">Problem Description:</p>
                                <p class="text-gray-800">{{ $dbiRequest->problem_desc }}</p>
                            </div>
                            <div>
                                <p class="font-bold text-gray-600">Business Impact:</p>
                                <p class="text-gray-800">{{ $dbiRequest->business_impact }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-lg">
                        <h3 class="text-xl font-bold mb-4">Technical Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-2">
                                <p class="font-bold text-gray-600">Source Code:</p>
                                <p class="text-gray-800">{{ $dbiRequest->source_code }}</p>
                            </div>
                            <div class="mb-2">
                                <p class="font-bold text-gray-600">DB Instance:</p>
                                <p class="text-gray-800">{{ $dbiRequest->db_instance }}</p>
                            </div>
                            <div class="mb-2">
                                <p class="font-bold text-gray-600">SQL File Path:</p>
                                <p class="text-gray-800">{{ $dbiRequest->sql_file_path }}</p>
                            </div>
                            <div class="mb-2">
                                <p class="font-bold text-gray-600">SQL Logs Info:</p>
                            </div>
                            <div class="col-span-2">
                                <textarea class="form-control" rows="10" disabled>{{ $dbiRequest->sql_logs_info }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @if($dbiRequest->is_requestor_submit == 0 && $assigned[0]['user_roles'][0]['name'] == 'Requester')
                    <form action="{{ route('dbi.submitToSDE', $dbiRequest->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_requestor_submit" value="0">
                        <button type="submit" class="btn btn-primary">Submit to SDE</button>
                    </form>
                @elseif($dbiRequest->is_requestor_submit == 1 && $dbiRequest->is_operator_approve == 0 && (
        (isset($assigned[0]['user_roles'][0]['name']) && !empty($assigned[0]['user_roles'][0]['name']) && $assigned[0]['user_roles'][0]['name'] == 'SDE') ||
        (isset($assigned[1]['user_roles'][0]['name']) && !empty($assigned[1]['user_roles'][0]['name']) && $assigned[1]['user_roles'][0]['name'] == 'SDE')
    ))
                    <form action="{{ route('dbi.sdeApprovedOrReject', $dbiRequest->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_requestor_submit" value="1">
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="radio" id="approve" name="approvalorreject" value="approve" class="mr-2" required>
                                <label for="approve" class="text-gray-700 font-bold">Approve</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="approve" name="approvalorreject" value="reject" class="mr-2" required>
                                <label for="reject" class="text-gray-700 font-bold">Reject</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    @elseif($dbiRequest->is_requestor_submit == 1 && $dbiRequest->is_operator_approve == 1 && $dbiRequest->is_admin_approve == 0 &&   (
        (isset($assigned[0]['user_roles'][0]['name']) && !empty($assigned[0]['user_roles'][0]['name']) && $assigned[0]['user_roles'][0]['name'] == 'DAT') ||
        (isset($assigned[1]['user_roles'][0]['name']) && !empty($assigned[1]['user_roles'][0]['name']) && $assigned[1]['user_roles'][0]['name'] == 'DAT')
    ))
                    <form action="{{ route('dbi.sdeApprovedOrReject', $dbiRequest->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_requestor_submit" value="1">
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="radio" id="approve" name="approvalorreject" value="approve" class="mr-2" required>
                                <label for="approve" class="text-gray-700 font-bold">Approve</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="approve" name="approvalorreject" value="reject" class="mr-2" required>
                                <label for="reject" class="text-gray-700 font-bold">Reject</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                @elseif($dbiRequest->is_requestor_submit == 1 && $dbiRequest->is_operator_approve == 1)
                    <h1><b>DBI Request is submitted to DAT user</b></h1>
                @elseif($dbiRequest->is_requestor_submit == 1 && $dbiRequest->is_operator_approve == 0)
                    <h1><b>DBI Request is submitted to SDE user</b></h1>
                @elseif($dbiRequest->is_requestor_submit == 1 && $dbiRequest->is_operator_approve == 2)
                    <h1><b>DBI Request is rejected SDE user</b></h1>
                @elseif($dbiRequest->is_requestor_submit == 1 && $dbiRequest->is_operator_approve == 1 && $dbiRequest->is_admin_approve == 1)
                    <h1><b>DBI Request is Approved by DAT user</b></h1>
                @else
                    <h1><b>DBI Request is submitted</b></h1>
                @endif

               
            </div>
        </div>
    </div>
    </x-app-layout>
<style>
    textarea.form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 0.375rem;
        background-color: #f7f7f7;
        color: #333;
    }
    .sidebar-menu li a:hover {
        background-color: #f2f2f2;
    }
    .container {
        float: left;
    }
    .card {
        margin-top: 30px;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .font-bold {
        font-weight: 600;
    }
    .p-6 {
        padding: 1.5rem;
    }
    .rounded-lg {
        border-radius: 0.5rem;
    }
    .shadow-lg {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .text-gray-600 {
        color: #4b5563;
    }
    .text-gray-800 {
        color: #1f2937;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: 600;
    }
    .btn-primary {
        background-color: #3b82f6;
        color: #fff;
    }
    .btn-primary:hover {
        background-color: #2563eb;
    }
</style>