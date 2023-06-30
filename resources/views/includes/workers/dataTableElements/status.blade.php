@if($status->id === 1)
    <span style="color: #00da11f0">
            работает
        </span>
@elseif($status->id === 2)
    <span style="color: #ff9900">
            в отпуске
        </span>
@else
    <span style="color: rgba(234,6,32,0.94)">
            уволен
        </span>

@endif


