<!doctype html>
<html lang="en">

@include('includes.core.head')

<body class="bg-theme bg-theme1">
<div class="wrapper">
    <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
        <div class="container-fluid">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                <div class="col mx-auto">
                    <div class="text-center">
                        <img src="user_assets/images/logo-icon.png" width="180" alt=""/>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="border p-4 rounded">
                                <div class="text-center">
                                    <h3 class="">Вход</h3>
                                </div>
                                <div class="form-body">
                                    <form class="row g-3" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="col-12">
                                            <label for="inputEmailAddress" class="form-label">Почта</label>
                                            {{--                                            <input type="email" class="form-control" id="inputEmailAddress"--}}
                                            {{--                                                   placeholder="Введите почту">--}}
                                            <input id="inputEmailAddress" type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   placeholder="Введите почту"
                                                   name="email" value="{{ old('email') }}" required autocomplete="off"
                                                   autofocus>

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="inputChoosePassword" class="form-label">Пароль</label>
                                            <div class="input-group" id="show_hide_password">
                                                {{--                                                <input type="password" class="form-control border-end-0"--}}
                                                {{--                                                       id="inputChoosePassword" value=""--}}
                                                {{--                                                       placeholder="Введите пароль"> --}}

                                                <input id="inputChoosePassword" type="password"
                                                       class="form-control border-end-0 @error('password') is-invalid @enderror"
                                                       name="password" required autocomplete="off"
                                                       placeholder="Введите пароль">
                                                <a href="javascript:;"
                                                   class="input-group-text bg-transparent"><i
                                                        class='bx bx-hide'></i></a>
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-light"><i
                                                        class="bx bxs-lock-open"></i>Авторизоваться
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="switcher-wrapper">
    <div class="switcher-btn"><i class='bx bx-cog bx-spin'></i>
    </div>
    <div class="switcher-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0 text-uppercase">Theme Customizer</h5>
            <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
        </div>
        <hr/>
        <p class="mb-0">Gaussian Texture</p>
        <hr>
        <ul class="switcher">
            <li id="theme1"></li>
            <li id="theme2"></li>
            <li id="theme3"></li>
            <li id="theme4"></li>
            <li id="theme5"></li>
            <li id="theme6"></li>
        </ul>
        <hr>
        <p class="mb-0">Gradient Background</p>
        <hr>
        <ul class="switcher">
            <li id="theme7"></li>
            <li id="theme8"></li>
            <li id="theme9"></li>
            <li id="theme10"></li>
            <li id="theme11"></li>
            <li id="theme12"></li>
            <li id="theme13"></li>
            <li id="theme14"></li>
            <li id="theme15"></li>
        </ul>
    </div>
</div>

<!--plugins-->
<script src="{{asset('user_assets/js/jquery.min.js')}}"></script>

<script>
    $(".switcher-btn").on("click", function () {
        $(".switcher-wrapper").toggleClass("switcher-toggled")
    }), $(".close-switcher").on("click", function () {
        $(".switcher-wrapper").removeClass("switcher-toggled")
    }),


        $('#theme1').click(theme1);
    $('#theme2').click(theme2);
    $('#theme3').click(theme3);
    $('#theme4').click(theme4);
    $('#theme5').click(theme5);
    $('#theme6').click(theme6);
    $('#theme7').click(theme7);
    $('#theme8').click(theme8);
    $('#theme9').click(theme9);
    $('#theme10').click(theme10);
    $('#theme11').click(theme11);
    $('#theme12').click(theme12);
    $('#theme13').click(theme13);
    $('#theme14').click(theme14);
    $('#theme15').click(theme15);


    function theme1() {
        $('body').attr('class', 'bg-theme bg-theme1');
    }

    function theme2() {
        $('body').attr('class', 'bg-theme bg-theme2');
    }

    function theme3() {
        $('body').attr('class', 'bg-theme bg-theme3');
    }

    function theme4() {
        $('body').attr('class', 'bg-theme bg-theme4');
    }

    function theme5() {
        $('body').attr('class', 'bg-theme bg-theme5');
    }

    function theme6() {
        $('body').attr('class', 'bg-theme bg-theme6');
    }

    function theme7() {
        $('body').attr('class', 'bg-theme bg-theme7');
    }

    function theme8() {
        $('body').attr('class', 'bg-theme bg-theme8');
    }

    function theme9() {
        $('body').attr('class', 'bg-theme bg-theme9');
    }

    function theme10() {
        $('body').attr('class', 'bg-theme bg-theme10');
    }

    function theme11() {
        $('body').attr('class', 'bg-theme bg-theme11');
    }

    function theme12() {
        $('body').attr('class', 'bg-theme bg-theme12');
    }

    function theme13() {
        $('body').attr('class', 'bg-theme bg-theme13');
    }

    function theme14() {
        $('body').attr('class', 'bg-theme bg-theme14');
    }

    function theme15() {
        $('body').attr('class', 'bg-theme bg-theme15');
    }
</script>
</body>

</html>
