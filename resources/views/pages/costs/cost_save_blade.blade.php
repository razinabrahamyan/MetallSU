@extends('layouts.core')
@section('content')

    <div class="page-wrapper">

        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
                <div class="breadcrumb-title pe-3">Выплаты</div>
                <div class="ps-3 d-flex align-items-center calc_handler">
                    <div id="calc_result">
                        0₽
                    </div>
                    <div>
                        <button class="button_no_nothing ms-2" id="reset_calc_button">
                            <i class="bx bx-x text-white font-20"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!--        <div class="w-100 d-flex">
                        <div class="dropdown ms-auto">
                            <button class="dropdown-toggle btn btn-light dropdown-toggle-nocaret cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false">
                                ...<span></span>
                            </button>
                            <ul class="dropdown-menu" style="margin: 0px;">
                                <li><a class="dropdown-item" href="#">Редактировать</a>
                                </li>


                            </ul>
                        </div>
                    </div>-->
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">

                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#costing_tab" role="tab" aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class="bx bx-add-to-queue font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Добавить Выплату</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " data-bs-toggle="tab" href="#calculations_tab" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class="bx bx-ruble font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Выплаты</div>
                                </div>
                            </a>
                        </li>


                        <li class="nav-item" role="presentation">
                            <a class="nav-link " data-bs-toggle="tab" href="#tab1" role="tab" aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class="bx bx-square-rounded font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Общая Сумма</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " data-bs-toggle="tab" href="#charts" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class="bx bx-bar-chart font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Чарты</div>
                                </div>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content py-3 ">
                        <div class="tab-pane fade active show costing_tab" id="costing_tab" role="tabpanel">

                            @include('includes.chartCards.cost_main')
                        </div>
                        <div class="tab-pane fade statistic_tab" id="calculations_tab" role="tabpanel">
                            @include('includes.chartCards.costFilter')
                        </div>
                        <div class="tab-pane fade costing_tab" id="tab1" role="tabpanel">
                            @include('includes.chartCards.statistic_tree')
                        </div>
                        <div class="tab-pane fade statistic_tab " id="charts" role="tabpanel">
                            @include('includes.chartCards.chart')
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/caret/1.3.7/jquery.caret.min.js" integrity="sha512-DR6H+EMq4MRv9T/QJGF4zuiGrnzTM2gRVeLb5DOll25f3Nfx3dQp/NlneENuIwRHngZ3eN6w9jqqybT3Lwq+4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <script src="{{asset('user_assets/plugins/apexcharts-bundle/js/apexcharts.js')}}"></script>
        <script src="{{asset('user_assets/js/customDropdown.js')}}"></script>
        <script>






            $('#cashless').change(function() {
                if(this.checked) {
                    $(this).closest('.date_main_part').addClass('cashless')
                }else{
                    $(this).closest('.date_main_part').removeClass('cashless')
                }
            });

            let table = $('.position_table')

            let statistic_table = $('.statistic_table').DataTable({
                scrollY:   '700px',
                scrollX: true,
                scrollCollapse: true,
                aLengthMenu: [[15, 20, 25, -1], [15, 20, 25, "Все"]],
                ordering:false,
                aaSorting: [],
                iDisplayLength: 25,
                buttons: [
                    {
                        extend:'excel', text:'excel',

                    },
                    {
                        extend:'pdf', text:'pdf',

                    },
                    {
                        extend:'copy', text:'коп.',

                    },
                    {
                        extend: 'print',
                        text: 'печать',

                    },
                ],
                language:{
                    paginate:{
                        next:"след",
                        previous:"пред"
                    },
                    zeroRecords:'нет результатов',
                    emptyTable:"нет результатов",
                    lengthMenu: "Показать _MENU_ строк"
                }
            });
            statistic_table.buttons().container()
                .appendTo($('#statistic_table_wrapper').find('.row:eq(0)').find('.col-md-6:eq(0)'));

            $('.reset_filters').click(function (){
                let form  = $($(this).data('target'));
                form.find('input[type="text"]').val('').trigger('change');
                var now = new Date();
                var now_utc = new Date(now.toUTCString().slice(0, -4));
                form.find('input[type="datetime"]').datepicker('setDate',now_utc)
                form.find('select:not(.should_stay)').val('').trigger('change');
                form.find('.date_main_part.cashless').removeClass('cashless');
                form.find('#cashless').prop('checked',false);
            })


            $(document).ready(function() {
                let ACTIVE_SELECT = null;
                $('#cashless').select2();
                $('.additional_select_input').select2({
                    templateSelection: renderCustom,
                    width: 'resolve',
                    language: {
                        noResults: function () {
                            return $('<a class="add_new_select_item" role="button">Добавить "<span>'+ $("input.select2-search__field").val() +'</span>"</a>');
                        }
                    }
                });
                $(document).on('click','li.select2-results__option--group',function (){

                })

                $('.item_select').select2({
                    matcher: matchCustom,
                    templateSelection: renderCustom,

                    width: 'auto',
                    language: {
                        noResults: function () {
                            return $('<a class="add_new_select_item" role="button">Добавить "<span>'+ $("input.select2-search__field").val() +'</span>"</a>');
                        }
                    },
                    placeholder: 'предмет / услуга'
                });
                function renderCustom(data){
                    if ($(data.element).data('parent')) {
                        return $(data.element).data('parent')+'/'+data.text
                    }
                    return data.text
                }
                function matchCustom(params, data) {
                    // If there are no search terms, return all of the data
                    if ($.trim(params.term) === '') {
                        return data;
                    }


                    // Do not display the item if there is no 'text' property
                    if (typeof data.text === 'undefined') {
                        return null;
                    }

                    // `params.term` should be the term that is used for searching
                    // `data.text` is the text that is displayed for the data object
                    if (data.text.toUpperCase().indexOf(params.term.toUpperCase()) > -1 ) {
                        var modifiedData = $.extend({}, data, true);

                        // You can return modified objects from here
                        // This includes matching the `children` how you want in nested data sets
                        return modifiedData;
                    }

                    if ($(data.element).data('match') && $(data.element).data('match').toUpperCase().indexOf(params.term.toUpperCase()) > -1 ) {
                        var modifiedData = $.extend({}, data, true);

                        // You can return modified objects from here
                        // This includes matching the `children` how you want in nested data sets
                        return modifiedData;
                    }
                    if (typeof data.children !== 'undefined') {
                        var filteredChildren = [];
                        $.each(data.children, function (idx, child) {
                            if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) > -1 || ($(child.element).data('match') && $(child.element).data('match').toUpperCase().indexOf(params.term.toUpperCase()) > -1)) {
                                filteredChildren.push(child);
                            }
                        });

                        // If we matched any of the timezone group's children, then set the matched children on the group
                        // and return the group object
                        if (filteredChildren.length) {
                            var modifiedData = $.extend({}, data, true);
                            modifiedData.children = filteredChildren;

                            // You can return modified objects from here
                            // This includes matching the `children` how you want in nested data sets
                            return modifiedData;
                        }
                    }

                    // `data.children` contains the actual options that we are matching against


                    // Return `null` if the term should not be displayed
                    return null;
                }

                $('.responsible_multiselect').select2({
                    closeOnSelect: false,
                    width: 'auto',
                    language: {
                        noResults: function () {
                            return $('<p style="color: grey" class="mb-0">Не найдено</p>');
                        }
                    },
                });

                $('.items_multiselect').select2({
                    closeOnSelect: false,
                    templateSelection: renderCustom,
                    width: 'auto',
                    language: {
                        noResults: function () {
                            return $('<p style="color: grey" class="mb-0">Не найдено</p>');
                        }
                    },
                });

                function formatState (state) {
                    if (!state.id) {
                        return state.text;
                    }
                    var $state = $(
                        '<span><img src="https://tinypng.com/images/social/website.jpg" class="select_dropdown_image" /> ' + state.text + '</span>'
                    );
                    return $state;
                };
                $('.statistic_select_input').select2({
                    /*  templateResult: formatState,*/
                    matcher: matchCustom,
                    templateSelection: renderCustom,
                    width: 'auto',
                    language: {
                        noResults: function () {
                            return $('<p style="color: grey" class="mb-0">Не найдено</p>');
                        }
                    },
                });

                $('#change_chart_base').on('change.select2',function (){
                    let value = $(this).val()
                    $.ajax({
                        type: "GET",
                        dataType : "json",
                        url: "{{route('chart.get.responsibles')}}",
                        data: {
                            type_id:value
                        },
                        success: function(data){

                            if(data.success === 'success'){
                                $('#chart_responsibles_select').empty().trigger('change');
                                if($(data.responsibles).length){
                                    var newOption = new Option('Выбрать все','all', false, false);
                                    $('#chart_responsibles_select').append(newOption).trigger('change');
                                    $(data.responsibles).each(function (index,value){
                                        var newOption = new Option(value.name, value.id, false, false);
                                        $('#chart_responsibles_select').append(newOption).trigger('change');
                                    })
                                }


                            }
                        },
                        error:function (err){
                            console.log(err)
                        }
                    });
                })
                $('#chart_responsibles_select,#chart_items_select, #filter_items_select').on('select2:select',function (){
                    let value = $(this).val()
                    let select = $(this);
                    let has_all_selected = false;
                    let has_other_selected = false;
                    let new_val = [];
                    $(value).each(function (index,value){
                        if(value === 'all'){
                            has_all_selected = true;
                        }else{
                            new_val.push(value);
                            has_other_selected = true;
                        }
                    })
                    if(has_all_selected && !select.hasClass('all_selected')){
                        select.val(['all']).trigger('change').select2('close')
                        select.addClass('all_selected')
                    }else{
                        select.val(new_val).trigger('change')
                        select.removeClass('all_selected')
                    }


                })
                function updateAllMoney(){
                    $('.should_money').each(function (index,value){
                        $(value).text(dataTableHelper.makeMoney($(value).text()));
                    })
                }

                $('.todays_costs_collapse_button').click(function (){
                    $('#collapseOne').collapse('toggle')
                    $(this).toggleClass('collapsed')
                })
                $('.cost_accordeon .accordion-button').click(function (){
                    $($(this).data('bs-target')).collapse('toggle')
                    $('.cost_accordeon .accordion-button').addClass('collapsed')




                })
                let horiz = true;
                var options = {
                    series: [{
                        data: []
                    }],
                    chart: {
                        foreColor: 'rgba(255, 255, 255, 0.65)',
                        type: 'bar',
                        height: 350
                    },
                    colors: [
                        function ({value, seriesIndex, w}) {
                            let items = $('#chart_items_select').val();
                            //Логика цвета для [обед,завтрак,ужин]
                            if (['54', '55', '56'].some(item => items.includes(item)) && $('[name=average]').is(':checked')) {
                                if (value >= 400) {
                                    return '#ff0000';
                                } else if (value >= 350 && value < 400) {
                                    return '#f38440';
                                } else {
                                    return '#38C876';
                                }
                            } else {
                                return "#fff";
                            }
                        }
                    ],
                    plotOptions: {
                        bar: {
                            horizontal: horiz,
                        }
                    },
                    grid: {
                        show: true,
                        borderColor: 'rgba(255, 255, 255, 0.12)',
                        strokeDashArray: 4,
                    },
                    tooltip: {
                        theme: 'dark',
                        y: {
                            formatter: function (val,data) {

                                let head =  '<div class="tooltip_chart_header">'+ dataTableHelper.makeMoney(val) +'</div><div>'
                                for(let i = 0; i < GLOBAL_CHART_LIST_ARRAY[data.dataPointIndex].length; i++){
                                    head +=  '                                <div class="d-flex justify-content-between">' +
                                        '                                <div style="">' +
                                        GLOBAL_CHART_LIST_ARRAY[data.dataPointIndex][i].key + ':'+
                                        '                                </div>' +
                                        '                            <div style=" " class="text-right ps-2">' +
                                        dataTableHelper.makeMoney(GLOBAL_CHART_LIST_ARRAY[data.dataPointIndex][i].sum)  +
                                        '                            </div>' +
                                        '                        </div>'
                                    if(GLOBAL_CHART_LIST_ARRAY[data.dataPointIndex][i].grouped && GLOBAL_CHART_LIST_ARRAY[data.dataPointIndex][i].grouped.length){
                                        for(let j = 0; j < GLOBAL_CHART_LIST_ARRAY[data.dataPointIndex][i].grouped.length; j++){
                                            head += '<div>' + GLOBAL_CHART_LIST_ARRAY[data.dataPointIndex][i].grouped[j].key +
                                                ' (' + GLOBAL_CHART_LIST_ARRAY[data.dataPointIndex][i].grouped[j].count +  ')' +
                                                '</div>'
                                        }
                                    }
                                }
                                head += '</div>'
                                return head;
                            }
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            colors: ['#333']
                        },
                        formatter: function (val,data) {
                            if(val){
                                return dataTableHelper.makeMoney(val)
                            }
                            return '';
                        }
                    },
                    xaxis: {
                        categories: [],
                    },

                };
                var chart = new ApexCharts(document.querySelector("#chart7"), options);
                chart.render();
                $('#change_horiz').click(function (){
                    horiz = !horiz;
                    if(!horiz){

                        chart.updateOptions({
                            chart: {
                                height: 650,
                            },
                        })
                    }else{
                        let horiz_height = $(this).data('height');
                        if(horiz_height){
                            chart.updateOptions({
                                chart: {
                                    height: horiz_height,
                                },
                            })
                        }
                    }
                    chart.updateOptions({
                        plotOptions: {
                            bar: {
                                horizontal: horiz
                            }
                        },
                        dataLabels: {
                            enabled: horiz,
                        },
                    })



                })
                let GLOBAL_CHART_LIST_ARRAY = [];
                $('#apply_chart_filters').click(function (){
                    $.ajax({
                        type: "GET",
                        dataType : "json",

                        url: "{{route('chart.get.info')}}"+"?"+$('#chart_form').serialize(),
                        data: {

                        },
                        success: function(data){

                            if(data.success === 'success'){

                                let categories = [];
                                let values = [];
                                GLOBAL_CHART_LIST_ARRAY = [];

                                if(data.responsibles.length > 10){
                                    if(horiz){
                                        chart.updateOptions({
                                            chart: {
                                                height: data.responsibles.length * 40,
                                            },
                                        })
                                    }else{
                                        chart.updateOptions({
                                            chart: {
                                                height: 650
                                            },
                                        })
                                    }

                                    $('#change_horiz').data('height',data.responsibles.length * 40)
                                }else{
                                    $('#change_horiz').data('height',650)
                                }

                                $(data.responsibles).each(function (index,value){
                                    let arr = [];
                                    categories.push(value.name);
                                    values.push(value.sum)
                                    $(value.list).each(function (list_index,list_value){
                                        arr.push({
                                            key:list_value.key,
                                            sum:list_value.sum,
                                            grouped:list_value.grouped
                                        })
                                        console.log(arr)
                                    })
                                    GLOBAL_CHART_LIST_ARRAY.push(arr)
                                })
                                chart.updateOptions({
                                    xaxis: {
                                        categories: categories
                                    },
                                    series: [{
                                        name:'Затрата',
                                        data: values,
                                    }],
                                })
                            }
                        },
                        error:function (err){
                            console.log(err)
                        }
                    });
                })
                let ADDITIONALS_REQUIRED = false;
                {{--$('.item_select, #cashless').on('change.select2',function (){--}}
                {{--    let item = $('.item_select').val()--}}
                {{--    let cashless = $('#cashless').val()--}}

                {{--    if($(this).val() !== 'new' && $(this).val()){--}}
                {{--        $.ajax({--}}
                {{--            type: "GET",--}}
                {{--            dataType : "json",--}}
                {{--            url: "{{route('cost.get.defaults')}}",--}}
                {{--            data: {--}}
                {{--                item:item,--}}
                {{--                cashless: cashless,--}}
                {{--            },--}}
                {{--            success: function(data){--}}

                {{--                if(data.success === 'success'){--}}

                {{--                    $(".additional_select_input").each(function () { //added a each loop here--}}
                {{--                        $(this).val('').trigger('change')--}}
                {{--                    });--}}

                {{--                    $(data.defaults).each(function (index,value){--}}
                {{--                        let select = $('.additional_select_input[data-type="'+ value.type_id +'"]')--}}
                {{--                        select.val(value.id).trigger('change')--}}
                {{--                    })--}}
                {{--                    if(data.item.required_count){--}}
                {{--                        $('.required_count').addClass('required');--}}
                {{--                        $('#count').attr('required',true);--}}
                {{--                    }else{--}}
                {{--                        $('.required_count').removeClass('required');--}}
                {{--                        $('#count').attr('required',false);--}}
                {{--                    }--}}
                {{--                    ADDITIONALS_REQUIRED = !!data.item.required_responsible;--}}
                {{--                    updateAdditionalsRequired()--}}
                {{--                    table.clear().rows.add(data.costs).draw()--}}
                {{--                    $('#my_tbody tr').each(function (index,value){--}}
                {{--                        updateAllMoney()--}}

                {{--                    })--}}
                {{--                }--}}
                {{--            },--}}
                {{--            error:function (err){--}}
                {{--                console.log(err)--}}
                {{--            }--}}
                {{--        });--}}
                {{--    }--}}

                {{--})--}}
                // function updateAdditionalsRequired(){
                //     if(ADDITIONALS_REQUIRED){
                //         $('.additional_required').addClass('required');
                //         let has_value = false;
                //         $('.additional_select_input').each(function (index,value){
                //             if($(value).val()){
                //                 has_value = true;
                //             }
                //         })
                //         if(has_value){
                //             $('#additional_required').attr('required',false);
                //         }else{
                //             $('#additional_required').attr('required',true);
                //         }
                //     }else{
                //         $('.additional_required').removeClass('required');
                //         $('#additional_required').attr('required',false);
                //     }
                //
                // }
                $('.stat_money,.should_money').each(function (index,value){
                    $(value).text(dataTableHelper.makeMoney($(value).text()));
                })
                function splitItemDiv(title, sum, count){
                    return $('<div class="d-flex m-1 align-items-center border radius-10 filter_item_result p-1 px-2">' +
                        '                    <div class="text-white font-16">'+ title +' - </div>' +
                        '            <div class="text-white font-20 ps-2"> '+ sum +'</div>' +
                        '            <div class="ps-1 font-16">('+ count +')</div>' +
                        '            </div>');
                }
                $('#additional_required').focus(function (){
                    $("#additional_required").blur();
                })
                $('#apply_filters').click(function (){
                    let button = $(this);
                    button.addClass('non_active').attr('disabled',true)
                    $.ajax({
                        type: "GET",
                        dataType : "json",

                        url: "{{route('statistic.get.rows')}}"+"?"+$('#stat_form').serialize(),
                        data: {

                        },
                        success: function(data){

                            $('#filter_result_outcome').removeClass('d-none')
                            button.removeClass('non_active').attr('disabled',false)
                            if(data.success === 'success'){
                                statistic_table.clear().rows.add(data.costs).draw()
                                $('#filter_request_sum').text(data.sum + ' (' + data.count + ')')
                                updateAllMoney()
                                $('#split_desk').empty();
                                $(data.split_item_arr).each(function (index,value){
                                    $('#split_desk').append(splitItemDiv(value.title,value.value,value.count))
                                })
                                $('.filter_from_back').text('( ' + data.show_filters + ' )');

                            }
                        },
                        error:function (err){
                            console.log(err)
                        }
                    });
                })
                $('#apply_tree_filters').click(function (){
                    let button = $(this);
                    button.addClass('non_active').attr('disabled',true)
                    $.ajax({
                        type: "GET",
                        dataType : "json",

                        url: "{{route('tree.filter.all')}}"+"?"+$('#tree_form').serialize(),
                        data: {

                        },
                        success: function(data){
                            button.removeClass('non_active').attr('disabled',false)
                            if(data.success === 'success'){
                                console.log(data)
                                $('#main_tree').empty();
                                $(data.data).each(function (index,value){

                                    let main_branch = $('<li>' +
                                        '      <input type="checkbox"  id="main_'+ index +'" />' +
                                        '      <label class="tree_label" for="main_'+ index +'">'+ value.name +'<span class="stat_money">'+ value.done_costs +'</span></label>' +
                                        '</li>')
                                    let ul = $('<ul></ul>');
                                    $(value.responsibles).each(function (subindex, subvalue){

                                        let new_li = $('<li>' +
                                            '    <span class="tree_label">'+ subvalue.name +'<span class="stat_money">'+ subvalue.done_costs +'</span></span>' +
                                            '                                    </li>');
                                        ul.append(new_li);
                                    })
                                    main_branch.append(ul)
                                    $('#main_tree').append(main_branch)
                                })

                            }
                        },
                        error:function (err){
                            console.log(err)
                        }
                    });
                })
                $('.show_filter_result_items').click(function (){
                    if($(this).hasClass('active')){
                        $(this).removeClass('active');
                        $('.split_desk_handler').removeClass('active');
                    }else{
                        $(this).addClass('active');
                        $('.split_desk_handler').addClass('active');
                    }
                })
                $('.select_2_select').on('select2:open',function (){
                    ACTIVE_SELECT = $(this);
                })
                $('.select_2_select').on('change.select2',function (){
                    let container = $(this).next('.select2').find('.select2-selection--single')
                    if($(this).val()){
                        container.addClass('chosen_valid')
                    }
                    else{
                        container.removeClass('chosen_valid')
                    }
                })
                // $('.additional_select_input').on('change.select2',function (){
                //    updateAdditionalsRequired()
                // })

                $(document).on('click','.add_new_select_item',function (){
                    let data = {
                        id: 'new',
                        text: $("input.select2-search__field").val()
                    };

                    let newOption = new Option(data.text, data.id, true, true);
                    ACTIVE_SELECT.closest('.form-group').find('.new_value').val($("input.select2-search__field").val())
                    ACTIVE_SELECT.append(newOption).trigger('change');
                    ACTIVE_SELECT.select2('close');
                    $('.required_count').removeClass('required');
                    $('#count').attr('required',false);

                })
                $('.datepicker').datepicker({
                    autoclose: true,
                    language: 'ru',

                    format: {
                        /*
                         * Say our UI should display a week ahead,
                         * but textbox should store the actual date.
                         * This is useful if we need UI to select local dates,
                         * but store in UTC
                         */
                        toDisplay: function (date, format, language) {
                            var d = new Date(date);
                            return d.toLocaleDateString();
                        },
                        toValue: function (date, format, language) {
                            var d = new Date(date);
                            return d.toLocaleDateString();
                        }
                    }
                });

                var now = new Date();

                var now_utc = new Date(now.toUTCString().slice(0, -4));
                $('#date').datepicker('setDate',now_utc)
                $('#from').datepicker('setDate',new Date(now_utc.getFullYear(),now_utc.getMonth(),1))
                $('#to').datepicker('setDate',now_utc)
                $(document).on('change keyup','.custom_dropdown input,.custom_dropdown select',function (){
                    updateFilterActives($(this).closest('form'))

                })
                function updateFilterActives(form){
                    form.find('.custom_dropdown').each(function (index,value){
                        let toggler_button = $(value).find('.custom_dropdown_toggler');
                        let has_value = false;
                        $(value).find('input,select').each(function (index,input){
                            if($(input).is('input')){
                                if($(input).val()){

                                    has_value = true;
                                }
                            }else if($(input).is('select')){
                                if($(input).attr('multiple')){
                                    if($(input).val().length){

                                        has_value = true;
                                    }
                                }else{
                                    if($(input).val()){

                                        has_value = true;
                                    }
                                }

                            }
                        })
                        if(has_value){
                            toggler_button.addClass('has_value')
                        }else{
                            toggler_button.removeClass('has_value')
                        }
                    })
                }

                $('.zero_date_field').click(function (){
                    $(this).closest('.datetime_delete_handler').find('.datepicker').val('').trigger('change')
                })
                $(".datepicker").keydown(function (event) {
                    event.preventDefault();
                });
                $('.sum_input').on('keyup',function (e){
                    let value= $(this).val();
                    let changedValue = dataTableHelper.makeMoney(value);
                    $(this).val(changedValue);
                    $(this).caret(changedValue.length-1)

                })
                $('.sum_input').on('keydown',function (e){
                    if(!(e.which >= 48 && e.which <= 57) && !(e.which >= 96 && e.which <= 105) && e.which !== 8){
                        e.preventDefault();
                    }

                })
                $('#from_money,#to_money').on('keyup',function (){
                    let value= $(this).val();
                    let changedValue = dataTableHelper.makeMoney(value,true);
                    $(this).val(changedValue);
                    $(this).caret(changedValue.length-1)

                })

                updateFilterActives($('form'));
            });
        </script>
    @endpush



@endsection
