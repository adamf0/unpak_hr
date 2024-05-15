<li class="notification-item">
    @if($icon)
    <i class="{{ $icon }} @if($class){{$class}}@endif"></i>
    @endif
    <div>
        {{ $slot }}
    </div>
</li>
<li>
    <hr class="dropdown-divider">
</li>