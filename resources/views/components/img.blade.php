<img 
src="{{ $path }}" 
alt="{{ $alt }}" 
@if($error) onerror="this.onerror=null;this.src='{{ $error }}';"@endif 
@if($class) class="{{ $class }}"@endif 
@if($id) id="{{ $id }}"@endif 
@if($islazy) loading="lazy"@endif
>