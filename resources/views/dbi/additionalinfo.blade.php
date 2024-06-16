<x-app-layout>
    <div class="flex">
        <!-- Sidebar -->
        @include('partials.dbi-sidebar')

        <!-- Main Content -->
        <div class="w-3/4 p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="container">
                            <div class="row justify-content-center">
                                <button class="btn btn-primary mb-4"><a href="{{ route('dbi.index') }}" class="btn btn-primary">Back</a></button>
                                <div class="col-md-12">
                                    <div class="card-body">
                                        <div class="card-header mb-4">Additional Information</div>
                                        <p class="mb-4">DBI ID: {{ $dbiRequest->id }}</p>

                                        <form id="temporaryTableForm" action="{{ route('dbi.storeTemporaryTable', $dbiRequest->id) }}" method="POST">
                                            @csrf
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Instance</th>
                                                        <th>User</th>
                                                        <th>Table</th>
                                                        <th>Type</th>
                                                        <th>Drop Date</th>
                                                        <th>SQL</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tableBody">
                                                    <tr>
                                                        <td>
                                                            <select name="instance[]" class="form-control">
                                                                <option value="{{ $dbiRequest->db_instance }}">{{ $dbiRequest->db_instance }}</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="user[]" class="form-control">
                                                                <option value="{{ $dbiRequest->db_user }}">{{ $dbiRequest->db_user }}</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="table[]" placeholder="Enter table name" class="form-control">
                                                        </td>
                                                        <td>
                                                            <select name="type[]" class="form-control">
                                                                <option value="regular">Regular</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="date" name="drop_date[]" value="{{ now()->addDays(2)->format('Y-m-d') }}" class="form-control">
                                                        </td>
                                                        <td>
                                                            <textarea name="sql[]" rows="4" class="form-control" required></textarea>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="form-group">
                                                <button type="button" id="addLine" class="btn btn-primary mb-4">Add New Line</button>
                                                <button type="submit" class="btn btn-success mb-4">Save</button>
                                            </div>
                                        </form>
                                        <form action="{{ route('dbi.testDBI', $dbiRequest->id) }}" method="GET">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Next</button>
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
</x-app-layout>

<script>
    document.getElementById('addLine').addEventListener('click', function() {
        var tableBody = document.getElementById('tableBody');
        var newRow = tableBody.insertRow();

        newRow.innerHTML = `
            <td>
                <select name="instance[]" class="form-control">
                    <option value="{{ $dbiRequest->db_instance }}">{{ $dbiRequest->db_instance }}</option>
                </select>
            </td>
            <td>
                <select name="user[]" class="form-control">
                    <option value="{{ $dbiRequest->db_user }}">{{ $dbiRequest->db_user }}</option>
                </select>
            </td>
            <td>
                <input type="text" name="table[]" placeholder="Enter table name" class="form-control">
            </td>
            <td>
                <select name="type[]" class="form-control">
                    <option value="regular">Regular</option>
                </select>
            </td>
            <td>
                <input type="date" name="drop_date[]" value="{{ now()->addDays(2)->format('Y-m-d') }}" class="form-control">
            </td>
            <td>
                <textarea name="sql[]" rows="4" class="form-control"></textarea>
            </td>
        `;
    });
</script>

<style>
    .btn {
        margin-right: 10px;
    }

    .table {
        margin-top: 20px;
    }

    .form-control {
        width: 100%;
    }

    .card-header {
        font-weight: bold;
    }

    .card-body {
        padding: 20px;
    }
</style>