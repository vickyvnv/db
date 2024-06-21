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
        <div class="w-3/4 p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{ route('dbi.index') }}" class="btn btn-primary">Back</a><br><br>

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

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Display DBI request details -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-lg">
                    <!-- Log button -->
                    <div class="mt-4 flex space-x-4">
                                <a href="{{ route('dbi.allLogs', $dbiRequest->id) }}" class="btn btn-primary">View All Logs And History</a>
                                <a href="{{ route('dbi.allSqlFile', $dbiRequest->id) }}" class="btn btn-primary">Click here to check SQL File</a>
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
                        
                                @if($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                    <div class="space-y-2">
                                        <h2 class="font-bold text-gray-600">Request submitted to SDE</h2>
                                        <p>
                                            <span class="font-bold">SDE:</span> {{ $dbiRequest->operator->user_firstname }} {{ $dbiRequest->operator->user_lastname }}
                                        </p>
                                        <p>
                                            <span class="font-bold">Email:</span> {{ $dbiRequest->operator->email }}
                                        </p>
                                    </div>
                                    </div>
                                </div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 2 && $dbiRequest->dbiRequestStatus->dat_status === 0)
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                    <div class="space-y-2">
                                        <h2 class="font-bold text-gray-600">Request rejected by SDE</h2>
                                        <p>
                                            <span class="font-bold">SDE:</span> {{ $dbiRequest->operator->user_firstname }} {{ $dbiRequest->operator->user_lastname }}
                                        </p>
                                        <p>
                                            <span class="font-bold">Email:</span> {{ $dbiRequest->operator->email }}
                                        </p>
                                    </div>
                                    </div>
                                </div>
                                    
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                    <div class="space-y-2">
                                        <h2 class="font-bold text-gray-600">Request Approved by SDE</h2>
                                        <p>
                                            <span class="font-bold">SDE:</span> {{ $dbiRequest->operator->user_firstname }} {{ $dbiRequest->operator->user_lastname }}
                                        </p>
                                        <p>
                                            <span class="font-bold">Email:</span> {{ $dbiRequest->operator->email }}
                                        </p>
                                    </div>
                                    </div></div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 1 && ($dbiRequest->prod_execution == 1 || $dbiRequest->prod_execution == 0))
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                    <h2 class="font-bold text-gray-600">Request Approved by DAT</h2>
                                    </div></div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 1 && $dbiRequest->prod_execution == 2)
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                    <h2 class="font-bold text-gray-600">Error while Execution on Prod DB</h2>
                                    </div></div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 2)
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                    <div class="space-y-2">
                                        <h2 class="font-bold text-gray-600">Request rejected by DAT</h2>
                                        @if(Auth::user()->userRoles[0]->name !== 'SDE' && Auth::user()->id == $dbiRequest->requestor_id)
                                            <a href="{{ route('dbi.edit', $dbiRequest->id) }}" class="inline-block bg-blue-500 hover:bg-blue-700  font-bold py-2 px-4 rounded">Edit</a>
                                        @endif
                                    </div>
                                    </div></div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0 && $dbiRequest->pre_execution == 2)
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                    <div class="space-y-2">
                                        <h2 class="font-bold text-gray-600">Pre DB Execution is failed</h2>
                                        @if(Auth::user()->userRoles[0]->name !== 'SDE' && Auth::user()->id == $dbiRequest->requestor_id)
                                            <a href="{{ route('dbi.edit', $dbiRequest->id) }}" class="btn btn-primary">Edit</a>
                                        @endif
                                    </div>
                                    </div></div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0 && ($dbiRequest->pre_execution == 1 || $dbiRequest->pre_execution == 0) && $user['role_name'][0] != 'DAT')
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                <h2 class="font-bold text-gray-600">DBI Request Pending</h2>
                                    </div></div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0 && $user['role_name'][0] == 'DAT')
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                <h2 class="font-bold text-gray-600">Admin Can Run Production</h2>
                                    </div></div>
                                @endif
                            

                        
                                @if($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0 && $userAssigned[0]['role_name'][0] == 'Requester' && ($dbiRequest->pre_execution == 1 || $dbiRequest->pre_execution == 0))
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <form action="{{ route('dbi.submitToSDE', $dbiRequest->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="prodTest" value="No">
                                                <button type="submit" class="btn btn-primary">Submit to SDE</button>
                                            </form>
                                            @if(Auth::user()->userRoles[0]->name !== 'SDE' && Auth::user()->id == $dbiRequest->requestor_id)
                                                <form action="{{ route('dbi.edit', $dbiRequest->id) }}" method="GET">
                                                    @csrf
                                                    <input type="hidden" name="prodTest" value="No">
                                                    <button type="submit" class="btn btn-primary">Edit</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @elseif($dbiRequest->operator_id == Auth::user()->id && $dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                        <div class="space-y-4">
                                            <form action="{{ route('dbi.sdeApprovedOrReject', $dbiRequest->id) }}" method="POST">
                                                @csrf
                                                <div class="space-y-2">
                                                    <label class="font-bold">Status:</label>
                                                    <div class="flex items-center space-x-2">
                                                        <input type="radio" id="approve" name="approvalorreject" value="approve" class="mr-2" required>
                                                        <label for="approve" class="text-gray-700 font-bold">Approve</label>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <input type="radio" id="reject" name="approvalorreject" value="reject" class="mr-2" required>
                                                        <label for="reject" class="text-gray-700 font-bold">Reject</label>
                                                    </div>
                                                </div>
                                                <div class="mb-4" id="rejectionReasonDiv" style="display: none;">
                                                    <label class="font-bold">Rejection Reasons:</label>
                                                    <select name="operator_comment[]" id="rejectionReason" class="form-control block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" multiple>
                                                        @foreach($rejectionReasons as $reason)
                                                            <option value="{{ $reason->name }}">{{ $reason->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input type="hidden" name="prodTest" value="No">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 0 && Auth::user()->userRoles[0]->name === 'DAT')
                                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                        <div class="space-y-4">
                                            <form action="{{ route('dbi.datApprovedOrReject', $dbiRequest->id) }}" method="POST">
                                                @csrf
                                                <div class="space-y-2">
                                                    <label class="font-bold">Status:</label>
                                                    <div class="flex items-center space-x-2">
                                                        <input type="radio" id="approve" name="approvalorreject" value="approve" class="mr-2" required>
                                                        <label for="approve" class="text-gray-700 font-bold">Approve</label>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <input type="radio" id="reject" name="approvalorreject" value="reject" class="mr-2" required>
                                                        <label for="reject" class="text-gray-700 font-bold">Reject</label>
                                                    </div>
                                                </div>
                                                <div class="mb-4" id="datrejectionReasonDiv" style="display: none;">
                                                    <label class="font-bold">Rejection Reasons:</label>
                                                    <select name="dat_comment[]" id="datrejectionReason" class="form-control block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" multiple>
                                                        @foreach($rejectionReasons as $reason)
                                                            <option value="{{ $reason->name }}">{{ $reason->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input type="hidden" name="prodTest" value="No">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                        <div class="space-y-4">
                                            <div class="space-y-2">
                                                <h2 class="font-bold text-gray-600">Operator Comment:</h2>
                                                <p>{{ $dbiRequest->dbiRequestStatus->operator_comment }}</p>
                                                <h2 class="font-bold text-gray-600">DBI Request is submitted to DAT user</h2>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                        <div class="space-y-4">
                                            <h2 class="font-bold text-gray-600">DBI Request is submitted to SDE user</h2>
                                        </div>
                                    </div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 2 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <h2 class="font-bold text-gray-600">Operator Rejected Reasons:</h2>
                                            @php
                                                $dbiRejectReason = explode(', ', $dbiRequest->dbiRequestStatus->operator_comment);
                                            @endphp
                                            <ul class="list-disc pl-5 space-y-1">
                                                @foreach($dbiRejectReason as $comment)
                                                    <li>{{ $comment }}</li>
                                                @endforeach
                                            </ul>
                                            @if(Auth::user()->userRoles[0]->name !== 'SDE' && Auth::user()->id == $dbiRequest->requestor_id)
                                                <form action="{{ route('dbi.edit', $dbiRequest->id) }}" method="GET">
                                                        @csrf
                                                    <input type="hidden" name="prodTest" value="No">
                                                    <button type="submit" class="btn btn-primary">Edit</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 1  && ($dbiRequest->prod_execution == 1 || $dbiRequest->prod_execution == 0))
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <h2 class="font-bold text-gray-600">DAT:</h2>
                                            <h2 class="font-bold text-gray-600">DBI Request is Approved by DAT user</h2>
                                        </div>
                                    </div>
                                </div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 1 && $dbiRequest->prod_execution == 2)
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <h2 class="font-bold text-gray-600">Production Execution failed.</h2>
                                            <p>You can try again execution or edit your dbi request using the following button. (You Need approval from SDE and DAT user after PreProd Execution)</p>
                                            @if(Auth::user()->userRoles[0]->name !== 'SDE' && Auth::user()->id == $dbiRequest->requestor_id)
                                                <form action="{{ route('dbi.edit', $dbiRequest->id) }}" method="GET">
                                                    @csrf
                                                    <input type="hidden" name="prodTest" value="No">
                                                    <button type="submit" class="btn btn-primary">Edit</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 2)
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <h2 class="font-bold text-gray-600">DAT Comment:</h2>
                                            <p>{{ $dbiRequest->dbiRequestStatus->dat_comment }}</p>
                                            <h2 class="font-bold text-gray-600">DBI Request is Rejected by DAT user</h2>
                                        </div>
                                    </div>
                                </div>
                                @elseif($dbiRequest->dbiRequestStatus->request_status == 3 && $dbiRequest->dbiRequestStatus->operator_status == 3 && $dbiRequest->dbiRequestStatus->dat_status == 3)
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                    <div class="space-y-4">
                                        <h2 class="font-bold text-gray-600">DBI Request Changes commited on Production</h2>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @if($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 1 && Auth::user()->id == $dbiRequest->requestor_id)
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                <form action="{{ route('dbi.testDbi', $dbiRequest->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="prodTest" value="Yes">
                                    <button type="submit" class="btn btn-primary">Production Run</button>
                                </form>
                            </div>
                        @elseif($dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0 && $user['role_name'][0] == 'DAT')
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
        }  else {
            $logData .= "Request is pending\n";
        }

        $logData .= "-----------------------------\n";

        file_put_contents($logFile, $logData);
    @endphp
    </x-app-layout>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var approveRadio = document.getElementById('approve');
            var rejectRadio = document.getElementById('reject');
            var rejectionReasonDiv = document.getElementById('rejectionReasonDiv');

            approveRadio.addEventListener('change', function() {
                if (this.checked) {
                    rejectionReasonDiv.style.display = 'none';
                }
            });

            rejectRadio.addEventListener('change', function() {
                if (this.checked) {
                    rejectionReasonDiv.style.display = 'block';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
        var approveRadio = document.getElementById('approve');
        var rejectRadio = document.getElementById('reject');
        var datrejectionReasonDiv = document.getElementById('datrejectionReasonDiv');

        approveRadio.addEventListener('change', function() {
            if (this.checked) {
                datrejectionReasonDiv.style.display = 'none';
            }
        });

        rejectRadio.addEventListener('change', function() {
            if (this.checked) {
                datrejectionReasonDiv.style.display = 'block';
            }
        });
    });
    </script>
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
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    .flex {
        display: flex;
    }

    .space-x-4 > * + * {
        margin-left: 1rem;
    }
</style>