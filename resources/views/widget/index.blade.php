<div class="widget">
    @foreach($rates as $rate)
        <p>
            <div class="cell">
                <div class="name">
                    {{ $rate->nominal }}&nbsp;{{ $rate->char_code }}<br>
                    {{ $rate->name }}
                </div>
            </div>
            <div class="cell">{{ $rate->values->last }}</div>
            <div class="cell">
                @if($rate->values->increasing)
                    &uarr;
                @else
                    {{ html_entity_decode(is_null($rate->values->increasing) ? '&ndash;' : '&darr;') }}
                @endif
            </div>
            <div class="cell">{{ $rate->values->difference }}</div>
        <p>
    @endforeach
</div>
