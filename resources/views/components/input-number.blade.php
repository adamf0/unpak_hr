<!-- <div class="mb-3"> -->
    @if($title)
    <label class="form-label">{{$title}}</label>
    @endif
    <input type="number" name="{{$name}}" class="@error($name) is-invalid @enderror form-control {{$class}}" value="{{old($name,$default)}}" @if($min!=-1) min="{{$min}}" @endif @if($max!=-1) max="{{$max}}" @endif @if($disable) disabled @endif>
    <!-- <div class="form-text">We'll never share your email with anyone else.</div> -->
    @error($name)
    <span class="text-danger">{{ $message }}</span>
    @enderror
<!-- </div> -->