<div id="cast_wrapper" class="row">
    <div id="cost" class="cost_table_part  order-2 order-lg-1">
        <div class="table-responsive">
            <table id="small_table" class="table position_table table-striped table-bordered beautiful_overflow">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Пред./Услуга</th>
                    <th>Кол.</th>
                    <th>Сумма</th>
                    @foreach($additionalColumns as $additional)
                        <th>{{$additional->name}}</th>
                    @endforeach
                    <th>Комментарий</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody id="my_tbody">
                </tbody>
            </table>
        </div>
    </div>
    <div class="cost_cost_part  order-1 order-lg-2">
        <!--  NEW COST FORM      -->
        <form action="{{route('cost.store')}}" method="post" id="main_cost_form" enctype="multipart/form-data">
            <div class="row m-0">
                <div class="col-12 m-0 p-2">
                    <div class="card position_adding_card ">
                        <div class="card-body">
                            <div class="card-title">
                                @csrf
                                <input type="hidden" id="pos_id" value="1">
                                <div class=" align-items-end vers_2_main">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-6 col-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row date_main_part">
                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group ">
                                                                <label for="date">Дата <span
                                                                        class="required_label">*</span></label>
                                                                <input autocomplete="off" required
                                                                       class="form-control datepicker" type="datetime"
                                                                       id="date" placeholder="выберите дату"
                                                                       name="date">

                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group">
                                                                <div class="position-relative">
                                                                    <label for="item">Предмет/Услуга<span
                                                                            class="required_label">*</span></label>
                                                                    <input type="hidden" class="new_value"
                                                                           name="new_value">
                                                                    <select class="item_select select_2_select"
                                                                            id="item" name="item" required>
                                                                        <option></option>
                                                                        @foreach($items as $item)
                                                                            @if(count($item->subItems))
                                                                                <optgroup label="{{$item->title}}">
                                                                                    @foreach($item->subItems as $subItem)
                                                                                        <option
                                                                                            data-parent="{{$item->title}}"
                                                                                            data-match="{{$item->title.'|'}}@foreach($subItem->defaultResponsibles as $defaultResponsible){{$defaultResponsible->name.'|'}}@endforeach"
                                                                                            value="{{$subItem->id}}">{{$subItem->title}}</option>
                                                                                    @endforeach

                                                                                </optgroup>

                                                                            @else
                                                                                <option
                                                                                    data-match="@foreach($item->defaultResponsibles as $defaultResponsible){{$defaultResponsible->name.'|'}}@endforeach"
                                                                                    value="{{$item->id}}">{{$item->title}}</option>
                                                                            @endif

                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group">
                                                                <label for="item">Тип оплаты</label>
                                                                <div class="position-relative">
                                                                    <select class="select_2_select" id="cashless"
                                                                            name="cashless">
                                                                        <option value=" " class="ppp" selected>Все
                                                                            типы
                                                                        </option>
                                                                        <option value="0" class="ppp">Наличные</option>
                                                                        <option value="1" class="ppp">Безнал</option>
                                                                        <option value="2" class="ppp">Карта</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="row date_main_part">
                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group">
                                                                <div class="d-flex justify-content-between">
                                                                    <label for="sum">Сумма (₽) <span
                                                                            class="required_label">*</span> </label>

                                                                </div>
                                                                <div class="position-relative">
                                                                    <input required type="text"
                                                                           placeholder="Введите сумму"
                                                                           class="form-control sum_input" id="sum"
                                                                           name="sum">
                                                                </div>

                                                            </div>

                                                            <div class="form-group">
                                                                <label for="count">
                                                                    Количество
                                                                    <span
                                                                        :class="['required_label required_count', formRequiredFields.required_count ? 'required' : '']">*</span>
                                                                </label>
                                                                <input type="number" class="form-control" id="count"
                                                                       name="count"
                                                                       :required="formRequiredFields.required_count">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group">
                                                                <label for="comment">Комментарий</label>
                                                                <textarea id="comment" name="comment"
                                                                          class="form-control comment_input" cols="30"
                                                                          rows="4"></textarea>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 date_main_part col-sm-6 col-12">
                                            <div class="row  position-relative pt-3">
                                                <div class="additional_sign">
                                                    Дополнительно
                                                    <span
                                                        :class="['required_label additional_required', formRequiredFields.required_responsible ? 'required' : '']">*</span>
                                                    <input type="text" class="hidden_input"
                                                           :required="formRequiredFields.required_responsible"
                                                           id="additional_required">
                                                </div>
                                                @foreach($statistics as $additional)
                                                    <div
                                                        class="col-sm-12 col-6 col-lg-6 col-xl-4 additional_selects mt-2">
                                                        <div class="form-group">
                                                            <label for="item">{{$additional->name}}</label>
                                                            <div class="position-relative">
                                                                <input type="hidden"
                                                                       name="additionals[{{$additional->id}}][type_id]"
                                                                       value="{{$additional->id}}">
                                                                <input type="hidden" class="new_value"
                                                                       name="additionals[{{$additional->id}}][new_value]">
                                                                <select data-type="{{$additional->id}}"
                                                                        class="additional_select_input select_2_select"
                                                                        name="additionals[{{$additional->id}}][value]">
                                                                    <option selected value="" class="ppp"
                                                                            data-mix="222"> Не Указывать
                                                                    </option>
                                                                    @foreach($additional->responsibles as $responsible)
                                                                        <option
                                                                            value="{{$responsible->id}}">{{$responsible->name}}</option>
                                                                    @endforeach

                                                                </select>
                                                            </div>

                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-lg-12  col-sm-6 col-12">
                                            <div class="row position-relative pt-3">
                                                <div class="form-group">
                                                    <label for="images">Изображения</label>
                                                    <input name="images[]" type="file" multiple="multiple">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="d-flex mt-4 justify-content-between">
                                    <div>
                                        <button type="submit" class="btn btn-light pos_save text-white ms-3 ">
                                            Сохранить<i class="bx bx-save"></i></button>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-danger radius-30 ms-2"
                                                @click="resetFilter">
                                            <i class="bx bx-x-circle"></i>Сбросить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!--  NEW COST FORM      -->
        <!--  TOTAL NUMBERS      -->
        <div class="row m-0">
            <div class="col-12">
                <div class="card ">
                    <div class="card-body">
                        <div class="card-title">
                            <div>
                                <h5>Выплаты За Сегодня</h5>
                            </div>
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button
                                            class="should_money todays_costs_collapse_button accordion-button collapsed"
                                            type="button" data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            {{$todays_total}}
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse  in"
                                         aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body todays_accordeon">
                                            <div class="cost_accordeon accordion accordion-flush"
                                                 id="accordionFlushExample">
                                                @foreach($todays_grouped_costs as $key => $group)
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingOne">
                                                            <button class="border-bottom accordion-button collapsed"
                                                                    type="button" data-bs-target="#{{$key}}"
                                                                    aria-expanded="false"
                                                                    aria-controls="flush-collapseOne">
                                                                <div class="w-100">
                                                                    <div class="d-flex justify-content-between p-1">
                                                                        <div>
                                                                            <h6 class="m-0">
                                                                                {{$group->first()->item->title}}:
                                                                            </h6>
                                                                        </div>
                                                                        <div>
                                                                            <h5 class="should_money m-0">{{$group->sum('value')}}</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="{{$key}}" class="accordion-collapse collapse"
                                                             aria-labelledby="flush-headingOne"
                                                             data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body small_accordion">
                                                                @foreach($group as $cost)
                                                                    <div class="d-flex justify-content-between">
                                                                        <div>{{\Carbon\Carbon::parse($cost->date)->format('d.m.Y')}}</div>
                                                                        <div>
                                                                            <p class="should_money m-0">{{$cost->value}}</p>
                                                                        </div>
                                                                    </div>
                                                                    <p class="m-0 @if(!$loop->last) border-bottom @endif">
                                                                        @foreach($cost->responsibles as $responsible)
                                                                            {{$responsible->name}}
                                                                            @if(!$loop->last){{', '}}@endif
                                                                        @endforeach
                                                                    </p>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  TOTAL NUMBERS      -->
    </div>
</div>
<!--Images Popup-->
<div id="images" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Изображения</h5>
                <button type="button" class="close p-1" data-dismiss="modal" aria-label="Close"
                        onclick="$('#images').modal('hide');">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="costImagesCarusel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div v-for="(image,key) in loadedImages" :class="['carousel-item',key===0 ? 'active' : '']">
                            <a id="single_image" :src="'../storage/'+image">
                                <img class="d-block w-100" :src="'../storage/'+image" alt="First slide">
                            </a>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev"
                       style="opacity: 1;">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only"></span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next"
                       style="opacity: 1;">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@push('vue')
    <script>
        window.cost = new Vue({
            el: "#cast_wrapper",
            data: function () {
                return this.initialState();
            },
            watch: {},
            methods: {
                initialState() {
                    return {
                        dataTable: undefined,
                        costParams: {
                            item: '',
                            cashless: '',
                        },
                        formRequiredFields: {
                            required_count: false,
                            required_responsible: false,
                        },
                        defaults: [],
                    }
                },
                initDataTable: function () {
                    this.dataTable = $('.position_table').DataTable({
                        serverSide: true,
                        processing: true,
                        ajax: {
                            url: "/cost/lazyLoad",
                            method: "get",
                            data: {
                                costParams: this.costParams,
                            }
                        },
                        drawCallback: function (data) {
                            window.cost.updateCostForm(data);
                            dataTableHelper.recoverActivesOnTableChange();

                            /*Костыль (УДАЛИТЬ НАХЕР)*/
                            var now_utc = new Date((new Date('{{$last_cost->date}}')).toUTCString().slice(0, -4));
                            $('#date').datepicker('setDate', now_utc)
                            /*Костыль (УДАЛИТЬ НАХЕР)*/
                        },
                        scrollY: '600px',
                        scrollX: true,
                        scrollCollapse: true,
                        ordering: true,
                        aLengthMenu: [[15, 20, 25, 100], [15, 20, 25, 100]],
                        aaSorting: [[0, 'desc']],
                        searchDelay: 500,
                        columnDefs: [
                            {
                                "targets": [0],
                                "orderable": true,
                            },
                            {
                                "targets": [''],
                                "orderable": false,
                            },
                        ],
                        dom: "B<'clear'><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",//lrtip
                        buttons: [
                            {
                                "extend": 'excel',
                                "text": 'Выгрузить все',
                                "titleAttr": 'Excel',
                                "action": dataTableHelper.exportAllDataTableExcel
                            },
                            {extend: 'excel', text: 'excel'},
                            {extend: 'pdf', text: 'pdf'},
                            {extend: 'copy', text: 'коп.'},
                            {extend: 'print', text: 'печать'},
                        ],
                        language: {
                            paginate: {
                                next: "След",
                                previous: "Пред"
                            },
                            zeroRecords: 'Нет результатов',
                            emptyTable: "Нет результатов",
                            lengthMenu: "Показать _MENU_ строк",
                            processing: "Идет загрузка...",
                            info: "Показано с _START_ по _END_ из _TOTAL_ выплат",
                        }
                    });
                },
                destroyDataTable: function () {
                    window.cost.dataTable.destroy();
                },
                updateDataTable: function () {
                    window.cost.destroyDataTable();
                    window.cost.initDataTable();
                },
                dropFilter: function () {
                    Object.assign(this.$data, this.initialState());
                    $('[type="search"]').val('');
                    window.cost.updateDataTable();
                },
                dataTableCustomization: function () {
                    this.dataTable.buttons().container()
                        .appendTo($('#small_table_wrapper').find('.row:eq(0)').find('.col-md-6:eq(0)'));
                },
                costChangeEvents: function () {
                    $('.item_select, #cashless').on('change.select2', function () {
                        window.cost.costParams.item = $('.item_select').val();
                        window.cost.costParams.cashless = $('#cashless').val();

                        if ((window.cost.costParams.item !== '' || window.cost.costParams.cashless !== ' ')) {
                            window.cost.updateDataTable();
                        }
                    });

                    $('.additional_select_input').on('change.select2', function () {
                        $('.additional_select_input').each(function (index, value) {
                            window.cost.formRequiredFields.required_responsible = true;
                            if ($(value).val()) {
                                window.cost.formRequiredFields.required_responsible = false;
                                return false;
                            }
                        })
                    })
                },
                updateCostForm: function (data) {
                    //Очищаем все select при изменении "Предмет/Услуга"
                    $(".additional_select_input").each(function () {
                        $(this).val('').trigger('change')
                    });

                    window.cost.formRequiredFields.required_count = data.json.formRequiredFields.required_count;
                    window.cost.formRequiredFields.required_responsible = data.json.formRequiredFields.required_responsible;
                    window.cost.defaults = data.json.defaults;

                    $(window.cost.defaults).each(function (index,value){
                        let select = $('.additional_select_input[data-type="'+ value.type_id +'"]')
                        select.val(value.id).trigger('change');
                    });

                },
                resetFilter: function () {
                    //Очистка Data и обновление DataTable
                    let dataTable = window.cost.dataTable;
                    Object.assign(this.$data, this.initialState());
                    window.cost.dataTable = dataTable; //Костыль надо поправить

                    //Очистка Select2
                    let form = $('#main_cost_form');
                    form.find('input[type="text"]').val('').trigger('change');
                    form.find('input[type="number"]').val('');
                    form.find('textarea').val('').trigger('change');

                    var now = new Date();
                    var now_utc = new Date(now.toUTCString().slice(0, -4));
                    form.find('input[type="datetime"]').datepicker('setDate', now_utc)
                    form.find('select:not(.should_stay)').val('');
                    form.find('.date_main_part.cashless').removeClass('cashless');
                    $('[type="search"]').val('');
                    form.find('#cashless').val(' ');
                    form.find('select:not(.should_stay),#cashless').trigger('change');

                    //Очистка калькулятора по клику
                    dataTableHelper.resetCalcButton();

                    //Обновляем таблицу с начальными данными
                    window.cost.updateDataTable();
                },
            },
            mounted() {
                this.initDataTable();
                this.dataTableCustomization();
                this.costChangeEvents();
            }
        });
        window.costImage = new Vue({
            el: "#images",
            data: function () {
                return this.initialState();
            },
            methods: {
                initialState() {
                    return {
                        loadedImages: [],
                    }
                },
                loadCostImages(id) {
                    axios.post('{{route('cost.get.cost')}}', {
                        'id': id,
                    }, {
                        headers: {
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        }
                    }).then(response => {
                        this.loadedImages = response.data.images;
                        $('#images').modal('show');
                    });
                },
            },
            mounted() {
                setTimeout(function () {
                    $('#costImagesCarusel').carousel();
                }, 300)
                $('#costImagesCarusel .carousel-control-next').click(function () {
                    $('#costImagesCarusel').carousel('next');
                });
                $('#costImagesCarusel .carousel-control-prev').click(function () {
                    $('#costImagesCarusel').carousel('prev');
                });

            }
        });
    </script>
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"
            integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA=="
            crossorigin="anonymous"></script>
    <script>
        $(document).on('click', '.imageLookup', function () {
            $().fancybox({
                selector: '.modal.show .carousel-item a',
                hash: false,
                thumbs: {
                    autoStart: false
                },
            });
        });
    </script>
@endpush
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"
          integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw=="
          crossorigin="anonymous"/>
    <style>
        .carousel-item img {
            max-height: 400px;
            object-fit: contain;
        }

        #single_image {
            display: block !important;
        }
    </style>
@endpush
<!--Images Popup-->
