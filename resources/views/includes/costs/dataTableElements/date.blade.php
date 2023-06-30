<div class="position-relative">
    {{\Carbon\Carbon::parse($cost->date)->format('d.m.Y')}}

    <div class="table_comment_desc ">
        <div class="inner">
            <div>
                <span class="time">{{\Carbon\Carbon::parse($cost->created_at)->format('H:i')}}</span>
            </div>
            <div>
                <span>{{\Carbon\Carbon::parse($cost->created_at)->format('d/m/Y')}}</span>
            </div>
        </div>
    </div>
</div>
