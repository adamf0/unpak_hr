@if($show)
<li class="nav-item">
    <a class="nav-link @if(!$active) collapsed @endif" @if($id) id="{{ $id }}" @endif href="{{ $link }}">
        <i class="{{$icon}} @if($class) {{$class}} @endif"></i>
        <span>{{ $title }}</span>
    </a>
</li>
@endif