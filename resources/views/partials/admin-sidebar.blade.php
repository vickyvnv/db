<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="#">User</a>
            <ul> <!-- Sublist -->
                <li><a href="{{ route('users.index') }}">User List</a></li>
                <li><a href="{{ route('users.create') }}">Create New User</a></li>
            </ul>
        </li>
        <li><a href="#">Navigation</a>
            <ul> <!-- Sublist -->
                <li><a href="#">Main Menu or Application</a></li>
                <li><a href="#">Submenus</a></li>
            </ul>
        </li>
        <li><a href="#">Teams</a>
            <ul> <!-- Sublist -->
                <li><a href="{{ route('teams.index') }}">Teams List</a></li>
            </ul>
        </li>
        <li><a href="#">Rights and Roles</a>
            <ul> <!-- Sublist -->
                <li><a href="{{ route('rights.index') }}">Rights</a></li>
                <li><a href="{{ route('roles.index') }}">Role</a></li>
            </ul>
        </li>
    </ul>
</div>
