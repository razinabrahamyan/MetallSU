@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
                <div class="breadcrumb-title pe-3">Редактировать</div>

                <div class="breadcrumb-title px-3">Предметы/Услуги</div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 ">
            </div>

            <div class="card list_edit_card list_card should_animate animate__animated " id="item_product_card">
                <edit-card-component></edit-card-component>

            </div>
        </div>
    </div>

    @push('scripts')
        <script defer src="{{asset('js/app.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/caret/1.3.7/jquery.caret.min.js" integrity="sha512-DR6H+EMq4MRv9T/QJGF4zuiGrnzTM2gRVeLb5DOll25f3Nfx3dQp/NlneENuIwRHngZ3eN6w9jqqybT3Lwq+4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="{{asset('user_assets/js/customDropdown.js')}}"></script>
        <script>
            $(document).ready(function (){
                $('.list_card').removeClass('should_animate').addClass('animate__fadeIn')

            })

            $(document).on('input change', '#group_find_input',function (){
                let searched  = $(this).val();
                $('#group_find_results').empty();
                if(searched){
                    $('.main_value').each(function (){
                        let input = $(this);

                        if(input.val().toUpperCase().startsWith(searched.toUpperCase())){
                            let group_name = input.hasClass('child_input') ? input.closest('.costing_tab').data('name') + ' / ' + input.data('par') : input.closest('.costing_tab').data('name');
                            $('#group_find_results').append($('<div class="d-flex px-2">' +
                                '                        <div class="me-2 my-1">'+ input.val() +'</div>' +
                                '                    <div class="me-2 my-1">-</div>' +
                                '                    <div class=" my-1">'+ group_name +'</div>' +
                                '                </div>'))
                        }

                    })
                }
            })
            $(document).on('click','.empty_group_results',function (){
                $('#group_find_input').val('').trigger('change');
            })
            $(document).on('click','.show_group_search_desk',function (){
                $('.group_search_desk').toggleClass('active')
            })
            $('#submit_button').on('click',function (){
                if($('.new_child_input.error').length){
                    alert('Повторение названий в одной категории')
                    $('html, body').animate({
                        scrollTop: $('.new_child_input.error').offset().top
                    }, 100);
                }else{
                    $('#real_submit').click();
                }
            })

        </script>
    @endpush

@endsection
