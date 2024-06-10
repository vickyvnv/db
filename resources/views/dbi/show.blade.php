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
                    <!-- Log button -->
                    <div class="mt-4">
                        <a href="{{ route('dbi.showLogs', $dbiRequest->id) }}" target="_blank" class="btn btn-primary">View Logs</a>
                    </div>
                    <br>
                    <h3 class="text-xl font-bold mb-4">DBI Request Details  :  <b>{{Auth::user()->userRoles[0]->name}}</b></h3>
                    <div class="grid grid-cols-2 gap-4">
                        @if($userAssigned[0]['role_name'][0] === 'Requester')
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                @foreach ($userAssigned as $user)
                                    @if ($user['user_id'] == $dbiRequest->requestor_id && $user['role_name'][0] == 'Requester')
                                        <h3 class="text-lg font-bold mb-4">Requestor</h3>
                                        <div class="mb-2">
                                            <p class="font-bold text-gray-600">Name:</p>
                                            <p class="text-gray-800">{{ $user['first_name'] }} {{ $user['last_name'] }}</p>
                                        </div>
                                        <div class="mb-2">
                                            <p class="font-bold text-gray-600">Email:</p>
                                            <p class="text-gray-800">{{ $user['email'] }}</p>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-600">Role:</p>
                                            <p class="text-gray-800">{{ $user['role_name'][0] }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @if((
                            (isset($userAssigned[0]['role_name'][0]) && !empty($userAssigned[0]['role_name'][0]) && $userAssigned[0]['role_name'][0] == 'SDE') ||
                            (isset($userAssigned[1]['role_name'][0]) && !empty($userAssigned[1]['role_name'][0]) && $userAssigned[1]['role_name'][0] == 'SDE')
                        ))
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                @foreach ($userAssigned as $user)
                                    @if ($user['user_id'] == $dbiRequest->operator_id && $user['role_name'][0] == 'SDE')
                                        <h3 class="text-lg font-bold mb-4">Operator</h3>
                                        <div class="mb-2">
                                            <p class="font-bold text-gray-600">Name:</p>
                                            <p class="text-gray-800">{{ $user['first_name'] }} {{ $user['last_name'] }}</p>
                                        </div>
                                        <div class="mb-2">
                                            <p class="font-bold text-gray-600">Email:</p>
                                            <p class="text-gray-800">{{ $user['email'] }}</p>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-600">Role:</p>
                                            <p class="text-gray-800">{{ $user['role_name'][0] }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        @if($userAssigned[0]['role_name'][0] === 'DAT')
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                @foreach ($userAssigned as $user)
                                    @if ($user['user_id'] == $dbiRequest->operator_id && $user['role_name'][0] == 'DAT')
                                        <h3 class="text-lg font-bold mb-4">Operator & Requestor</h3>
                                        <div class="mb-2">
                                            <p class="font-bold text-gray-600">Name:</p>
                                            <p class="text-gray-800">{{ $user['first_name'] }} {{ $user['last_name'] }}</p>
                                        </div>
                                        <div class="mb-2">
                                            <p class="font-bold text-gray-600">Email:</p>
                                            <p class="text-gray-800">{{ $user['email'] }}</p>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-600">Role:</p>
                                            <p class="text-gray-800">{{ $user['role_name'][0] }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <p class="font-bold text-gray-600">DBI Request Source code:</p>
                            <p class="text-gray-800">
                            <div class="mt-4">
                                <textarea class="form-control" rows="10">{{ $dbiRequest->source_code }}</textarea>
                            </div>
                            </p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <p class="font-bold text-gray-600">DBI Request Test Log:</p>
                            <p class="text-gray-800">
                            <div class="mt-4">
                                <textarea class="form-control" rows="10">{{ $dbiRequest->sql_logs_info }}</textarea>
                            </div>
                            </p>
                        </div>
                        @if($dbiRequest->sql_logs_info_prod != null)
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                <p class="font-bold text-gray-600">DBI Request Prod Log:</p>
                                <p class="text-gray-800">
                                <div class="mt-4">
                                    <textarea class="form-control" rows="10">{{ $dbiRequest->sql_logs_info_prod }}</textarea>
                                </div>
                                </p>
                            </div>
                        @endif
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <p class="font-bold text-gray-600">DBI Request Status:</p>
                            <p class="text-gray-800">
                                @if($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                                    <h1>Request submitted to SDE: {{ $dbiRequest->operator->user_firstname }} {{ $dbiRequest->operator->user_lastname }}</h1>
                                    <h1>Email: {{ $dbiRequest->operator->email }}</h1>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 2 && $dbiRequest->dbiRequestStatus->dat_status === 0)
                                    <h1>Request rejected by SDE: {{ $dbiRequest->operator->user_firstname }} {{ $dbiRequest->operator->user_lastname }}</h1>
                                    <h1>Email: {{ $dbiRequest->operator->email }}</h1>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                                    <h1>Request Approved by SDE : {{ $dbiRequest->operator->user_firstname }} {{ $dbiRequest->operator->user_lastname }}</h1>
                                    <h1>Email: {{ $dbiRequest->operator->email }}</h1>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 1)
                                    <h1>Request Approved by DAT</h1>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 2)
                                    <h1>Request rejected by DAT</h1>
                                    @elseif($dbiRequest->dbiRequestStatus->request_status == 11 && $dbiRequest->dbiRequestStatus->operator_status == 11 && $dbiRequest->dbiRequestStatus->dat_status == 10)
                                <h1><b>DBI Request for Prod is submitted to DAT user</b></h1>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 11 && $dbiRequest->dbiRequestStatus->operator_status == 10 && $dbiRequest->dbiRequestStatus->dat_status == 10)
                                    <h1><b>DBI Request is for Prod submitted to SDE user</b></h1>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 10 && $dbiRequest->dbiRequestStatus->operator_status == 12 && $dbiRequest->dbiRequestStatus->dat_status == 10)
                                    <h1><b>DBI Request for Prod is rejected by SDE user</b></h1>
                                    
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 11 && $dbiRequest->dbiRequestStatus->operator_status == 11 && $dbiRequest->dbiRequestStatus->dat_status == 11)
                                    <h1><b>DBI Request for prod is Approved by DAT user</b></h1>
                                @else
                                    <h1>Request is pending</h1>
                                @endif
                            </p>
                        </div>
                        <!-- ... -->
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            @if($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0 && $userAssigned[0]['role_name'][0] == 'Requester')
                                <form action="{{ route('dbi.submitToSDE', $dbiRequest->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="prodTest" value="No">
                                    <button type="submit" class="btn btn-primary">Submit to SDE</button>
                                </form>
                            @elseif($dbiRequest->operator_id == Auth::user()->id && $dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0 && ((isset($userAssigned[0]['role_name'][0]) && !empty($userAssigned[0]['role_name'][0]) && $userAssigned[0]['role_name'][0] == 'SDE') ||
                                    (isset($userAssigned[1]['role_name'][0]) && !empty($userAssigned[1]['role_name'][0]) && $userAssigned[1]['role_name'][0] == 'SDE')
                                ))
                                <form action="{{ route('dbi.sdeApprovedOrReject', $dbiRequest->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label>Status: </label>
                                        <div class="flex items-center">
                                            <input type="radio" id="approve" name="approvalorreject" value="approve" class="mr-2" required>
                                            <label for="approve" class="text-gray-700 font-bold">Approve</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="approve" name="approvalorreject" value="reject" class="mr-2" required>
                                            <label for="reject" class="text-gray-700 font-bold">Reject</label>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label>Comment Status: </label>
                                        <input type="text" id="statuscomment" name="operator_comment" class="mr-2" required>
                                    </div>
                                    <input type="hidden" name="prodTest" value="No">

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 0 && Auth::user()->userRoles[0]->name === 'DAT')
                                <form action="{{ route('dbi.datApprovedOrReject', $dbiRequest->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label>Status: </label>
                                        <div class="flex items-center">
                                            <input type="radio" id="approvalorreject" name="approvalorreject" value="approve" class="mr-2" required>
                                            <label for="approve" class="text-gray-700 font-bold">Approve</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="approvalorreject" name="approvalorreject" value="reject" class="mr-2" required>
                                            <label for="reject" class="text-gray-700 font-bold">Reject</label>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label>Comment Status: </label>
                                        <input type="text" id="statuscomment" name="dat_comment" class="mr-2" required>
                                    </div>
                                    <input type="hidden" name="prodTest" value="No">

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                                <h1><b>DBI Request is submitted to DAT user</b></h1>
                            @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                                <h1><b>DBI Request is submitted to SDE user</b></h1>
                            @elseif($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 2 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                                <h1><b>DBI Request is rejected by SDE user</b></h1>
                                @if(Auth::user()->userRoles[0]->name !== 'SDE' && Auth::user()->id == $dbiRequest->requestor_id)
                                    <a href="{{ route('dbi.edit', $dbiRequest->id) }}" class="btn btn-secondary">Edit</a>
                                @endif
                            @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 1)
                                <h1><b>DBI Request is Approved by DAT user</b></h1>
                            


                            @elseif($dbiRequest->dbiRequestStatus->request_status == 11 && $dbiRequest->dbiRequestStatus->operator_status == 11 && $dbiRequest->dbiRequestStatus->dat_status == 10)
                                <h1><b>DBI Request for Prod is submitted to DAT user</b></h1>
                            @elseif($dbiRequest->dbiRequestStatus->request_status == 11 && $dbiRequest->dbiRequestStatus->operator_status == 10 && $dbiRequest->dbiRequestStatus->dat_status == 10)
                                <h1><b>DBI Request is for Prod submitted to SDE user</b></h1>
                            @elseif($dbiRequest->dbiRequestStatus->request_status == 10 && $dbiRequest->dbiRequestStatus->operator_status == 12 && $dbiRequest->dbiRequestStatus->dat_status == 10)
                                <h1><b>DBI Request for Prod is rejected by SDE user</b></h1>
                                
                            @elseif($dbiRequest->dbiRequestStatus->request_status == 11 && $dbiRequest->dbiRequestStatus->operator_status == 11 && $dbiRequest->dbiRequestStatus->dat_status == 11)
                                <h1><b>DBI Request for prod is Approved by DAT user</b></h1>
                            @else
                                <h1><b>DBI Request is submitted</b></h1>
                            @endif
                        </div>
                        @if($dbiRequest->dbiRequestStatus->request_status == 10 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 1 && Auth::user()->userRoles[0]->name == 'Requester')
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                <form action="{{ route('dbi.submitToSDE', $dbiRequest->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="prodTest" value="Yes">
                                    <button type="submit" class="btn btn-primary">Submit to SDE(Prod)</button>
                                </form>
                            </div>
                        @endif

                        @if(Auth::user()->id == $dbiRequest->operator_id && $dbiRequest->dbiRequestStatus->request_status == 11 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 1 && Auth::user()->userRoles[0]->name == 'SDE')
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <form action="{{ route('dbi.sdeApprovedOrReject', $dbiRequest->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label>Status: </label>
                                        <div class="flex items-center">
                                            <input type="radio" id="approve" name="approvalorreject" value="approve" class="mr-2" required>
                                            <label for="approve" class="text-gray-700 font-bold">Approve</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="approve" name="approvalorreject" value="reject" class="mr-2" required>
                                            <label for="reject" class="text-gray-700 font-bold">Reject</label>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label>Comment Status: </label>
                                        <input type="text" id="statuscomment" name="operator_comment" class="mr-2" required>
                                    </div>
                                    <input type="hidden" name="prodTest" value="Yes">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        @endif

                        @if($dbiRequest->dbiRequestStatus->request_status == 11 && $dbiRequest->dbiRequestStatus->operator_status == 11 && $dbiRequest->dbiRequestStatus->dat_status == 1 && Auth::user()->userRoles[0]->name == 'DAT')
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                <form action="{{ route('dbi.datApprovedOrReject', $dbiRequest->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label>Status: </label>
                                        <div class="flex items-center">
                                            <input type="radio" id="approvalorreject" name="approvalorreject" value="approve" class="mr-2" required>
                                            <label for="approve" class="text-gray-700 font-bold">Approve</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="approvalorreject" name="approvalorreject" value="reject" class="mr-2" required>
                                            <label for="reject" class="text-gray-700 font-bold">Reject</label>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label>Comment Status: </label>
                                        <input type="text" id="statuscomment" name="dat_comment" class="mr-2" required>
                                    </div>
                                    <input type="hidden" name="prodTest" value="Yes">

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        @endif
                    
                        @if($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 1 && Auth::user()->userRoles[0]->name == 'Requester')
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                <form action="{{ route('dbi.testDbi', $dbiRequest->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="prodTest" value="Yes">
                                    <button type="submit" class="btn btn-primary">Production Run</button>
                                </form>
                            </div>
                        @endif
                       
                      </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Logging code -->
    @php
        $logFile = storage_path('dbilogs/' . $dbiRequest->id . '_dbi_request.log');
        $timestamp = date('Y-m-d H:i:s');

        // Log DBI request details
        $logData = "[{$timestamp}] DBI Request Details:\n";
        $logData .= "Request ID: {$dbiRequest->id}\n";
        $logData .= "Requestor: {$dbiRequest->requestor->user_firstname} {$dbiRequest->requestor->user_lastname}\n";
        $logData .= "Operator: {$dbiRequest->operator->user_firstname} {$dbiRequest->operator->user_lastname}\n";
        $logData .= "Source Code:\n{$dbiRequest->source_code}\n";
        $logData .= "Test Log:\n{$dbiRequest->sql_logs_info}\n";
        if ($dbiRequest->sql_logs_info_prod) {
            $logData .= "Prod Log:\n{$dbiRequest->sql_logs_info_prod}\n";
        }

        // Log status based on conditions
        $logData .= "Status:\n";
        if ($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0) {
            $logData .= "Request submitted to SDE: {$dbiRequest->operator->user_firstname} {$dbiRequest->operator->user_lastname}\n";
            $logData .= "Email: {$dbiRequest->operator->email}\n";
        } elseif ($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 2 && $dbiRequest->dbiRequestStatus->dat_status === 0) {
            $logData .= "Request rejected by SDE: {$dbiRequest->operator->user_firstname} {$dbiRequest->operator->user_lastname}\n";
            $logData .= "Email: {$dbiRequest->operator->email}\n";
        } elseif ($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 0) {
            $logData .= "Request Approved by SDE: {$dbiRequest->operator->user_firstname} {$dbiRequest->operator->user_lastname}\n";
            $logData .= "Email: {$dbiRequest->operator->email}\n";
        } elseif ($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 1) {
            $logData .= "Request Approved by DAT\n";
        } elseif ($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 2) {
            $logData .= "Request rejected by DAT\n";
        } elseif ($dbiRequest->dbiRequestStatus->request_status == 11 && $dbiRequest->dbiRequestStatus->operator_status == 11 && $dbiRequest->dbiRequestStatus->dat_status == 10) {
            $logData .= "DBI Request for Prod is submitted to DAT user\n";
        } elseif ($dbiRequest->dbiRequestStatus->request_status == 11 && $dbiRequest->dbiRequestStatus->operator_status == 10 && $dbiRequest->dbiRequestStatus->dat_status == 10) {
            $logData .= "DBI Request is for Prod submitted to SDE user\n";
        } elseif ($dbiRequest->dbiRequestStatus->request_status == 10 && $dbiRequest->dbiRequestStatus->operator_status == 12 && $dbiRequest->dbiRequestStatus->dat_status == 10) {
            $logData .= "DBI Request for Prod is rejected by SDE user\n";
        } elseif ($dbiRequest->dbiRequestStatus->request_status == 11 && $dbiRequest->dbiRequestStatus->operator_status == 11 && $dbiRequest->dbiRequestStatus->dat_status == 11) {
            $logData .= "DBI Request for prod is Approved by DAT user\n";
        } else {
            $logData .= "Request is pending\n";
        }

        $logData .= "-----------------------------\n";

        file_put_contents($logFile, $logData);
    @endphp
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