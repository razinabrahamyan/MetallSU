@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <a href="{{route('storage.index')}}"><div class="breadcrumb-title should_opac pe-3">Склады</div></a>

                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{route('storage.index')}}"><i class="bx bx-grid-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Редактирование</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="col should_open_on_mobile">
                <h6 class="mb-0 text-uppercase">Primary Nav Tabs</h6>
                <hr/>
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs storage_tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active storage_tab" data-bs-toggle="tab" href="#primaryhome" role="tab" aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-folder-open font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Склад</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link add_tab" data-bs-toggle="tab" href="#primaryprofile" role="tab" aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-add-to-queue font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Добавить</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link ded_tab" data-bs-toggle="tab" href="#primarycontact" role="tab" aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-comment-minus font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Вычесть</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content py-3 tabs_no_bord storage_tabs_content">
                            <div class="tab-pane fade show active" id="primaryhome" role="tabpanel">
                                @include('includes.storage.storage')
                            </div>
                            <div class="tab-pane fade" id="primaryprofile" role="tabpanel">
                                @include('includes.storage.add')
                            </div>
                            <div class="tab-pane fade" id="primarycontact" role="tabpanel">
                                @include('includes.storage.deduct')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row should_close_on_mobile">
                <div class="col-12 col-md-6 col-xl-3 col-lg-4">
                    @include('includes.storage.add')
                    @include('includes.storage.storage')
                </div>
                <div class="col-12 col-md-6 col-xl-9 col-lg-8">
                    @include('includes.storage.deduct')
                </div>
            </div>

            <!--end row-->
        </div>
        <input type="hidden" id="stor_id" value="{{$storage->id}}">

    </div>
    <script>
        $(document).ready(function (){
            $('.add_item_type').change(function (){
                if($(this).val() === 'add_new'){
                    let input = $('<input type="text" id="name" class="form-control" name="product_new" placeholder="введите название">');
                    let desc_input = $('<div class="form-group mt-3">' +
                        '<label class="form-label" for="desc">Описание</label>' +
                        '<input type="text" id="desc" class="form-control" name="description" placeholder="введите название">' +
                        ' </div>')
                    $(this).after(input);
                    $(this).closest('.form-group').after(desc_input)
                    $(this).remove()
                    input.focus()
                }
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
            })
            let STORAGE_ID = $('#stor_id').val();
            let STATIC_TABLE_INFO = $('.static_body')
            let DYNAMIC_TABLE_INFO = $('.dynamic_body')
            $('#stor_id').remove();

            $('.deduct_item_select').change(function (){
                $('.deduct_count_input').val('').attr('max',$(this).find(':selected').data('max')).data('current',$(this).find(':selected').data('handlerded'));
            })
            let search_value;
            $('.deduct_search_field').on('input',function (){
                $('.deduct_search_field').val($(this).val())
                search_value = $(this).val();
                if(search_value){
                    $.ajax({
                        type: "GET",
                        dataType : "json",
                        url: "{{route('search.deduct')}}",
                        data: {
                            storage_id:STORAGE_ID,
                            text: search_value
                        },
                        success: function(data){
                            if(data.success === 'success'){
                                if(search_value){
                                    STATIC_TABLE_INFO.removeClass('active');
                                    DYNAMIC_TABLE_INFO.empty().addClass('active');
                                    let deductions = data.data;
                                    if(deductions.length){
                                        $(deductions).each(function (index,value){
                                            index++;
                                            if(!value.comment){
                                                value.comment = '';
                                            }
                                            let new_tr = $('<tr></tr>')
                                            new_tr.append('<td>'+ index +'</td>')
                                            new_tr.append('<td>'+ value.item.name +'</td>')
                                            new_tr.append('<td>'+ value.count +'</td>')
                                            new_tr.append('<td>'+ value.to +'</td>')
                                            new_tr.append('<td>'+ value.date +'</td>')
                                            new_tr.append('<td>'+ value.comment +'</td>')
                                            new_tr.append('<td><a href="/deduction/'+ value.id +'" data-id="'+ value.id +'" class="btn btn-light edit_deduct">Ред<i class="bx bx-edit mr-1"></i></a></td>')
                                            DYNAMIC_TABLE_INFO.append(new_tr)
                                        })
                                    }else{
                                        DYNAMIC_TABLE_INFO.html('<div class="nothing_found_td">ничего не найдено</div>')
                                    }

                                }


                            }else{

                            }
                        }
                    });
                }else{
                    STATIC_TABLE_INFO.addClass('active');
                    DYNAMIC_TABLE_INFO.removeClass('active');
                }

            })

            let STATIC_STORAGE_INFO = $('.static_store_items')
            let DYNAMIC_STORAGE_INFO = $('.dynamic_store_items')

            $('.store_items_search_field').on('input',function (){
                $('.store_items_search_field').val($(this).val())
                search_value = $(this).val();
                if(search_value){
                    $.ajax({
                        type: "GET",
                        dataType : "json",
                        url: "{{route('search.store.item')}}",
                        data: {
                            storage_id:STORAGE_ID,
                            text: search_value
                        },
                        success: function(data){
                            if(data.success === 'success'){
                                if(search_value){
                                    STATIC_STORAGE_INFO.removeClass('active');
                                    DYNAMIC_STORAGE_INFO.empty().addClass('active');
                                    let deductions = data.data;
                                    if(deductions.length){
                                        $(deductions).each(function (index,value){

                                            let new_tr = $('<div class="d-flex align-items-center mt-1">' +
                                                '                                            <div class="fm-file-box bg-transparent"><i class="bx bx-menu-alt-right"></i>' +
                                                '                                    </div>' +
                                                '                                    <div class="flex-grow-1 ms-2 overflow-hidden  p-1">' +
                                                '                                    <h6 class="mb-0">'+ value.name +'</h6>' +
                                                '                                    <p class="mb-0 storage_item_card_comment">'+ value.description +'</p>' +
                                                '                                    </div>' +
                                                '                                    <h6 class="mb-0 storage_item_count_handler">'+ value.count +'</h6>' +
                                                '<div class="dropdown" >' +
                                                '                                            <div class="cursor-pointer font-24 dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i>' +
                                                '                                    </div>' +
                                                '                                    <div class="dropdown-menu dropdown-menu-end">' +
                                                '                                    <a class="dropdown-item" href="/item/'+ value.id +'"><span><i class="bx bx-edit pe-1"></i></span>Ред</a>' +
                                                '                                   </div>' +
                                                '                                    </div>' +
                                                '                                    </div>');
                                            DYNAMIC_STORAGE_INFO.append(new_tr)
                                        })
                                    }else{
                                        DYNAMIC_STORAGE_INFO.html('<p>Ничего не найдено</p>')
                                    }

                                }

                            }else{

                            }
                        }
                    });
                }else{
                    STATIC_STORAGE_INFO.addClass('active');
                    DYNAMIC_STORAGE_INFO.removeClass('active');
                }

            })

        })

    </script>

    <div class="overlay toggle-icon"></div>
    <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

@endsection
