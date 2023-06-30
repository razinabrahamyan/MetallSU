@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
                <div class="breadcrumb-title pe-3">Редактировать</div>

                <div class="breadcrumb-title px-3">Площадки/Отделы</div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 ">
            </div>

            <div class="card list_edit_card worker_card should_animate animate__animated " id="item_product_card">
                <category-edit-component></category-edit-component>

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
                $('.worker_card').removeClass('should_animate').addClass('animate__fadeIn')
            })


        </script>
    @endpush

@endsection
