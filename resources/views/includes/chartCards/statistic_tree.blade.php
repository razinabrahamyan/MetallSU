<form id="tree_form">
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
                                <label for="tree_from" class="ps-2">От</label>
                                <div class="datetime_delete_handler">
                                    <input readonly placeholder="выберите дату" autocomplete="off" class="form-control datepicker" type="text" id="tree_from" name="from">
                                    <button type="button" class="zero_date_field">x</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tree_to" class="ps-2">До</label>
                                <div class="datetime_delete_handler">
                                    <input readonly placeholder="выберите дату" autocomplete="off" class="form-control datepicker" type="text" id="tree_to" name="to">
                                    <button type="button" class="zero_date_field">x</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-auto reset_block order-2 order-lg-3">
                <button type="button" data-target="#tree_form"  class="btn reset_filters btn-light radius-30 m-1"><i class="bx bx-x-circle"></i>Сбросить</button>
            </div>
        </div>
    </div>

    <div class="m-2">
        <button type="button" id="apply_tree_filters" class="btn btn-light">
            <i class="bx bx-refresh static"></i>
            <div class="spinner-border dynamic spinner-border-sm me-2 ms-1" role="status">
            </div>
            Применить
        </button>
    </div>


</form>

<ul class="tree" id="main_tree">
    @foreach($statistics as $statistic)
        @php($main_index = $loop->index)
        <li>
            <input type="checkbox"  id="main_{{$main_index}}" />
            <label class="tree_label" for="main_{{$main_index}}">{{$statistic->name}}<span class="stat_money">{{$statistic->costSum}}</span></label>
            <ul>
                @foreach($statistic->responsibles as $responsible)
                    <li>
                        <span class="tree_label">{{$responsible->name}}<span class="stat_money">{{$responsible->costs->sum('value')}}</span></span>
                    </li>
                @endforeach
            </ul>
        </li>
    @endforeach

</ul>
