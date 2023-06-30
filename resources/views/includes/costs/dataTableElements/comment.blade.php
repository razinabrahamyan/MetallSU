<div class="position-relative">
    {{Illuminate\Support\Str::limit($cost->comment, 50, ' ...')}}
    @if($cost->comment)
        <div class="table_comment_desc right">
            <div class="inner">
                <div>
                    <span>{{$cost->comment}}</span>
                </div>
            </div>
        </div>
    @endif

</div>
