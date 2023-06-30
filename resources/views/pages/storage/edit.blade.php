@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <a href="{{route('storage.show',$deduction->storage->id)}}"><div class="breadcrumb-title should_opac pe-3">{{$deduction->storage->title}}</div></a>

                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{route('storage.index')}}"><i class="bx bx-grid-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('storage.index')}}">Вычеты</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Редактирование</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="row">
                <div class="col-12 col-lg-3">
                    <div class="card storage_adding_card">
                        <div class="card-body">
                            <h5 class="my-3">Вычет</h5>
                            <form action="{{route('update.deduct')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" value="{{$deduction->id}}" name="deduct_id">
                                </div>
                                <div class="form-group col-xl-4 col-lg-6 col-md-6">
                                    <label for="" class="form-label">Предмет</label>
                                    <h6>{{$deduction->item->name}}</h6>
                                </div>
                                <div class="form-group mt-3">
                                    <label class="form-label" for="count">Количество</label>
                                    <input required value="{{$deduction->count}}" type="number"  class="form-control" name="count" placeholder="введите количество">
                                </div>
                                <div class="form-group mt-3">
                                    <label class="form-label" for="count">Дата</label>
                                    <input required value="{{\Carbon\Carbon::parse($deduction->date)->format('d/m/Y')}}" type="datetime"  class="form-control datepicker" name="date" placeholder="выберите дату">
                                </div>
                                <div class="form-group mt-3">
                                    <label class="form-label" for="count">Кому</label>
                                    <input required value="{{$deduction->to}}" type="text"  class="form-control" name="to" placeholder="напишите имя">
                                </div>
                                <div class="form-group mt-3">
                                    <label class="form-label" for="count">Комментарий</label>
                                    <textarea class="form-control" name="comment" type="text" placeholder="комментарий...">{{$deduction->comment}}</textarea>
                                </div>
                                <div class="d-grid mt-3"> <button type="submit" class="btn btn-light">Сохранить <i class="bx bx-save me-0"></i> </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
            <!--end row-->
        </div>

    </div>
    <script>
        $(document).ready(function (){

        })

    </script>

    <div class="overlay toggle-icon"></div>
    <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

@endsection
