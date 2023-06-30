<template>
    <div id="cast_wrapper" class="row">
        <div id="cost" class="cost_table_part  order-2 order-lg-1">
            <table id="small_table" class="table position_table table-striped table-bordered beautiful_overflow">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Пред./Услуга</th>
                    <th>Кол.</th>
                    <th>Сумма</th>
                    <th>sdfsdfs</th>
                    <th>adddfsdf2</th>
                    <th>addsef3</th>
                    <th>add4</th>
                    <th>Комментарий</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody id="my_tbody">
                </tbody>
            </table>
        </div>
        <div class="cost_cost_part  order-1 order-lg-2">
            <form  method="post" id="main_cost_form" enctype="multipart/form-data">
                <div class="row m-0">
                    <div class="col-12 m-0 p-2">
                        <div class="card position_adding_card ">
                            <div class="card-body">
                                <div class="card-title">

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
                                                                        <vue-select id="item" v-model="item" :options="items_to_select"></vue-select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-12">
                                                                <div class="form-group">
                                                                    <label for="tem">Тип оплаты</label>
                                                                    <div class="position-relative">
                                                                        <vue-select
                                                                            v-model="payment_type"
                                                                            id="cashless"
                                                                            :options="payment_types_to_select"></vue-select>
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
                                                                            :class="['required_label required_count ']">*</span>
                                                                    </label>
                                                                    <input type="number" class="form-control" id="count"
                                                                           name="count"
                                                                           :required="true">
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
                                                            :class="['required_label additional_required']">*</span>
                                                        <input type="text" class="hidden_input"
                                                               :required="true"
                                                               id="additional_required">
                                                    </div>
                                                    <div
                                                        v-for="column in additionalColumns"
                                                        class="col-sm-12 col-6 col-lg-6 col-xl-6 additional_selects mt-2">
                                                        <div class="form-group">
                                                            <label >{{column.name}}</label>
                                                            <vue-select :nullable="true" @input="(value) => responsibleChanged(column.id,value)" :value="defaultResponsibles[column.id]" :options="column.responsibles" :id="column.id"></vue-select>
                                                        </div>
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

            <!--  TOTAL NUMBERS      -->
        </div>
    </div>

</template>

<script>
import Select2Component from "../select2/Select2Component";
export default {
    name: "CostMainComponent",
    components: {Select2Component},
    data: function () {
        return this.initialState();
    },
    watch: {
        item:function (value) {
            this.defaultResponsibles = {};
            let item = this.items[value];
            Object.entries(item.groupedResponsibles).map(([index,resp]) => {
                this.defaultResponsibles[index] = resp.value
            })
            this.costParams.item = value;
            this.updateDataTable()
        },
        payment_type:function (value){
            this.costParams.cashless = value;
            this.updateDataTable()
            console.log(value)
        }

    },
    mounted() {
        axios.get('/axios/cost/get-initial-data/', {
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        }).then(response => {
            let data  = response.data;
            this.additionalColumns = data.additionalColumns;
            let select_array = [];
            data.items.map(value => {
                let select_item = {};
                select_item.id = value.id;
                select_item.text = value.title;
                this.items[value.id] = value;
                if(value.sub_items.length){
                    let subitem_array = [];
                    value.sub_items.map(sub_item => {
                        let select_subitem = {};
                        select_subitem.id = sub_item.id;
                        select_subitem.text = sub_item.title;
                        this.items[sub_item.id] = sub_item;
                        subitem_array.push(select_subitem)
                    })
                    select_item.children = subitem_array;
                }
                select_array.push(select_item)
            })
           /* data.additionalColumns.map(value => {
                this.defaultResponsibles[value.id] = null;
            })*/
            this.items_to_select = select_array;
            console.log(data,'data')
            console.log(this.items)
        });
        this.initDataTable();
        this.dataTableCustomization();
        this.costChangeEvents();
    },
    methods: {
        responsibleChanged(type_id,value){
            this.defaultResponsibles[type_id] = value
        },
        initialState(){
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
                items_to_select:[],
                payment_types_to_select:[{id:' ',text:'Все типы'},{id:0,text:'Наличные'},{id:1,text:'Безнал'},{id:2,text:'Карта'}],
                item:null,
                items:{},
                payment_type:' ',
                additionalColumns:[],
                defaultResponsibles:{},
                defaults: [],
            }
        },
        initDataTable(){
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
                drawCallback:  (data) =>  {
                    this.updateCostForm(data);
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
        destroyDataTable () {
            this.dataTable.destroy();
        },
        updateDataTable ()  {
            this.destroyDataTable();
            this.initDataTable();
        },
        dropFilter ()  {
            Object.assign(this.$data, this.initialState());
            $('[type="search"]').val('');
            this.updateDataTable();
        },
        dataTableCustomization ()  {
            this.dataTable.buttons().container()
                .appendTo($('#small_table_wrapper').find('.row:eq(0)').find('.col-md-6:eq(0)'));
        },
        costChangeEvents ()  {
            $('.item_select, #cashless').on('change.select2', function () {
                this.costParams.item = $('.item_select').val();
                this.costParams.cashless = $('#cashless').val();

                if ((this.costParams.item !== '' || this.costParams.cashless !== ' ')) {
                    this.updateDataTable();
                }
            });

            $('.additional_select_input').on('change.select2', function () {
                $('.additional_select_input').each(function (index, value) {
                    this.formRequiredFields.required_responsible = true;
                    if ($(value).val()) {
                        this.formRequiredFields.required_responsible = false;
                        return false;
                    }
                })
            })
        },
        updateCostForm(data) {
            //Очищаем все select при изменении "Предмет/Услуга"
            $(".additional_select_input").each(function () {
                $(this).val('').trigger('change')
            });

            this.formRequiredFields.required_count = data.json.formRequiredFields.required_count;
            this.formRequiredFields.required_responsible = data.json.formRequiredFields.required_responsible;
            this.defaults = data.json.defaults;

            $(this.defaults).each(function (index,value){
                let select = $('.additional_select_input[data-type="'+ value.type_id +'"]')
                select.val(value.id).trigger('change');
            });

        },
        resetFilter () {
            //Очистка Data и обновление DataTable
            let dataTable = this.dataTable;
            Object.assign(this.$data, this.initialState());
            this.dataTable = dataTable; //Костыль надо поправить

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

            //Обновляем таблицу с начальными данными
            this.updateDataTable();
        },
    },

}
</script>

<style scoped>

</style>
