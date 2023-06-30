<template>
    <div class="table-responsive">
        <table id="small_table" ref="table" class="table position_table table-striped table-bordered beautiful_overflow">
            <thead>
            <tr>
                <th v-for="col in table_columns" v-text="col"></th>
            </tr>
            </thead>
            <tbody id="my_tbody">
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "DataTableComponent",
    props:['params','columns', 'url'],
    data:function () {
        return this.initialState()
    },
    watch:{
        params:{
            handler:function (val){
                this.updateDataTable()
            },
            deep:true
        }
    },
    mounted() {
        console.log(this.table_params, 'asdasdasd')
        this.initDataTable();
        this.dataTableCustomization();
        this.costChangeEvents();
    },
    methods:{
        initialState() {
            return {
                dataTable: undefined,
                table_params:this.params,
                table_columns:this.columns,
                link:this.url
            }
        },
        initDataTable: function () {
            this.dataTable = $(this.$refs.table).DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: this.link,
                    method: "get",
                    data: {
                        params: this.params,
                    }
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
            this.dataTable.destroy();
        },
        updateDataTable: function () {
            this.destroyDataTable();
            this.initDataTable();
        },
        dropFilter: function () {
            Object.assign(this.$data, this.initialState());
            $('[type="search"]').val('');
            this.updateDataTable();
        },
        dataTableCustomization: function () {
            this.dataTable.buttons().container()
                .appendTo($('#small_table_wrapper').find('.row:eq(0)').find('.col-md-6:eq(0)'));
        },
        costChangeEvents: function () {

        },

    },

}
</script>

<style scoped>

</style>
