@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
                <div class="breadcrumb-title pe-3">Позиции</div>
                <a role="button" class="btn btn-light add_period_modal_opener" data-bs-toggle="modal"
                   data-bs-target="#listModal"><i class="lni m-0 lni-plus"></i> <span class="align-middle">Добавить Список</span></a>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 ">
                @foreach($bases as $base)
                    <div class="col">
                        <a href="{{route('lists.edit',$base->id)}}">
                            <div class="card list_card radius-10">
                                <div class="card-body">
                                    <div class="card-title">
                                        <div>
                                            <h5>{{$base->name}}</h5>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap">
                                        @foreach($base->someBases as $item)
                                            <div class="index_pos_item">
                                                <p><span>{{$item->name}}</span></p>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>

                            </div>
                        </a>

                    </div>
                @endforeach


            </div>

        </div>
    </div>


@endsection
