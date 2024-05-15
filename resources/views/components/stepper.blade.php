<div class="nav circle-tab-container mb-3" id="pills-tab" role="tablist">
    @php
        $countList = $listItem->count();
    @endphp
    @foreach ($listItem as $item)
        <x-stepper-item :key="$loop->index+1" :isActive="$item->isActive" :isDisable="$item->isDisable" :isEndStep="$loop->index!=$countList-1" numberStep="{{$loop->index+1}}" />
    @endforeach
</div>