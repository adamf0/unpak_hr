@if($title)
    <label class="form-label">{{$title}}</label>
@endif
<select name="{{$name}}" class="@error($name) is-invalid @enderror form-control {{$class}}" @if($disable) disabled @endif></select>
<!-- <div class="form-text">We'll never share your email with anyone else.</div> -->
@error($name)
<span class="text-danger">{{ $message }}</span>
@enderror