@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler justify-content-between">
                <div class="breadcrumb-title pe-3">Итоги</div>
                <div >
                    <div>
                        <a href="{{route('edit.export.group.page')}}" class="btn text-warning export_group_link" >
                            <i class="bx bx-edit"></i>Редактировать Группы
                        </a>
                        <!--                                <button class="btn btn-light should_dis text-warning add_button" type="button" id="add_group_button">
                                                            <i class="bx bx-plus"></i>Добавить Группу
                                                        </button>-->

                    </div>
                </div>
            </div>

            <div class="card">

                <div class="card-body">
                    <div class="card-title">
                        <form action="{{route('outcome.export')}}" method="POST">
                            @csrf
                            <div class="row justify-content-between align-items-center">
                                <div class="row">
                                    <div class="form-group col-auto mx-2 mt-2">
                                        <label for="file_title" class="form-label">Введите название файла</label>
                                        <input type="text" id="file_title" class="form-control w-auto" name="file_name" placeholder="пр. ИтогЗаФевраль">

                                        <div class="form-group mt-2">
                                            <label for="cashless">Тип оплаты</label>
                                            <div class="position-relative statistic_tab ">
                                                <select id="cashless" name="cashless">
                                                    <option value="0" class="ppp" selected>Наличные</option>
                                                    <option value="1" class="ppp">Безнал</option>
                                                    <option value="2" class="ppp">Карта</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto mx-2 ">
                                        <div class="">

                                            <div class="d-flex flex-wrap ">
                                                <div class="form-group mt-2">
                                                    <label for="from" class="ps-2 form-label">Дата От</label>
                                                    <div class="datetime_delete_handler">
                                                        <input placeholder="выберите дату" autocomplete="off" class="form-control datepicker" type="text" id="from" name="from">
                                                        <button type="button" class="zero_date_field">x</button>
                                                    </div>

                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="to" class="ps-2 form-label">Дата До</label>
                                                    <div class="datetime_delete_handler">
                                                        <input placeholder="выберите дату" autocomplete="off" class="form-control datepicker" type="text" id="to" name="to">
                                                        <button type="button" class="zero_date_field">x</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mx-2 col-auto mt-2">
                                        <label  class="form-label">Выберите тип файла</label>
                                        <div class="d-flex align-items-center mt-2 export_filetypes">
                                            <div class="form-check ">
                                                <input class="form-check-input" checked value="excel" type="radio" name="file_type" id="excel">
                                                <label class="form-check-label" for="excel">excel</label>
                                            </div>
                                            <div class="form-check ms-2">
                                                <input class="form-check-input"  value="pdf" type="radio" name="file_type" id="pdf">
                                                <label class="form-check-label" for="pdf">pdf</label>
                                            </div>
                                            <div class="form-check ms-2">
                                                <input class="form-check-input" value="html" type="radio" name="file_type" id="html">
                                                <label class="form-check-label" for="html">html</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mx-2 pt-4 position-relative fuck_the_police col-auto">
                                        <div class="form-check form-switch" >
                                            <input class="form-check-input" checked type="checkbox" id="hide_nulls" name="hide_nulls">
                                            <label class="form-check-label" for="hide_nulls">Скрыть нулевые Группы</label>
                                        </div>
                                        <div class="form-check form-switch" >
                                            <input class="form-check-input" checked type="checkbox" id="show_items" name="show_items">
                                            <label class="form-check-label" for="show_items">Показывать Позиции Группы</label>
                                        </div>
                                        <div class="form-check form-switch" >
                                            <input class="form-check-input" checked type="checkbox" id="show_item_costs" name="show_item_costs">
                                            <label class="form-check-label" for="show_items">Показывать Суммы Позиций</label>
                                        </div>
                                    </div>


                                </div>


                            </div>
                            <!--                        <div data-index="1" id="big_export_desk">
                                                        <div class="export_group my-3 py-3">
                                                            <div class="form-group d-flex align-items-center py-2">
                                                                <label for="group_title_0">Название Группы</label>
                                                                <input type="text" name="group[0][group_name]" id="group_title_0" class="form-control w-auto ms-2">
                                                            </div>
                                                            <div class="group_items" data-index="0" data-item-index="0">

                                                            </div>
                                                            <div class="form-group">
                                                                <div class="export_results_relative_handler">
                                                                    <input type="text" class="form-control w-auto export_search_input" placeholder="Поиск...">
                                                                    <div class="beautiful_overflow export_result_div animate__animated">
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>-->


                            <div class="mt-4">
                                <div >
                                    <button type="submit" class="btn btn-light  text-white ms-3 "><i class="bx bx-download"></i>Выгрузить</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush
    <script>
        $(document).ready(function (){
            $('#cashless').select2();

            $('#show_items').change(function (){
                if($(this).is(':checked')){
                    $('#show_item_costs').attr('disabled',false)
                }else{
                    $('#show_item_costs').attr('disabled',true)
                }
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

            $('.zero_date_field').click(function (){
                $(this).closest('.datetime_delete_handler').find('.datepicker').val('')
            })
            $(".datepicker").keydown(function (event) {
                event.preventDefault();
            });
            var now = new Date();

            var now_utc = new Date(now.toUTCString().slice(0, -4));
            $('#from').datepicker('setDate',new Date(now_utc.getFullYear(),now_utc.getMonth(),1))
            $('#to').datepicker('setDate',now_utc)
            /*$('.stat_money,.should_money').each(function (index,value){
                $(value).text(makeMoney($(value).text()));
            })
            $('#file_title').focus();
            function makeMoney(value, nullable = false){
                value+= '';
                value = value.replace(/\D/g,'')
                if(value[0] === '0'){
                    value = value.substr(1,value.length);
                }
                let result = '';
                let check = true;
                while(check){
                    if(value.length>3){
                        result = ',' + value.substr(value.length-3,3)+result;
                        value = value.substr(0,value.length-3)
                    }else{
                        result = value + result;
                        check = false;
                    }
                }
                if(result.length){
                    result += '₽'
                }else{
                    if(nullable){
                        result = ''
                    }else{
                        result = '0₽'
                    }

                }
                return result;
            }
            $(document).on('focus','.export_search_input',function (){
                let export_result_div = $(this).closest('.export_results_relative_handler').find('.export_result_div');
                export_result_div.addClass('animate__fadeIn active');
                export_result_div.removeClass('animate__fadeOut');
            })
            $(document).on('keyup','.export_search_input',function (){
                let export_result_div = $(this).closest('.export_results_relative_handler').find('.export_result_div');
                export_result_div.addClass('animate__fadeIn active');
                export_result_div.removeClass('animate__fadeOut');
                let text = $(this).val();
                if(text){
                    $.ajax({
                        type: "GET",
                        dataType : "json",
                        url: "{{route('outcome.search')}}",
                    data: {'text': text},
                    success: function(data){
                        if(data.success === 'success'){
                            export_result_div.empty()
                            let items = data.items;
                            if(items.length){
                                export_result_div.append(' <div class="search_result_item heading">\n' +
                                    '<p>Предмет Услуга</p>'+
                                    '                        </div>')
                                $(items).each(function (item,value){
                                    let icon = 'bx-grid-alt';
                                    let main_div = $('<div class="search_result_item" data-title="'+ value.title +'" data-id="'+ value.id +'"><i class="bx '+ icon +'"></i></div>');
                                    let main_link = $('<p>'+ value.title + '</p>')
                                    if(value.parent_id){
                                        main_link.append('/<span class="search_result_category_admin">'+ value.parent_item.title +'</span>');
                                    }
                                    main_div.append(main_link);


                                    export_result_div.append(main_div)
                                })
                            }else{
                                export_result_div.append('<div class="search_result_item">\n' +
                                    '                            <p class="no_result" >Нет Результатов</p>\n' +
                                    '                        </div>')
                            }

                        }
                    }
                });
            }else{
                export_result_div.empty()
                export_result_div.removeClass('animate__fadeIn active');
                export_result_div.addClass('animate__fadeOut');
            }
        })
        function groupCategoryItem(id,title,group_index,group_item_index,type="item"){
            return $('<div class="group_item">' +
                '                <h5>' +
                '                <i class="bx bx-book-add"></i> '+ title +' ' +
                '            </h5>' +
                '<button class="btn btn-light should_dis text-danger delete_button" type="button">' +
                '                <i class="bx bx-trash"></i>' +
                '        </button>'+
                '            <input type="hidden" value="'+ type +'" name="group['+ group_index +'][items]['+ group_item_index +'][type]">' +
                '                <input type="hidden" value="'+ id +'" name="group['+ group_index +'][items]['+ group_item_index +'][id]">' +
                '                </div>')
        }

        function exportGroup(group_index){
            return $('<div class="export_group my-3 py-3">' +
            '                <div class="form-group d-flex align-items-center py-2">' +
                '                <label for="group_title_'+ group_index +'">Название Группы</label>' +
                '            <input type="text" name="group['+ group_index +'][group_name]" id="group_title_'+ group_index +'" class="form-control w-auto ms-2 group_name">' +
                '            </div>' +
                '            <div class="group_items" data-index="'+ group_index +'" data-item-index="0">' +
                '            </div>' +
                '            <div class="form-group">' +
                '                <div class="export_results_relative_handler">' +
                '                    <input type="text" class="form-control w-auto export_search_input" placeholder="Поиск...">' +
                '                        <div class="beautiful_overflow export_result_div animate__animated">' +
                '                        </div>' +
                '                </div>' +
                '            </div>' +
                '        </div>')
        }
        $(document).on('click','.export_result_div .search_result_item:not(.heading)',function (){
            let CURRENT_ITEM = $(this);
            let CAT_DESK = CURRENT_ITEM.closest('.export_group').find('.group_items');
            let ITEM_ID = CURRENT_ITEM.data('id');
            let ITEM_TITLE = CURRENT_ITEM.data('title');
            let GROUP_INDEX = CAT_DESK.data('index')
            let GROUP_ITEM_INDEX = CAT_DESK.data('item-index')
            CAT_DESK.data('item-index',GROUP_ITEM_INDEX +1)
            let new_group_item = groupCategoryItem(ITEM_ID,ITEM_TITLE,GROUP_INDEX,GROUP_ITEM_INDEX);
            CAT_DESK.append(new_group_item);
        })
        $(document).on('click', function (event) {
            if (!$(event.target).closest('.export_results_relative_handler').length) {
                $('.export_result_div ').removeClass('animate__fadeIn active').addClass('animate__fadeOut');

            }
        });
        $(document).on('click', '.group_item .delete_button',function (event) {
            $(this).closest('.group_item').remove();
        });

        $('#add_group_button').click(function (){
            let BIG_DESK = $('#big_export_desk');
            let NEW_GROUP_INDEX = BIG_DESK.data('index');
            BIG_DESK.data('index',NEW_GROUP_INDEX +1);
            let new_group = exportGroup(NEW_GROUP_INDEX);
            BIG_DESK.append(new_group);
            new_group.find('.group_name').focus();
        })*/



        })
    </script>
    @push('styles')
        <style>
            .statistic_tab .select2-container--default .select2-selection--single:not(.chosen_valid) .select2-selection__rendered {
                color: #FFFFFF !important;
            }

            .statistic_tab .select2-container--default .select2-selection--single{
                background-color: rgb(0 0 0 / 15%);
            }
        </style>
    @endpush

@endsection
