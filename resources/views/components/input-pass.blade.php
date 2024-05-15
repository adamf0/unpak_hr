<!-- <div class="mb-3"> -->
    @if($title)
    <label class="form-label">{{$title}}</label>
    @endif
    @if(!empty($pref) || !empty($suff))<div class="input-group mb-3">@endif
    @if(!empty($pref))<span class="input-group-text">{{$pref}}</span>@endif
    <input type="password" name="{{$name}}" class="@error($name) is-invalid @enderror form-control {{$class}}" value="{{old($name,$default)}}" @if($disable) disabled @endif>
    @if(!empty($suff))<span class="input-group-text">{{$suff}}</span>@endif
    @if(!empty($pref) || !empty($suff))</div>@endif
    {{$slot}}
    @error($name)
    <span class="text-danger">{{ $message }}</span>
    @enderror
<!-- </div> -->