<li class="nav-item dropdown">
    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-bell"></i>
        <span class="badge bg-primary badge-number">{{ count($datas) }}</span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
        <x-top-nav-menu-dropdown-child-header title="You have {{ count($datas) }} new notifications" desc="View All"></x-top-nav-menu-dropdown-child-header>
        <x-top-nav-menu-dropdown-child icon="bi bi-exclamation-circle" class="text-warning">
            <h4>Lorem Ipsum</h4>
            <p>Quae dolorem earum veritatis oditseno</p>
            <p>30 min. ago</p>
        </x-top-nav-menu-dropdown-child>
        <x-top-nav-menu-dropdown-child icon="bi bi-x-circle text-danger">
            <h4>Lorem Ipsum</h4>
            <p>Quae dolorem earum veritatis oditseno</p>
            <p>30 min. ago</p>
        </x-top-nav-menu-dropdown-child>
        <li class="dropdown-footer">
           <a href="#">Show all notifications</a>
        </li>
    </ul>
</li>