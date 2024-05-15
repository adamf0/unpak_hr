<div class="accordion-item">
    <h2 class="accordion-header" id="{{$group}}">
        <button class="accordion-button @if(!$collapse) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#{{$group}}C" aria-expanded="{{!$collapse?'false':'true'}}" style="padding: 15px !important">
            {{ $title }}
        </button>
    </h2>
    <div id="{{$group}}C" class="offset-x accordion-collapse @if(!$collapse) collapse @else collapse show @endif" aria-labelledby="{{$group}}" data-bs-parent="#indikator">
        <div class="accordion-body">
            {{ $slot }}
        </div>
    </div>
</div>