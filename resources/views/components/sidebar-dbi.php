<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="{{ $link1 }}">DBI Home</a></li>
        <li><a href="{{ $link2 }}">Change Role</a></li>
        <li><a href="{{ $link3 }}">Search DBI</a></li>
        <li><a href="{{ $link4 }}">List My DBI</a></li>
        <li><a href="{{ $link5 }}">New DBI</a></li>
        <li><a href="{{ $link5 }}">Cleanup</a></li>
        <li><a href="{{ $link5 }}">Documentation</a></li>
        <!-- Add more sidebar links here -->
    </ul>
</div>
<style>.sidebar {
    width: 250px; /* Adjust width as needed */
    height: 100%;
    background-color: #f4f4f4;
    padding: 20px;
}

.sidebar-menu {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.sidebar-menu li {
    margin-bottom: 10px;
}

.sidebar-menu li a {
    display: block;
    padding: 10px 15px;
    text-decoration: none;
    color: #333;
    transition: background-color 0.3s;
}

.sidebar-menu li a:hover {
    background-color: #ddd;
}
</style>