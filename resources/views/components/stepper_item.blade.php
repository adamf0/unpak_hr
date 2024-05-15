<button class="nav-link circle-tab {{$isActive? 'active':''}}" id="step{{$key}}-tab" data-bs-toggle="pill" data-bs-target="#step{{$key}}" type="button" role="tab" aria-controls="step{{$key}}" aria-selected="{{$isActive?'true':'false'}}" @if($isDisable) disabled @endif>{{$numberStep}}</button>
@if($isEndStep)
<div class="line"></div>
@endif