@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <salary-component></salary-component>

    </div>

    @push('scripts')

        <script defer src="{{asset('js/app.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/caret/1.3.7/jquery.caret.min.js" integrity="sha512-DR6H+EMq4MRv9T/QJGF4zuiGrnzTM2gRVeLb5DOll25f3Nfx3dQp/NlneENuIwRHngZ3eN6w9jqqybT3Lwq+4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://unpkg.com/vuejs-datepicker/dist/locale/translations/ru.js"></script>
    @endpush

@endsection
