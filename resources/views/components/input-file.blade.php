<!-- <div class="mb-3"> -->
    @if($title)
    <label class="form-label">{{$title}}</label>
    @endif
    <input type="file" name="{{$name}}" class="@error($name) is-invalid @enderror form-control {{$class}}" @if($accept) accept="{{$accept}}" @endif @if($enable) disabled @endif @if($multi) multiple @endif>
    @if($default && $default instanceof \Illuminate\Support\Collection)
        @foreach ($default as $d)
            @if($d && $d instanceof \Architecture\Domain\ValueObject\File)
                <div class="form-text">File : <a href="{{$d->getUrl()}}" target="_blank">{{ strlen($d->getFileName())>20? substr($d->getFileName(),0,20):$d->getFileName() }}...{{$d->getExtension()}}</a></div>
            @elseif($d && !($d instanceof \Architecture\Domain\ValueObject\File))
                <div class="form-text">File : {{ strlen($d)>20? substr($d,0,20):$d }}...{{pathinfo($d, PATHINFO_EXTENSION)}}</div>
            @endif    
        @endforeach
    @elseif($default && $default instanceof \Architecture\Domain\ValueObject\File)
        <div class="form-text">File : <a href="{{$default->getUrl()}}" target="_blank">{{ strlen($default->getFileName())>20? substr($default->getFileName(),0,20):$default->getFileName() }}...{{$default->getExtension()}}</a></div>
    @elseif($default && !($default instanceof \Architecture\Domain\ValueObject\File))
        <div class="form-text">File : {{ strlen($default)>20? substr($default,0,20):$default }}...{{pathinfo($default, PATHINFO_EXTENSION)}}</div>
    @endif

    @error($name)
        <span class="text-danger">{{ $message }}</span><br>
    @enderror
<!-- </div> -->