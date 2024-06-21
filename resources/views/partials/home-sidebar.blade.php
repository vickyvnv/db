<div class="sidebar">
    <ul class="sidebar-menu">
        <li>
            <a href="#" class="sidebar-link">
                <i class="fas fa-users"></i>
                <span>DBI</span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('dbi.index') }}">DBI List</a></li>
                @if(Auth::user()->userRoles->first()->name == 'Requester' || Auth::user()->userRoles->first()->name == 'DAT')
                    <li><a href="{{ route('dbi.create') }}">DBI New User</a></li>
                @endif
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
        height: 1000px;
        overflow-y: auto;
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
</style>
<!-- Add Font Awesome library for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">