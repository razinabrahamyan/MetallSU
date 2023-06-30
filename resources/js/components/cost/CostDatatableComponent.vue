<template>
    <div class="table-responsive">
        <table id="small_table" class="table position_table table-striped table-bordered beautiful_overflow">
            <thead>
            <tr>
                <th>Дата</th>
                <th>Пред./Услуга</th>
                <th>Кол.</th>
                <th>Сумма</th>
                <th>add1</th>
                <th>add2</th>
                <th>add3</th>
                <th>add4</th>
                <th>Комментарий</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody id="my_tbody">
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "CostDatatableComponent",
    data: function () {
        return this.initialState();
    },
    watch: {},
    methods: {
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
    mounted() {
        this.initDataTable();
        this.dataTableCustomization();
        this.costChangeEvents();
    }
}
</script>

<style scoped>

</style>
