<div class="pb-3">



<!--    <h4>Фильтры <button type="button" data-target="#stat_form"  class="btn reset_filters btn-light radius-30 ms-2"><i class="bx bx-x-circle"></i>Сбросить</button></h4>-->
    <form id="stat_form">
        <div class="">
            <div class="row align-items-center justify-content-lg-start justify-content-between div_for_filters">
                <div class="col-auto order-1 me-2 filter_title_handler">
                    <h4>Фильтры</h4>

                </div>

                <div class="col-lg-auto col-12 border radius-10 py-2 px-1 filter_but_handler order-3 order-lg-2">
                    <div class="d-flex justify-content-between filter_form_buttons flex-wrap">
                        <div class="custom_dropdown position_dropdown col-md-auto col-6 col-sm-auto">
                            <button  type="button" class="btn btn-light button_no_nothing custom_dropdown_toggler m-1"><i class="bx bx-calendar"></i><span>Дата</span></button>
                            <div class="custom_dropdown_menu px-2 " >
                                <label class="group_label">введите даты</label>
                                <div class="form-group">
                                    <label for="from" class="ps-2">От</label>
                                    <div class="datetime_delete_handler">
                                        <input readonly placeholder="выберите дату" autocomplete="off" class="form-control datepicker" type="text" id="from" name="from">
                                        <button type="button" class="zero_date_field">x</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="to" class="ps-2">До</label>
                                    <div class="datetime_delete_handler">
                                        <input readonly placeholder="выберите дату" autocomplete="off" class="form-control datepicker" type="text" id="to" name="to">
                                        <button type="button" class="zero_date_field">x</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="custom_dropdown position_dropdown col-md-auto col-6 col-sm-auto ">
                            <button  type="button" class="btn btn-light button_no_nothing custom_dropdown_toggler m-1"><i class="bx bx-ruble"></i>Сумма</button>
                            <div class="custom_dropdown_menu px-2 " >
                                <label class="group_label">введите суммы</label>
                                <div class="form-group">
                                    <label for="from_money" class="ps-2">От</label>
                                    <input class="form-control" type="text" id="from_money" name="from_money">
                                </div>
                                <div class="form-group">
                                    <label for="to_money" class="ps-2">До</label>
                                    <input class="form-control" type="text" id="to_money" name="to_money">
                                </div>
                            </div>
                        </div>
                        <div class="custom_dropdown col-md-auto col-6 col-sm-auto ">
                            <button type="button" class="btn btn-light button_no_nothing m-1 custom_dropdown_toggler"><i class="bx bx-cylinder"></i><span>Предмет/Услуга</span></button>
                            <div class="custom_dropdown_menu px-2">
                                <div class="form-group stat_item_select">
                                    <label class="group_label">выберите предметы/услуги</label>
                                    <select   class="items_multiselect form-select select_2_select" name="items[]" multiple="multiple" id="filter_items_select">
                                        <option value="all">Выбрать все</option>
                                        @foreach($items as $item)
                                            @if(count($item->subItems))
                                                <optgroup value="ATZ" class="select2-result-selectable" label="{{$item->title}}">
                                                    @foreach($item->subItems as $subItem)
                                                        <option data-parent="{{$item->title}}" data-match="{{$item->title.'|'}}@foreach($subItem->defaultResponsibles as $defaultResponsible){{$defaultResponsible->name.'|'}}@endforeach" value="{{$subItem->id}}">{{$subItem->title}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                <option data-match="@foreach($item->defaultResponsibles as $defaultResponsible){{$defaultResponsible->name.'|'}}@endforeach" value="{{$item->id}}">{{$item->title}}</option>
                                            @endif

                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="custom_dropdown col-md-auto col-6 col-sm-auto ">
                            <button type="button" class="btn btn-light button_no_nothing m-1 custom_dropdown_toggler"><i class="bx bx-folder-plus"></i>Ответственные</button>
                            <div class="custom_dropdown_menu px-2">
                                <div class="d-flex flex-wrap selects_min">
                                    @foreach($statistics as $additional)
                                        <div class="stat_select_box_handler">
                                            <div class="border m-2 radius-10 p-1 stat_select_box">
                                                <h6 class=" pb-2">{{$additional->name}}</h6>
                                                <div class="form-group">
                                                    <select  class="statistic_select_input form-select select_2_select" name="additionals[]" >
                                                        <option selected value="" class="ppp" data-mix="222"> Не Указывать</option>

                                                        @foreach($additional->responsibles as $responsible)
                                                            <option  value="{{$responsible->id}}">{{$responsible->name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-auto reset_block order-2 order-lg-3">
                    <button type="button" data-target="#stat_form"  class="btn reset_filters btn-light radius-30 m-1"><i class="bx bx-x-circle"></i>Сбросить</button>
                </div>
            </div>
        </div>
<!--        <div class="d-block d-lg-flex align-items-start">

                <div class="d-flex col-auto flex-wrap">
                    <div class="border m-2 radius-10 p-1">
                        <h5 class="text-center border-bottom pb-2">Дата</h5>
                        <div class="">
                            <div class="form-group">
                                <label for="from" class="ps-2">От</label>
                                <div class="datetime_delete_handler">
                                    <input placeholder="выберите дату" autocomplete="off" class="form-control datepicker" type="text" id="from" name="from">
                                    <button type="button" class="zero_date_field">x</button>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="to" class="ps-2">До</label>
                                <div class="datetime_delete_handler">
                                    <input placeholder="выберите дату" autocomplete="off" class="form-control datepicker" type="text" id="to" name="to">
                                    <button type="button" class="zero_date_field">x</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="border m-2 radius-10 p-1">
                        <h5 class="text-center border-bottom pb-2">Сумма</h5>
                        <div class="">
                            <div class="form-group">
                                <label for="from_money" class="ps-2">От</label>
                                <input class="form-control" type="text" id="from_money" name="from_money">
                            </div>
                            <div class="form-group">
                                <label for="to_money" class="ps-2">До</label>
                                <input class="form-control" type="text" id="to_money" name="to_money">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border m-2 radius-10 p-1 stat_item_select ">
                    <h6 class=" pb-2">Предмет/Услуга</h6>
                    <div class="form-group">
                        <select   class="items_multiselect form-select select_2_select" name="items[]" multiple="multiple" id="filter_items_select">
                            <option value="all">Выбрать все</option>
                            @foreach($items as $item)
                                @if(count($item->subItems))
                                    <optgroup value="ATZ" class="select2-result-selectable" label="{{$item->title}}">
                                        @foreach($item->subItems as $subItem)
                                            <option data-parent="{{$item->title}}" data-match="{{$item->title.'|'}}@foreach($subItem->defaultResponsibles as $defaultResponsible){{$defaultResponsible->name.'|'}}@endforeach" value="{{$subItem->id}}">{{$subItem->title}}</option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    <option data-match="@foreach($item->defaultResponsibles as $defaultResponsible){{$defaultResponsible->name.'|'}}@endforeach" value="{{$item->id}}">{{$item->title}}</option>
                                @endif

                            @endforeach
                        </select>

                    </div>
                </div>




            <div class=" d-flex flex-wrap ">

                @foreach($statistics as $additional)
                    <div class="border m-2 radius-10 p-1 stat_select_box">
                        <h6 class=" pb-2">{{$additional->name}}</h6>
                        <div class="form-group">
                            <select  class="statistic_select_input form-select select_2_select" name="additionals[]" >
                                <option selected value="" class="ppp" data-mix="222"> Не Указывать</option>

                                @foreach($additional->responsibles as $responsible)
                                    <option  value="{{$responsible->id}}">{{$responsible->name}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                @endforeach
            </div>




        </div>-->
        <div class="m-2">
            <button type="button" id="apply_filters" class="btn btn-light">
                <i class="bx bx-refresh static"></i>
                <div class="spinner-border dynamic spinner-border-sm me-2 ms-1" role="status">
                </div>
                Применить
            </button>
        </div>


    </form>

</div>
<div class="d-none" id="filter_result_outcome">
    <div class="border radius-10 p-2">
        <h5>Общая сумма по указанным фильтрам <span class="filter_from_back"></span></h5>
        <div class="d-flex align-items-center ms-2">
            <h3 class="mb-0" id="filter_request_sum"></h3>
            <button class="btn button_no_nothing show_filter_result_items border ms-2">
                <i class="bx me-0 bx-exclude show_items_icon"></i>
                <i class="bx me-0 bx-unite hide_items_icon text-white"></i>
            </button>
        </div>
        <div class="split_desk_handler">
            <div class="d-flex flex-wrap" id="split_desk">

            </div>
        </div>

    </div>

</div>
<div class="table-responsive">
    <table id="statistic_table" class="table statistic_table table-striped table-bordered">
        <thead>
        <tr>
            <th>Дата</th>
            <th class="responsible_th">Пред./Услуга</th>
            <th>Кол.</th>
            <th >Сумма</th>
            @foreach($additionalColumns as $additional)
                <th @if($loop->index%2 === 0) class="responsible_th" @endif>{{$additional->name}}</th>
            @endforeach

            <th>Комментарий</th>
            <th>Ред.</th>

        </tr>
        </thead>
        <tbody id="my_tbody_statistic">
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @foreach($additionalColumns as $additional)
                <td></td>
            @endforeach
            <td></td>
            <td></td>
        </tr>

        </tbody>
    </table>
</div>
