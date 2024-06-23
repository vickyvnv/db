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
            <a href="{{ route('dbi.index') }}" class="btn btn-primary mb-4">{{ __('Back') }}</a>

            @if (session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">{{ __('Whoops! Something went wrong.') }}</strong>
                    <ul class="mt-3 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Display DBI request details -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-lg">
                    <!-- Log buttons -->
                    <div class="mt-6 flex flex-wrap justify-start gap-4">
                        <a href="{{ route('dbi.allLogs', $dbiRequest->id) }}" class="btn-primary">
                            <i class="fas fa-history mr-2"></i>{{ __('View All Logs And History') }}
                        </a>
                        <a href="{{ route('dbi.allSqlFile', $dbiRequest->id) }}" class="btn-primary">
                            <i class="fas fa-file-code mr-2"></i>{{ __('View SQL Files') }}
                        </a>
                        <a href="{{ route('dbi.requestProcess', $dbiRequest->id) }}" class="btn-primary">
                            <i class="fas fa-project-diagram mr-2"></i>{{ __('View Request Flow') }}
                        </a>
                    </div>
                    <br> </br>
                    <h3 class="text-xl font-bold my-4">{{ __('DBI Request Details') }} : {{ Auth::user()->userRoles->first()->name }}</h3>
                    
                    <!-- Requestor Information -->
                    @if($dbiRequest->requestor->isRequester())
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-4">
                            <h3 class="text-lg font-bold mb-4">{{ __('Requestor') }}</h3>
                            <p><span class="font-bold">{{ __('Name') }}:</span> {{ $dbiRequest->requestor->user_firstname }} {{ $dbiRequest->requestor->user_lastname }}</p>
                            <p><span class="font-bold">{{ __('Email') }}:</span> {{ $dbiRequest->requestor->email }}</p>
                            <p><span class="font-bold">{{ __('Role') }}:</span> {{ $dbiRequest->requestor->userRoles->first()->name }}</p>
                        </div>
                    @endif

                    <!-- Operator Information -->
                    @if($dbiRequest->operator->isSDE())
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-4">
                            <h3 class="text-lg font-bold mb-4">{{ __('Operator') }}</h3>
                            <p><span class="font-bold">{{ __('Name') }}:</span> {{ $dbiRequest->operator->user_firstname }} {{ $dbiRequest->operator->user_lastname }}</p>
                            <p><span class="font-bold">{{ __('Email') }}:</span> {{ $dbiRequest->operator->email }}</p>
                            <p><span class="font-bold">{{ __('Role') }}:</span> {{ $dbiRequest->operator->userRoles->first()->name }}</p>
                        </div>
                    @endif

                    <!-- Source Code -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-4">
                        <h3 class="text-lg font-bold mb-4">{{ __('Source Code') }}</h3>
                        <textarea class="form-control" rows="10" readonly>{{ $dbiRequest->source_code }}</textarea>
                    </div>

                    <!-- Test Log -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-4">
                        <h3 class="text-lg font-bold mb-4">{{ __('Test Log') }}</h3>
                        <textarea class="form-control" rows="10" readonly>{{ $dbiRequest->sql_logs_info }}</textarea>
                    </div>

                    <!-- Production Log (if available) -->
                    @if($dbiRequest->sql_logs_info_prod)
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-4">
                            <h3 class="text-lg font-bold mb-4">{{ __('Production Log') }}</h3>
                            <textarea class="form-control" rows="10" readonly>{{ $dbiRequest->sql_logs_info_prod }}</textarea>
                        </div>
                    @endif

                    <!-- Status Information -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-4">
                        <h3 class="text-lg font-bold mb-4">{{ __('Status Information') }}</h3>
                        @if($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                            <p>{{ __('Request submitted to SDE') }}: {{ $dbiRequest->operator->user_firstname }} {{ $dbiRequest->operator->user_lastname }}</p>
                        @elseif($dbiRequest->dbiRequestStatus->request_status == 2 && $dbiRequest->dbiRequestStatus->operator_status == 2 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                            <p>{{ __('Request rejected by SDE') }}: {{ $dbiRequest->operator->user_firstname }} {{ $dbiRequest->operator->user_lastname }}</p>
                            @if($dbiRequest->operatorComments->isNotEmpty())
                                <div class="mt-4">
                                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mt-4">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">SDE Rejected Comments:</h3>
                                        @if($dbiRequest->operatorComments->isNotEmpty())
                                            <ul class="space-y-2">
                                                @foreach($dbiRequest->operatorComments as $comment)
                                                    <li class="bg-blue-100 dark:bg-blue-900 border-l-4 border-blue-500 text-blue-700 dark:text-blue-200 p-4 rounded">
                                                        <p class="font-medium">{{ $comment->comment }}</p>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-gray-600 dark:text-gray-400 italic">No comments available.</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <br><br>
                            @if($dbiRequest->requestor_id == Auth::user()->id)
                            @can('update', $dbiRequest)
                            <a href="{{ route('dbi.edit', $dbiRequest->id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                            @endcan
                            @endif
                        @elseif($dbiRequest->pre_execution == 1 && $dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                            <p>{{ __('Request Approved by SDE') }}: {{ $dbiRequest->operator->user_firstname }} {{ $dbiRequest->operator->user_lastname }}</p>
                        @elseif($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 1)
                            <p>{{ __('Request Approved by DAT') }}</p>
                        @elseif($dbiRequest->dbiRequestStatus->request_status == 2 && $dbiRequest->dbiRequestStatus->operator_status == 2 && $dbiRequest->dbiRequestStatus->dat_status == 2)
                            <p>{{ __('Request rejected by DAT') }}</p>
                            @if($dbiRequest->operatorComments->isNotEmpty())
                                <div class="mt-4">
                                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mt-4">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">DAT Rejected Comments:</h3>
                                        @if($dbiRequest->operatorComments->isNotEmpty())
                                            <ul class="space-y-2">
                                                @foreach($dbiRequest->operatorComments as $comment)
                                                    <li class="bg-blue-100 dark:bg-blue-900 border-l-4 border-blue-500 text-blue-700 dark:text-blue-200 p-4 rounded">
                                                        <p class="font-medium">{{ $comment->comment }}</p>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-gray-600 dark:text-gray-400 italic">No comments available.</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if($dbiRequest->requestor_id == Auth::user()->id)
                            @can('update', $dbiRequest)
                            <a href="{{ route('dbi.edit', $dbiRequest->id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                            @endcan
                            @endif
                        @elseif($dbiRequest->prod_execution == 2 && $dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                            <p>{{ __('Prod Execution Failed') }}</p>
                            @if($dbiRequest->requestor_id == Auth::user()->id)
                                @can('update', $dbiRequest)
                                    <a href="{{ route('dbi.edit', $dbiRequest->id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                                @endcan
                            @endif
                        @elseif($dbiRequest->pre_execution == 1 && $dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                            <p>{{ __('Request is run on Pre-production successfully.') }}</p>
                            
                        @elseif($dbiRequest->pre_execution == 2 && $dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                            <p>{{ __('Pre Execution Failed') }}</p>
                            @if($dbiRequest->requestor_id == Auth::user()->id)
                                @can('update', $dbiRequest)
                                    <a href="{{ route('dbi.edit', $dbiRequest->id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                                @endcan
                            @endif
                        @elseif($dbiRequest->prod_execution == 1 && $dbiRequest->dbiRequestStatus->request_status == 3 && $dbiRequest->dbiRequestStatus->operator_status == 3 && $dbiRequest->dbiRequestStatus->dat_status == 3)
                            <p>{{ __('Request is deploy on production successfully.') }}</p>
                            @else
                        
                        @endif
                    </div>

                    @if($dbiRequest->pre_execution == 1 && $dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0 && Auth::user()->isRequester())
                    <div class="mt-6 flex flex-wrap justify-start gap-4">
                        <form action="{{ route('dbi.submitToSDE', $dbiRequest->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">{{ __('Submit to SDE') }}</button>
                        </form>

                        <!-- Action Buttons -->
                        @can('update', $dbiRequest)
                            <a href="{{ route('dbi.edit', $dbiRequest->id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                        @endcan
                    </div>
                    <br> </br>    
                    
                    
                    @elseif($dbiRequest->pre_execution == 2 && $dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0 && Auth::user()->isRequester())
                        <p>{{ __('Execution is failed for PreProd') }}</p>
                    @endif
                    @if(Auth::user()->isDAT() && $dbiRequest->pre_execution == 1 && $dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                        <form action="{{ route('dbi.datApprovedOrReject', $dbiRequest->id) }}" method="POST" class="mt-4" id="sdeDecisionForm">
                            @csrf
                            <div class="space-y-2">
                                <label class="font-bold">{{ __('Status') }}:</label>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" id="approve" name="approvalorreject" value="approve" class="mr-2" required>
                                    <label for="approve" class="text-gray-700 font-bold">{{ __('Approve') }}</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" id="reject" name="approvalorreject" value="reject" class="mr-2" required>
                                    <label for="reject" class="text-gray-700 font-bold">{{ __('Reject') }}</label>
                                </div>
                            </div><br></br>
                            <div class="mb-4" id="rejectionReasonDiv" style="display: none;">
                                <label class="font-bold">{{ __('Rejection Reasons') }}:</label>
                                <select name="operator_comment[]" id="rejectionReason" class="form-control block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" multiple>
                                    @foreach($rejectionReasons as $reason)
                                        <option value="{{ $reason->name }}">{{ $reason->name }}</option>
                                    @endforeach
                                </select>
                            </div><br></br>
                            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                        </form>
                    
                    @endif
                    @if(Auth::user()->isSDE() && $dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0)
                        <form action="{{ route('dbi.sdeApprovedOrReject', $dbiRequest->id) }}" method="POST" class="mt-4" id="sdeDecisionForm">
                            @csrf
                            <div class="space-y-2">
                                <label class="font-bold">{{ __('Status') }}:</label>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" id="approve" name="approvalorreject" value="approve" class="mr-2" required>
                                    <label for="approve" class="text-gray-700 font-bold">{{ __('Approve') }}</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" id="reject" name="approvalorreject" value="reject" class="mr-2" required>
                                    <label for="reject" class="text-gray-700 font-bold">{{ __('Reject') }}</label>
                                </div>
                            </div><br></br>
                            <div class="mb-4" id="rejectionReasonDiv" style="display: none;">
                                <label class="font-bold">{{ __('Rejection Reasons') }}:</label>
                                <select name="operator_comment[]" id="rejectionReason" class="form-control block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" multiple>
                                    @foreach($rejectionReasons as $reason)
                                        <option value="{{ $reason->name }}">{{ $reason->name }}</option>
                                    @endforeach
                                </select>
                            </div><br></br>
                            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                        </form>
                    @endif

                    @if($dbiRequest->dbiRequestStatus->request_status == 1 && $dbiRequest->dbiRequestStatus->operator_status == 1 && $dbiRequest->dbiRequestStatus->dat_status == 1 && Auth::user()->id == $dbiRequest->requestor_id)
                        <form action="{{ route('dbi.testDbi', $dbiRequest->id) }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="prodTest" value="Yes">
                            <button type="submit" class="btn btn-primary">{{ __('Production Run') }}</button>
                        </form>
                    @endif

                    @if(Auth::user()->isDAT() && $dbiRequest->dbiRequestStatus->request_status == 0 && $dbiRequest->dbiRequestStatus->operator_status == 0 && $dbiRequest->dbiRequestStatus->dat_status == 0 && Auth::user()->id == $dbiRequest->requestor_id && Auth::user()->id == $dbiRequest->operator_id)
                        <form action="{{ route('dbi.testDbi', $dbiRequest->id) }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="prodTest" value="Yes">
                            <button type="submit" class="btn btn-primary">{{ __('Production Run') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
console.log('Script tag found');
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');

    var approveRadio = document.getElementById('approve');
    var rejectRadio = document.getElementById('reject');
    var rejectionReasonDiv = document.getElementById('rejectionReasonDiv');

    console.log('Approve radio:', approveRadio);
    console.log('Reject radio:', rejectRadio);
    console.log('Rejection reason div:', rejectionReasonDiv);

    function toggleRejectionReason() {
        console.log('Toggle function called');
        if (rejectionReasonDiv) {
            rejectionReasonDiv.style.display = rejectRadio.checked ? 'block' : 'none';
            console.log('Rejection reason div display:', rejectionReasonDiv.style.display);
        }
    }

    if (approveRadio && rejectRadio) {
        approveRadio.addEventListener('change', function() {
            console.log('Approve selected');
            toggleRejectionReason();
        });

        rejectRadio.addEventListener('change', function() {
            console.log('Reject selected');
            toggleRejectionReason();
        });

        console.log('Event listeners added');
    } else {
        console.log('Radio buttons not found');
    }

    // Initial check
    toggleRejectionReason();
});
</script>
</x-app-layout>

<style>
    .bg-blue-100 { background-color: #e6f2ff; }
    .dark .bg-blue-900 { background-color: #1e3a8a; }
    .border-blue-500 { border-color: #3b82f6; }
    .text-blue-700 { color: #1d4ed8; }
    .dark .text-blue-200 { color: #bfdbfe; }
    .text-gray-600 { color: #4b5563; }
    .dark .text-gray-400 { color: #9ca3af; }

    textarea.form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 0.375rem;
        background-color: #f7f7f7;
        color: #333;
    }
    .btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        user-select: none;
        border: 1px solid transparent;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    

    .btn-primary {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #ffffff;
        background-color: #3490dc;
        border: 1px solid #3490dc;
        border-radius: 0.375rem;
        transition: all 0.15s ease-in-out;
        text-decoration: none;
    }

    .btn-primary:hover {
        background-color: #2779bd;
        border-color: #2779bd;
    }

    .btn-primary:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(52, 144, 220, 0.5);
    }

    @media (max-width: 640px) {
        .btn-primary {
            width: 100%;
            justify-content: center;
            margin-bottom: 0.5rem;
        }
    }

    .form-radio {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        display: inline-block;
        vertical-align: middle;
        background-origin: border-box;
        user-select: none;
        flex-shrink: 0;
        border-radius: 100%;
        border-width: 2px;
    }

    .form-radio:checked {
        background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e");
        border-color: transparent;
        background-color: currentColor;
        background-size: 100% 100%;
        background-position: center;
        background-repeat: no-repeat;
    }

    @media not print {
        .form-radio::-ms-check {
            border-width: 1px;
            color: transparent;
            background: inherit;
            border-color: inherit;
            border-radius: inherit;
        }
    }

    .form-radio:focus {
        outline: none;
    }

    .form-radio:focus-visible {
        outline: 2px solid transparent;
        outline-offset: 2px;
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        --tw-ring-opacity: 0.75;
        --tw-ring-color: rgb(59 130 246 / var(--tw-ring-opacity));
    }
</style>