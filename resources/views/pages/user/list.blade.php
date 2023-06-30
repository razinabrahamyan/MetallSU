@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row justify-content-evenly">
                @include('includes.user.allUsersList')
            </div>
        </div>
    </div>
@endsection
