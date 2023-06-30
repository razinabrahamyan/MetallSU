<div class="card storage_deduct_card">
    <div class="card-body">
        <!--                        <div class="fm-search">
                                    <div class="mb-0">
                                        <div class="input-group input-group-lg">	<span class="input-group-text bg-transparent"><i class='bx bx-search'></i></span>
                                            <input type="text" class="form-control" placeholder="Search the files">
                                        </div>
                                    </div>
                                </div>-->
        <h5 class="my-3">Вычет</h5>

        <form action="{{route('storage.deduct')}}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-xl-4 col-lg-6 col-md-6">
                    <label for="" class="form-label">Предмет</label>
                    <select required type="text" class="form-select deduct_item_select" name="product_id">
                        @foreach($storage->availableItems as $item)
                            <option data-max="{{$item->count}}" data-handlerded="{{$item->id}}" value="{{$item->id}}"> {{$item->name.'  ( '.$item->description.' ) '}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-4 col-lg-6 col-md-6">
                    <label for="" class="form-label">Количество</label>
                    <input data-current="{{$storage->availableItems->first()?$storage->availableItems->first()->id:''}}" max="{{$storage->availableItems->first()?$storage->availableItems->first()->count:''}}" required class="form-control deduct_count_input" type="number" placeholder="введите количество"  name="count">
                </div>

                <div class="form-group col-xl-4 col-lg-6 col-md-6">
                    <label for="" class="form-label">Дата</label>
                    <input autocomplete="off" required class="form-control datepicker" type="datetime" placeholder="выберите дату" name="date">
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" value="{{$storage->id}}" name="storage_id">
            </div>
            <div class="row mt-3">
                <div class="form-group col-xl-4 col-lg-6 col-md-6">
                    <label for="" class="form-label">Кому</label>
                    <input required class="form-control" type="text" name="to" placeholder="напишите имя">
                </div>

                <div class="form-group col-xl-8 col-lg-6 col-md-6">
                    <label for="" class="form-label">Комментарий</label>
                    <textarea class="form-control" name="comment" type="text" placeholder="комментарий..."></textarea>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-light ms-auto">Вычесть <i class="bx bx-link-external me-0"></i></button>
            </div>

        </form>
        <div class="d-flex align-items-center mt-5">
            <div class="d-flex">
                <h5 class="mb-0">Недавние вычеты</h5>
                <input type="text" class="form-control deduct_search_field" placeholder="поиск" >
            </div>

            <div class="ms-auto navig_buttons">
                <a href="{{route('storage.history',$storage->id)}}" class="btn btn-sm btn-light">История<i class="bx bx-history me-0"></i></a>
                <a href="{{route('storage.calculate',$storage->id)}}" class="btn btn-sm btn-light">Подсчеты <i class="bx bx-arrow-to-right me-0"></i></a>
            </div>
        </div>
        <div class="table-responsive mt-3 deduct_table beautiful_overflow">
            @if($storage->deductions->count())

                <table class="table table-striped table-hover table-sm mb-0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Предмет</th>
                        <th>Кол</th>
                        <th>Кому</th>
                        <th>Дата</th>
                        <th>Коммент.</th>
                        <th>Ред</th>
                    </tr>
                    </thead>
                    <tbody class="static_body active">
                    @foreach($deductions as $deduction)
                        <tr>
                            <td >{{$loop->index + $deductions->firstItem()}}</td>
                            <td >{{$deduction->item->name}}</td>
                            <td >{{$deduction->count}}</td>
                            <td >{{$deduction->to}}</td>
                            <td >{{\Carbon\Carbon::parse($deduction->date)->format('d/m/Y')}}</td>
                            <td  data-handler="comment" data-val="{{$deduction->comment}}">{{$deduction->comment}}</td>
                            <td>
                                <a href="{{route('edit.deduct',$deduction->id)}}" data-id="{{$deduction->id}}" data-purpose="edit" class="btn btn-light edit_deduct">
                                    Ред<i class="bx bx-edit mr-1"></i>
                                </a>
                            </td>
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
