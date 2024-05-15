<!-- <div class="mb-3"> -->
    @if($title)
    <label class="form-label">{{$title}}</label>
    @endif
    <input type="file" name="{{$name}}" class="@error($name) is-invalid @enderror form-control {{$class}}" @if($enable) disabled @endif>
    @if($default && $default instanceof \Architecture\Domain\ValueObject\File)
        <div class="form-text">File : <a href="{{$default->getUrl()}}" target="_blank">{{ strlen($default->getFileName())>20? substr($default->getFileName(),0,20):$default->getFileName() }}...{{$default->getExtension()}}</a></div>
    @elseif($default && !($default instanceof \Architecture\Domain\ValueObject\File))
        <div class="form-text">File : {{ strlen($default)>20? substr($default,0,20):$default }}...{{pathinfo($default, PATHINFO_EXTENSION)}}</div>
    @endif

    @error($name)
        <span class="text-danger">{{ $message }}</span>
    @enderror
<!-- </div> -->