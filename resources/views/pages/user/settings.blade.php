@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Профиль</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{route('index')}}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Настройки аккаунта</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="container">
                <div class="main-body">
                    @include('includes.user.userSetting')
                </div>
            </div>
        </div>
    </div>
@endsection
