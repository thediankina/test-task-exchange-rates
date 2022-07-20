<div class="widget">
    @foreach($rates as $rate)
        <div class="rate">
            {{ $rate->char_code }}
            @foreach($rate->values as $value)
                {{ $value->value }}
            @endforeach
        </div>
    @endforeach
</div>
