@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
                <div class="breadcrumb-title pe-3">Сотрудники</div>

            </div>
            <add-worker-component></add-worker-component>

        </div>
    </div>

    @push('scripts')
        <script defer src="{{asset('js/app.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/caret/1.3.7/jquery.caret.min.js" integrity="sha512-DR6H+EMq4MRv9T/QJGF4zuiGrnzTM2gRVeLb5DOll25f3Nfx3dQp/NlneENuIwRHngZ3eN6w9jqqybT3Lwq+4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://unpkg.com/vuejs-datepicker/dist/locale/translations/ru.js"></script>

    @endpush

@endsection
