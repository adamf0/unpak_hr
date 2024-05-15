<div class="mb-3">
    @if($title)
    <label class="form-label">{{$title}}</label>
    @endif
    <textarea name="{{$name}}" class="@error($name) is-invalid @enderror form-control {{$class}}" cols="30" rows="10">@if($default) {{old($name,$default)}}@else{{old($name)}}@endif</textarea>
    @error($name)
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>