<div class="widget" style="width: {{ $size }}">
    <div class="frame">
        @foreach($rates as $rate)
            <div class="row">
                <div class="cell">
                    <div class="rate">
                        {{ $rate->nominal }}&nbsp;{{ $rate->char_code }}<br>
                        <div class="name">{{ $rate->name }}</div>
                    </div>
                </div>
                <div class="cell">
                    {{ $rate->values->last }}
                </div>
                <div class="cell">
                    <div class="changes">
                        @if($rate->values->increasing)
                            <div style="color: darkgreen">&uarr;</div>
                        @else
                            @if(is_null($rate->values->increasing))
                                &nbsp;
                            @else
                                <div style="color: darkred">&darr;</div>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="cell">
                    <div class="difference">
                        {{ $rate->values->difference }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
