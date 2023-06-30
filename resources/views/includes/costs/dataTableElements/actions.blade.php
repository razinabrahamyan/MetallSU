<a href="{{route('edit.cost.page',$cost->id)}}" class="font-18">
    <i class="bx bx-edit"></i>
</a>
@if(!empty($cost->images))
    <a href="javascript: window.costImage.loadCostImages({{$cost->id}});"
       class="font-18 imageLookup">
        <i class="bx bx-image text-white"></i>
    </a>
@endif
