<span class="should_money @if($cost->cashless === '1') text-success @elseif($cost->cashless === '2') text-warning @endif" data-money-index="{{$cost->id}}">
    {{$cost->value}}
</span>
