<li>
    <a class="has-arrow" href="javascript:;">
        <div class="parent-icon"><i class="bx bx-grid-alt"></i>
        </div>
        <div class="menu-title">Склады</div>
    </a>
    <ul class="inner">
        <li><a href="{{route('storage.index')}}"><i class="bx bx-right-arrow-alt"></i>Все Склады</a></li>
        @foreach($storages as $storage)
            <li>
                <a href="{{route('storage.show',$storage->id)}}">
                    <div class="parent-icon"><i class="bx bx-notepad"></i>
                    </div>
                    <div class="menu-title">{{$storage->title}}</div>
                </a>

            </li>
        @endforeach

    </ul>
</li>

