<div class="row" id="cost_chart">
    <div class="col-md-4">
        <div class="pb-3">
            <h4>Фильтры  <button type="button" data-target="#chart_form"  class="btn reset_filters pos_save btn-light radius-30 ms-2"><i class="bx bx-x-circle"></i>Сбросить</button> </h4>
            <form id="chart_form">
                <div class="row">

                        <div class="border m-2 radius-10 p-1 col">
                            <h5 class="text-center border-bottom pb-2">Дата</h5>
                            <div class="">
                                <div class="form-group">
                                    <label for="from_date" class="ps-2">От</label>
                                    <div class="datetime_delete_handler">
                                        <input placeholder="выберите дату" autocomplete="off" class="form-control datepicker" type="text" id="from_date" name="from_date">
                                        <button type="button" class="zero_date_field">x</button>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="to_date" class="ps-2">До</label>
                                    <div class="datetime_delete_handler">
                                        <input placeholder="выберите дату" autocomplete="off" class="form-control datepicker" type="text" id="to_date" name="to_date">
                                        <button type="button" class="zero_date_field">x</button>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="border m-2 radius-10 col p-3 w-auto stat_select_box chart_type_select_handler">
                            <h6 class=" pb-2">Сравнивать</h6>
                            <div class="form-group">
                                <label for="type_id">Тип</label>
                                <select  class="should_stay statistic_select_input form-select select_2_select" id="change_chart_base" name="type_id" >
                                    @foreach($additionalColumns as $additional)
                                        <option  @if($loop->index === 0) selected @endif value="{{$additional->id}}">{{$additional->name}}</option>

                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="chart_responsibles_select">Сравнить</label>
                                <select  class="responsible_multiselect form-select select_2_select" name="responsibles[]" multiple="multiple" id="chart_responsibles_select">

                                </select>
                            </div>

                        </div>
                        <div class="border m-2 radius-10 col p-3 w-auto stat_select_box">
                            <h6 class=" pb-2">Считать выплаты на</h6>

                            <div class="form-group mt-2">
                                <label for="chart_items_select">Предмет/Услуга</label>
                                <select   class="items_multiselect form-select select_2_select" name="chart_items[]" multiple="multiple" id="chart_items_select">
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
                <div class="m-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="average" type="checkbox" id="flexSwitchCheckChecked" v-model="tumblers.avg">
                        <label class="form-check-label" for="flexSwitchCheckChecked">Считать среднее</label>
                    </div>
                    <div class="form-check form-switch" v-if="tumblers.avg">
                        <input class="form-check-input" name="avgOnOneService" type="checkbox" id="avgOnOneService">
                        <label class="form-check-label" for="avgOnOneService">Считать среднее на одну позицию</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="grouped" type="checkbox" id="groupResponse">
                        <label class="form-check-label" for="groupResponse">Разбить на группы</label>
                    </div>
                </div>
                <div class="m-2">
                    <button type="button" id="apply_chart_filters" class="btn btn-light"><i class="bx bx-refresh"></i>Применить</button>
                </div>

            </form>

        </div>
    </div>
    <div class="col-md-8">
        <div id="chart7"></div>
        <div>
            <div ><button class="d-block btn btn-light text-white ms-auto" id="change_horiz">Перевернуть<i class="bx bx-refresh"></i></button></div>
        </div>
    </div>
</div>
@push('vue')
    <script>
        window.costChart = new Vue({
            el: "#cost_chart",
            data: function () {
                return this.initialState();
            },
            watch: {},
            methods: {
                initialState() {
                    return {
                        tumblers: {
                            avg: false,
                        },
                    }
                },
            },
            mounted() {

            }
        });
    </script>
@endpush
