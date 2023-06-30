@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <a href="{{route('storage.show',$item->storage->id)}}"><div class="breadcrumb-title should_opac pe-3">{{$item->storage->title}}</div></a>

                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{route('storage.index')}}"><i class="bx bx-grid-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('storage.index')}}">Товары</a>
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
                            <h5 class="my-3">Товар</h5>
                            <form action="{{route('update.storage.item')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" value="{{$item->id}}" name="item_id">
                                </div>

                                <div class="form-group mt-3">
                                    <label class="form-label" for="name">Название</label>
                                    <input required value="{{$item->name}}" type="text"  class="form-control" name="name" placeholder="введите название">
                                </div>

                                <div class="form-group mt-3">
                                    <label class="form-label" for="name">Цена</label>
                                    <input required value="{{$item->price}}" type="number"  class="form-control" name="price" placeholder="введите цену">
                                </div>

                                <div class="form-group mt-3">
                                    <label class="form-label" for="count">Комментарий</label>
                                    <textarea class="form-control" name="comment" type="text" placeholder="комментарий...">{{$item->description}}</textarea>
                                </div>
                                <div class="d-grid mt-3"> <button type="submit" class="btn btn-light">Сохранить</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-9">
                    <div class="card storage_items_card">
                        <div class="card-body">
                            <h5 class="my-3">Добавления</h5>
                            <div class="table-responsive mt-3 deduct_table beautiful_overflow">
                                @if($deductions->count())
                                    <table class="table table-striped table-hover table-sm mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Предмет</th>
                                            <th>Кол</th>
                                            <th>Цена</th>
                                            <th>Дата</th>
                                        </tr>
                                        </thead>
                                        <tbody class="static_body active">
                                        @foreach($deductions as $deduction)
                                            <tr>
                                                <td >{{$loop->index + $deductions->firstItem()}}</td>
                                                <td >{{$deduction->item->name}}</td>
                                                <td >{{$deduction->count}}</td>
                                                <td class="should_money">{{$deduction->price}}</td>
                                                <td >{{\Carbon\Carbon::parse($deduction->date)->format('d/m/Y')}}</td>


                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tbody class="dynamic_body">

                                        </tbody>
                                    </table>

                                    <div class="mt-2">
                                        {{$deductions->links("pagination::bootstrap-4")}}
                                    </div>
                                @else
                                    <p>Нет Вычетов</p>
                                @endif

                            </div>

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
@push('scripts')
    <script>
        function makeMoney(value, nullable = false){
            value+= '';
            value = value.replace(/\D/g,'')
            if(value[0] === '0'){
                value = value.substr(1,value.length);
            }
            let result = '';
            let check = true;
            while(check){
                if(value.length>3){
                    result = ',' + value.substr(value.length-3,3)+result;
                    value = value.substr(0,value.length-3)
                }else{
                    result = value + result;
                    check = false;
                }
            }
            if(result.length){
                result += '₽'
            }else{
                if(nullable){
                    result = ''
                }else{
                    result = '0₽'
                }

            }
            return result;
        }
        $(document).ready(function (){
            $('.should_money').each(function (index,value){
                if($(value).text()){
                    $(value).text(makeMoney($(value).text()));
                }

            })
        })
    </script>
@endpush
