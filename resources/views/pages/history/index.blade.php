@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
                <div class="breadcrumb-title pe-3">История</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-dock-left"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Отправка файлов</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row flex-column row-cols-1 row-cols-xl-2 ">
                @foreach($history as $item)
                    <div class="card mb-2">
                        <div class="card-body">
                            <p class="mb-2">Отправка файла <span class="filename">{{$item->additional['filename']}}</span> по адресу <span class="filename">{{$item->email}}</span></p>
                        </div>
                        <div class="history_created_time">{{$item->created_at}}</div>
                    </div>
                @endforeach


            </div>
        </div>
    </div>
    <div class="overlay toggle-icon"></div>
    <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

@endsection
