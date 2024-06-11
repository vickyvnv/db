<div class="sidebar">
    <ul class="sidebar-menu">
        <li>
            <a href="#" class="sidebar-link">
                <i class="fas fa-users"></i>
                <span>User</span>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('users.index') }}">User List</a></li>
                <li><a href="{{ route('users.create') }}">Create New User</a></li>
            </ul>
        </li>
        <!-- <li>
            <a href="#" class="sidebar-link">
                <i class="fas fa-compass"></i>
                <span>Navigation</span>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Main Menu or Application</a></li>
                <li><a href="#">Submenus</a></li>
            </ul>
        </li> -->
        <li>
            <a href="#" class="sidebar-link">
                <i class="fas fa-users-cog"></i>
                <span>Teams</span>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('teams.index') }}">Teams List</a></li>
            </ul>
        </li>
        <li>
            <a href="#" class="sidebar-link">
                <i class="fas fa-user-shield"></i>
                <span>Rights and Roles</span>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('rights.index') }}">Rights</a></li>
                <li><a href="{{ route('roles.index') }}">Role</a></li>
            </ul>
        </li>
        <li>
            <a href="#" class="sidebar-link">
                <i class="fas fa-cogs"></i>
                <span>DB and Market</span>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('database-info.index') }}">DB Info</a></li>
                <li><a href="{{ route('markets.index') }}">Markets</a></li>
                <li><a href="{{ route('db-instances.index') }}">Database List</a></li>
            </ul>
        </li>
    </ul>
</div>

<style>
    .sidebar {
        width: 250px;
        background-color: #2c3e50;
        padding: 20px;
        float: left;
        overflow-y: auto;
        height: 1000px;
    }

    .sidebar-menu {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu > li {
        margin-bottom: 10px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        text-decoration: none;
        color: #ecf0f1;
        transition: background-color 0.3s;
        border-radius: 4px;
    }

    .sidebar-link:hover {
        background-color: #34495e;
    }

    .sidebar-link i {
        margin-right: 10px;
    }

    .submenu {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: none;
    }

    .submenu li {
        margin-left: 30px;
    }

    .submenu li a {
        display: block;
        padding: 8px 15px;
        color: #bdc3c7;
        text-decoration: none;
        transition: color 0.3s;
    }

    .submenu li a:hover {
        color: #ecf0f1;
    }

    .dropdown-icon {
        margin-left: auto;
        transition: transform 0.3s;
    }

    .sidebar-menu > li:hover .submenu {
        display: block;
    }

    .sidebar-menu > li:hover .dropdown-icon {
        transform: rotate(180deg);
    }
</style>

<!-- Add Font Awesome library for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">