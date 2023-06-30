@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <a href="{{route('storage.show',$storage->id)}}"><div class="breadcrumb-title should_opac pe-3">{{$storage->title}}</div></a>

                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{route('storage.index')}}"><i class="bx bx-grid-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Подсчеты</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="my-3">История Действий</h5>
                        <div class="ms-auto navig_buttons">
                            <a href="{{route('storage.show',$storage->id)}}" class="btn btn-sm btn-light">Склад<i class="bx bx-grid me-0"></i></a>
                            <a href="{{route('storage.calculate',$storage->id)}}" class="btn btn-sm btn-light">Подсчеты <i class="bx bx-arrow-to-right me-0"></i></a>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex mt-2 ms-2 filter_formcheck">
                            <div class="form-check">
                                <input class="form-check-input set_history_type_filter" type="radio" value="all" name="history_type_filter" id="set_filter_all" @if(session('history') === 'all' || !session('history')) checked @endif>
                                <label class="form-check-label" for="set_filter_all">Все <i class="bx bx-transfer-alt "></i> </label>
                            </div>
                            <div class="form-check ms-3">
                                <input class="form-check-input set_history_type_filter" type="radio" value="deduct" name="history_type_filter" id="set_filter_ded" @if(session('history') === 'deduction') checked @endif>
                                <label class="form-check-label" for="set_filter_ded">Вычеты <i class="bx bx-downvote vichet_arr"></i> </label>
                            </div>
                            <div class="form-check ms-3">
                                <input class="form-check-input set_history_type_filter" type="radio" value="add" name="history_type_filter" id="set_filter_add" @if(session('history') === 'addition') checked @endif>
                                <label class="form-check-label" for="set_filter_add">Добавления <i class="bx bx-upvote dobav_arr"></i></label>
                            </div>
                        </div>
                        <div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col position-relative">
                            <div class="history_table_titles_absolute d-flex" id="hajime">
                                <div>#</div>
                                <div>Тип</div>
                                <div>Предмет</div>
                                <div>Кол</div>
                                <div>Цена</div>
                                @if(session('history') !== 'addition') <div>Кому</div> @endif
                                <div>Дата</div>
                                @if(session('history') !== 'addition') <div>Коммент.</div> @endif

                            </div>
                            <div class="table-responsive mt-3 history_table   beautiful_overflow px-2">

                                <table class="table table-striped table-hover table-sm mb-0">

                                    <thead>
                                    <tr class="header_table">
                                        <th>#</th>
                                        <th>Тип</th>
                                        <th>Предмет</th>
                                        <th>Кол</th>
                                        <th>Цена</th>
                                        @if(session('history') !== 'addition') <th>Кому</th> @endif

                                        <th>Дата</th>
                                        @if(session('history') !== 'addition') <th>Коммент.</th> @endif


                                    </tr>
                                    </thead>
                                    <tbody class="tbody_body">
                                    @foreach($history as $action)
                                        <tr class="@if($action->type === 'addition') addition @else deduction @endif" >
                                            <td>{{$loop->index + $history->firstItem()}}</td>
                                            <td class="type">
                                                @if($action->type === 'addition')
                                                    Добавление
                                                    <i class="bx bx-upvote should_show_on_hover"></i>
                                                    <i class="bx bx-up-arrow-alt "></i>
                                                @else
                                                    Вычет <i class="bx bx-downvote should_show_on_hover"></i>
                                                    <i class="bx bx-down-arrow-alt "></i>
                                                @endif</td>
                                            <td >{{$action->item->name}}</td>
                                            <td >{{$action->count}}</td>
                                            <td >{{$action->item->price}}</td>
                                            @if(session('history') !== 'addition') <td >{{$action->to}}</td> @endif
                                            <td >{{\Carbon\Carbon::parse($action->date)->format('d/m/Y')}}</td>
                                            @if(session('history') !== 'addition') <td  data-handler="comment" data-val="{{$action->comment}}">{{$action->comment}}</td> @endif

                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                                <div class="no_info_place">

                                </div>
                            </div>
                            <div class="mt-3">
                                {{$history->links("pagination::bootstrap-4")}}
                            </div>

                        </div>

                    </div>
                    <input type="hidden" id="stor_id" value="{{$storage->id}}">
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('user_assets/plugins/chartjs/js/Chart.min.js')}}"></script>
    <script>
        $(document).ready(function (){
            let hajime = $('#hajime');
            $('.header_table th').each(function (index,value){
                $('.history_table_titles_absolute div').eq(index).width($(value).width()+ 1)
            })
            $('.history_table').on('scroll',function (){
                if($(this).scrollTop() > 15){
                    hajime.addClass('active')
                }else{
                    hajime.removeClass('active')
                }

            })

            $('.set_history_type_filter').change(function (){
                let val = $(this).val();
                $.ajax({
                    type: "GET",
                    dataType : "json",
                    url: "{{route('history.set.filter')}}",
                    data: {
                        filter:val
                    },
                    success: function(data){
                        if(data.success === 'success'){
                            window.location.reload()
                        }

                    },
                    error:function (err){

                    }
                });
            })
        })
    </script>
    <div class="overlay toggle-icon"></div>
    <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

@endsection
