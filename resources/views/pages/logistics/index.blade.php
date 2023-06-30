@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
                <div class="breadcrumb-title pe-3">Логистика</div>
            </div>
            <div id="query-list-wrapper" class="card">
                <div class="card-datatable table-responsive">
                    <br/>
                    <div class="row m-0" id="custom_filters">
                        <label class="col-12">Фильтрация по дате</label>
                        <div class="col-12 mb-1">
                            <div id="departure_date_filter" class="btn-group btn-group-toggle mb-1"
                                 data-toggle="buttons">
                                <label v-for="(fastDateFilterValue,index) in fastDateFilterValues"
                                       :class="['btn btn-outline-primary dep_day_lable', (fastDateFilterValue.value === filter.fastDateFilter) ? 'active' : '']">
                                    <input type="radio" name="fast_date_filter"
                                           :id="'fastDate-' + index"
                                           :value="fastDateFilterValue.value"
                                           v-model="filter.fastDateFilter"/>
                                    <span v-text="fastDateFilterValue.name"></span>
                                </label>
                            </div>
                            <div class="d-flex filter_form_buttons flex-wrap">
                                <div
                                    class="justify-content-start custom_dropdown position_dropdown col-md-auto col-6 col-sm-auto">
                                    <button type="button"
                                            class="btn border btn-light button_no_nothing custom_dropdown_toggler"><i
                                            class="bx bx-calendar"></i><span>Дата</span></button>
                                    <div class="custom_dropdown_menu px-2 ">
                                        <div class="form-group">
                                            <label for="start_date" class="ps-2">От</label>
                                            <div class="datetime_delete_handler">
                                                <input placeholder="Выберите дату" autocomplete="off"
                                                       class="form-control datepicker" type="text" id="start_date"
                                                       name="start_date">
                                                <button type="button" class="zero_date_field">x</button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="end_date" class="ps-2">До</label>
                                            <div class="datetime_delete_handler">
                                                <input placeholder="Выберите дату" autocomplete="off"
                                                       class="form-control datepicker" type="text" id="end_date"
                                                       name="end_date">
                                                <button type="button" class="zero_date_field">x</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-1">
                            <button class="btn btn-primary" @click="dropFilter()">
                                Сброс фильтра
                            </button>
                        </div>
                    </div>
                    <hr/>
                    <table id="query_list_table" class="invoice-list-table table">
                        <thead>
                        <tr>
                            <th>Заявка</th>
                            <th>Дата</th>
                            <th>Водитель</th>
                            <th>Телефон</th>
                            <th>Тип Машины</th>
                            <th>Номер</th>
                            {{--                        <th>Резчик</th>--}}
                            {{--                        <th>Грузчик</th>--}}
                            {{--                        <th>Баллоны</th>--}}
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{asset('user_assets/js/customDropdown.js')}}"></script>
    @endpush

    @push('vue')
        <script>
            window.queryDataTable = new Vue({
                el: "#query-list-wrapper",
                data: function () {
                    return this.initialState();
                },
                watch: {
                    'filter': {
                        handler: function (key) {
                            window.queryDataTable.updateDataTable();
                            window.setCookie('fastDateFilter', key.fastDateFilter, 365 * 24 * 3600 * 1000)
                        },
                        deep: true,
                    }
                },
                methods: {
                    initialState() {
                        return {
                            fastDateFilterValues: [
                                {
                                    value: 'today',
                                    name: 'Сегодня',
                                },
                                {
                                    value: 'tomorrow',
                                    name: 'Завтра',
                                },
                                {
                                    value: '',
                                    name: 'Все',
                                }
                            ],
                            filter: {
                                fastDateFilter: (window.getCookie('fastDateFilter') !== undefined) ? window.getCookie('fastDateFilter') : "today",
                                startDate: "",
                                endDate: "",
                            }
                        }
                    },
                    destroyDataTable: function () {
                        $('#query_list_table').DataTable().destroy();
                    },
                    initDataTable: function () {
                        $('#query_list_table').DataTable({
                            serverSide: true,
                            processing: true,
                            ajax: {
                                url: "/logistics/lazy-load",
                                method: "get",
                                data: {
                                    start_date: this.filter.startDate,
                                    end_date: this.filter.endDate,
                                    fast_date_filter: this.filter.fastDateFilter,
                                }
                            },
                            drawCallback: function (settings) {
                                $('#query_list_table_wrapper .row').eq(0).addClass('d-flex justify-content-between align-items-center m-1');
                                setTimeout(function () {
                                    $('#query_list_table_wrapper td[tabindex="0"]').addClass('dtr-control');
                                }, 50);
                            },
                            stateSave: true,
                            bStateSave: true,
                            fnStateSave: function (oSettings, oData) {
                                localStorage.setItem('DataTables', JSON.stringify(oData));
                            },
                            fnStateLoad: function (oSettings) {
                                return JSON.parse(localStorage.getItem('DataTables'));
                            },
                            fnStateSaveParams: function (settings, data) {
                                data.selected = this.api().rows({selected: true})[0];
                            },
                            fnStateLoadParams: function (settings, data) {
                                savedSelected = data.selected;
                            },
                            lengthChange: true,
                            responsive: true,
                            paging: true,
                            select: true,
                            orderable: false,
                            info: false,
                            aaSorting: [[0, 'desc']],
                            colReorder: true,
                            sScrollX: "100%",
                            sScrollXInner: "110%",
                            language: {
                                paginate: {
                                    previous: "<",
                                    next: ">",
                                },
                                zeroRecords: "Записей не найдено",
                                emptyTable: "Записей не найдено",
                                lengthMenu: "Показать _MENU_ Записей",
                                search: "Поиск:",
                                processing: "Идет загрузка Записей...",
                                info: "Показано с _START_ по _END_ из _TOTAL_ Записей",
                            },
                            columnDefs: [
                                {
                                    "className": "text-center",
                                    "targets": "_all",
                                },
                                {
                                    'orderable': false,
                                    'targets': '_all',
                                }
                            ]
                        });
                    },
                    updateDataTable: function () {
                        this.destroyDataTable();
                        this.initDataTable();
                    },
                    dropFilter: function () {
                        Object.assign(this.$data, this.initialState());
                        $('[type="search"]').val('');
                        $('#start_date').val('');
                        $('#end_date').val('');
                        window.queryDataTable.updateDataTable();
                    },
                },
                mounted() {
                    this.initDataTable();

                    $(document).on('change', '#start_date', function () {
                        window.queryDataTable.filter.startDate = $(this).val();
                    });
                    $(document).on('change', '#end_date', function () {
                        window.queryDataTable.filter.endDate = $(this).val();
                    });
                }
            });
        </script>
    @endpush

    @push('styles')
        <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet">
    @endpush

@endsection
