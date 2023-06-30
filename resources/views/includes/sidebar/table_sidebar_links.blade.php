<li>
    <a href="{{route('cost')}}">
        <div class="parent-icon"><i class="bx bx-cookie"></i>
        </div>
        <div class="menu-title">Счет</div>
    </a>
</li>

<li>
    <a class="has-arrow" href="javascript:;">
        <div class="parent-icon"><i class="bx bx-dock-left"></i>
        </div>
        <div class="menu-title">Ответственные</div>
    </a>
    <ul class="inner">
        <li><a href="{{route('lists.index')}}"><i class="bx bx-right-arrow-alt"></i>Все Списки</a></li>
        @foreach($sidebar_lists as $list)
            <li>
                <a href="{{route('lists.edit',$list->id)}}">
                    <i class="bx bx-list-ul"></i>{{$list->name}}
                </a>
            </li>
        @endforeach
    </ul>
</li>

<li>
    <a href="{{route('costs.edit.items')}}">
        <div class="parent-icon"><i class="bx bx-cookie"></i>
        </div>
        <div class="menu-title">Предметы/Услуги</div>
    </a>
</li>

<li>
    <a href="{{route('outcome.index')}}">
        <div class="parent-icon"><i class="bx bx-calendar"></i>
        </div>
        <div class="menu-title">Итоги</div>
    </a>
</li>

@if(auth()->id() === 1)
    <li>
        <a href="{{route('logistics.index')}}">
            <div class="parent-icon">
                <i class="bx bx-car"></i>
            </div>
            <div class="menu-title">Логистика</div>
        </a>
    </li>
    <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class="bx bx-dock-left"></i>
            </div>
            <div class="menu-title">Сотрудники</div>
        </a>
        <ul class="inner">
            <li><a href="{{route('edit.bases.page')}}"><i class="bx bx-right-arrow-alt"></i>Площадки</a></li>
            <li><a href="{{route('categories.index')}}"><i class="bx bx-right-arrow-alt"></i>Отделы</a></li>
        <!--        <li><a href="{{route('workers.create')}}"><i class="bx bx-right-arrow-alt"></i>Добавить сотрудника</a></li>-->
            <li><a href="{{route('posts.index')}}"><i class="bx bx-right-arrow-alt"></i>Должности</a></li>
            <li><a href="{{route('salary.index')}}"><i class="bx bx-right-arrow-alt"></i>Зарплаты</a></li>

        </ul>
    </li>
@endif



