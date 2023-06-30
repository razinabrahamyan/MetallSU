<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand">
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
            </div>
            <div class="search-bar flex-grow-1">
                <div class="position-relative search-bar-box" id="main_search_div">
                    <input type="text" class="form-control search-control" placeholder="Поиск..." id="main_search_input"> <span
                        class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                    <span class="position-absolute top-50 search-close translate-middle-y"><i
                            class='bx bx-x'></i></span>

                    <div class="beautiful_overflow search_results_field animate__animated" id="search_result_div">

                    </div>
                </div>
            </div>
            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item mobile-search-icon">
                        <a class="nav-link" href="#">	<i class='bx bx-search'></i>
                        </a>
                    </li>

                </ul>
            </div>
            <div class="user-box dropdown">
                <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                   role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{asset('storage/user/images/avatar/'.auth()->user()->avatar)}}" class="user-img"
                         alt="user avatar">
                    <div class="user-info ps-3">
                        <p class="user-name mb-0">{{auth()->user()->first_name.' '.auth()->user()->last_name}}</p>

                    </div>
                </a>
                @auth()
                    <ul class="dropdown-menu dropdown-menu-end">

                        @if(auth()->user()->role_id == 1)
                            <li>
                                <a class="dropdown-item" href="{{route('user.new')}}">
                                    <i class="bx bx-user-plus"></i>
                                    <span>Добавить пользователя</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{route('user.list')}}">
                                    <i class="lni lni-users"></i>
                                    <span>Список всех пользователей</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a class="dropdown-item" href="{{route('user.settings',['id',auth()->user()->id])}}">
                                <i class="bx bx-cog"></i>
                                <span>Настройки аккаунта</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider mb-0"></div>
                        </li>
                        <li>
                            <form action="{{route('logout')}}" method="post">
                                @csrf
                                <button class="dropdown-item logout_button" type="submit">
                                    <i class='bx bx-log-out-circle'></i>
                                    <span>Выход</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                @endauth
            </div>
        </nav>
    </div>
</header>
<div class="height_content"></div>
{{--@if(auth()->user()->role_id === 1)
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex year_buttons">

                @foreach($periods as $period)
                    <a class="btn btn-light @if($period->status == '1') active_year @endif" href="{{route('set.period',$period->id)}}">{{$period->year}}</a>
                @endforeach

                <a role="button" class="btn btn-light add_period_modal_opener" data-bs-toggle="modal"
                   data-bs-target="#addPeriodModal"><i class="lni m-0 lni-plus"></i> <span class="align-middle">Начать новый период</span></a>

            </div>
        </div>
    </div>
@endif--}}
{{--@include('includes.modals.position_modal')--}}
@include('includes.modals.list_modal')
