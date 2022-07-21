<div class="widget">
    @foreach($rates as $rate)
        <div class="rate">
            <div class="code">
                {{ $rate->char_code }}
            </div>
            <div class="value">
                {{ $rate->values->last }}
                <div class="status">
                    @if($rate->values->increasing)
                        &uarr;
                    @else
                        &darr;
                    @endif
                    {{ $rate->values->difference }}
                </div>
            </div>
        </div>
    @endforeach
</div>
