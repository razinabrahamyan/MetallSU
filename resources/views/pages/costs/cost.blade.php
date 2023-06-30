@extends('layouts.core')
@section('content')

    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
                <div class="breadcrumb-title pe-3">Выплаты</div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{route('post.request')}}" method="POST">
                        @csrf
                        <input type="text" name="name">
                        <button type="submit">Submit</button>
                    </form>
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
    @endpush
@endsection
