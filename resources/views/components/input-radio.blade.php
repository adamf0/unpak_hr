<!-- <div class="mb-3"> -->
    <div class="form-check">
        <input class="form-check-input" type="radio" name="{{$name}}" id="{{$id}}" value="{{$value}}" @if($selected) checked @endif @if($disable) disabled @endif>
        <label class="form-check-label" for="{{$id}}">
            {{$slot}}
        </label>
        @error($name)
            @if($isend)
                <span class="text-danger">{{ $message }}</span>
            @endif
        @enderror
    </div>
<!-- </div> -->