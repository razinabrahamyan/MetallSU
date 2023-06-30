<div class="wrapper ">
    <div class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div>
                <img src="{{asset('user_assets/images/logo-icon.png')}}" class="logo-icon" alt="logo icon">
            </div>
            <div>
                <a href="{{route('index')}}">
                    <h4 class="logo-text">Металлолом</h4>                </a>
            </div>
            <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
            </div>
        </div>
        <ul class="metismenu" id="menu">
            @if(auth()->user()->role_id === 1)
                @include('includes.sidebar.table_sidebar_links')
            @else
                @include('includes.sidebar.storage_sidebar_links')
            @endif

        </ul>

        <div class="position-absolute bottom-0 start-0 end-0 m-auto text-center mb-3">
            <button id="pwa-install" class="btn btn-success d-none">
                Установить <br class="d-block d-md-none"> приложение
            </button>
        </div>
    </div>
</div>
