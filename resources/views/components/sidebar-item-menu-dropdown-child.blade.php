<li class="nav-item">
    <a class="nav-link @if(!$active) collapsed @endif" href="{{ $link }}">
        <i class="{{ $icon }} @if($class) {{$class}} @endif"></i><span>{{ $title }}</span>
    </a>
</li>