<!doctype html>
<html lang="en">

@include('includes.core.head')
<body class="bg-theme bg-theme1">
<div class="wrapper " id="main_wrapper">
    @if(session()->has('success'))
        <input type="hidden" id="success" value="{{session()->get('success')}}">
    @else
        <input type="hidden" id="success" value="">
    @endif
    @include('includes.header')
        <div class="back_button">
            <button class="button_no_nothing text-white fs-6 pl-2" onclick="goBack()">
                Назад<i class="bx bx-arrow-back"></i>
            </button>
        </div>
    <div id="app">
        @yield('content')
    </div>


</div>
<script>
    function goBack(){
        window.history.back()
    }
</script>
@include('includes.switcher.slide_bar_switcher')
@include('includes.switcher.color_switcher')
@include('includes.core.foot')
</body>

</html>
