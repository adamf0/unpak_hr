<li class="nav-item">
    <a class="nav-link {{!$active? 'collapsed':''}}" data-bs-target="#{{ $target }}" data-bs-toggle="collapse" href="#" aria-expanded="{{$active? 'true':'false'}}">
        <i class="{{ $icon }} @if($class) {{$class}} @endif"></i><span>{{$title}}</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="{{ $target }}" class="nav-content collapse {{!$active? '':'show'}}" data-bs-parent="#{{$parent}}">
        {{ $slot }}
    </ul>
</li>