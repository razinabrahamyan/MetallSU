@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <a href="{{route('storage.show',$storage->id)}}"><div class="breadcrumb-title should_opac pe-3">{{$storage->title}}</div></a>

                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{route('storage.index')}}"><i class="bx bx-grid-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Подсчеты</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="card">
                <div class="card-body">


                    <div class="d-flex align-items-center ">
                        <h5 class="my-3">Подсчеты</h5>

                        <div class="ms-auto navig_buttons">
                            <a href="{{route('storage.history',$storage->id)}}" class="btn btn-sm btn-light">История<i class="bx bx-history me-0"></i></a>
                            <a href="{{route('storage.show',$storage->id)}}" class="btn btn-sm btn-light">Склад <i class="bx bx-grid me-0"></i></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center ">
                                <div class="row">
                                    <div class="col">
                                        <label for="" class="form-label">Предмет</label>
                                        <input type="text" class="form-control deduct_search_field search_part" placeholder="поиск" id="search_item">
                                    </div>
                                    <div class="col">
                                        <label for="" class="form-label">Кол</label>
                                        <input type="number" class="form-control deduct_search_field search_part" placeholder="поиск" id="search_count">
                                    </div>
                                    <div class="col">
                                        <label for="" class="form-label">Кому</label>
                                        <input type="text" class="form-control deduct_search_field search_part" placeholder="поиск" id="search_who">
                                    </div>
                                    <div class="col">
                                        <div class="d-flex align-items-baseline justify-content-between">
                                            <label for="" class="form-label">Дата от</label>
                                            <button class="button_no_nothing" onclick="$('#search_date_from').val('').trigger('change')"><i class="bx bx-x fs-5"></i></button>
                                        </div>

                                        <input readonly type="datetime" class="form-control deduct_search_field search_part datepicker" placeholder="поиск" id="search_date_from">
                                    </div>
                                    <div class="col">
                                        <div class="d-flex align-items-baseline justify-content-between">
                                            <label for="" class="form-label">Дата до</label>
                                            <button class="button_no_nothing" onclick="$('#search_date_to').val('').trigger('change')"><i class="bx bx-x fs-5"></i></button>
                                        </div>

                                        <input readonly type="datetime" class="form-control deduct_search_field search_part datepicker" placeholder="поиск" id="search_date_to">
                                    </div>
                                    <div class="col">
                                        <label for="" class="form-label">Комментарий</label>
                                        <input type="text" class="form-control deduct_search_field search_part" placeholder="поиск" id="search_comment">
                                    </div>


                                </div>

                            </div>
                            <div class="table-responsive mt-3 calculate_table beautiful_overflow px-2">
                                <table class="table table-striped table-hover table-sm mb-0">
                                    <thead>
                                    <tr>
                                        <th>Действие</th>
                                        <th>Предмет</th>
                                        <th>Кол</th>
                                        <th>Кому</th>
                                        <th>Дата</th>
                                        <th>Коммент.</th>
                                    </tr>
                                    </thead>
                                    <tbody class="tbody_body">


                                    </tbody>
                                </table>
                                <div class="no_info_place">

                                </div>
                            </div>
                        </div>
                        <div class="card radius-10 col-lg-4">
                            <div class="chart-container-2 my-3">
                                <canvas id="chart2" class="active"></canvas>
                            </div>
                            <div class="calc_total_sum_handler">
                                <h4>0 <i class="bx bx-ruble"></i></h4>
                            </div>
                            <div class="table-responsive calc_total_sum_handler_table">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                    <tr>
                                        <th>Предмет</th>
                                        <th>Кол</th>
                                        <th>Сумма</th>
                                    </tr>
                                    </thead>
                                    <tbody class="counting_table_body">

                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                    <input type="hidden" id="stor_id" value="{{$storage->id}}">
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('user_assets/plugins/chartjs/js/Chart.min.js')}}"></script>
    <script>
        $(document).ready(function (){
            let ctx = document.getElementById("chart2").getContext('2d');
            let chartColors = [
                "#65CDC7",
                "#C9EE77",
                "#ffffff",
                "#F57C7C",
                "#6A808A",
                "#259BF4",
                "#25E0A1",
                "#F6B639",
                "#F2ABAC",
                "#8771D0",
            ];
            let myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels:[],
                    datasets: [{
                        backgroundColor: chartColors,
                        data: [],
                        borderWidth: [0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5,0.5,0.5,0.5,0.5,0.5,0.5]
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutoutPercentage: 60,
                    legend: {
                        position :"bottom",
                        display: false,
                        labels: {
                            fontColor: '#ddd',
                            boxWidth:15
                        }
                    },
                    animation:false,
                    tooltips: {
                        displayColors:false
                    }
                }
            });
            let STORAGE_ID = $('#stor_id').val();
            let TABLE_INFO = $('.tbody_body')
            let no_info_place = $('.no_info_place')
            let counting_place = $('.counting_table_body')
            let total_sum_handler = $('.calc_total_sum_handler')
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
            function addData(chart, label, data) {
                chart.data.labels.push(label);
                chart.data.datasets.forEach((dataset) => {
                    dataset.data.push(data);
                });
                chart.update();
            }

            function removeData(chart) {
                chart.data.labels.pop();
                chart.data.datasets.forEach((dataset) => {
                    dataset.data = [];

                });
                chart.data.labels = [];
                chart.update();
            }
            $('.search_part').on('input change',function (){
                $.ajax({
                    type: "GET",
                    dataType : "json",
                    url: "{{route('storage.get.data.info')}}",
                    data: {
                        item:$('#search_item').val(),
                        count:$('#search_count').val(),
                        who:$('#search_who').val(),
                        date_from:$('#search_date_from').val(),
                        date_to:$('#search_date_to').val(),
                        comment:$('#search_comment').val(),
                        storage_id:STORAGE_ID
                    },
                    success: function(data){
                        console.log(data);
                        if(data.success === 'success'){

                            TABLE_INFO.empty();
                            no_info_place.empty();
                            let deductions = data.data;
                            counting_place.empty();
                            total_sum_handler.empty();
                            removeData(myChart);
                            if(deductions.length){
                                $('.calc_total_sum_handler_table').addClass('active')
                                $('.chart-container-2').addClass('active')
                                $(deductions).each(function (index,value){
                                    if(!value.comment){
                                        value.comment = '';
                                    }
                                    let new_tr = $('<tr></tr>')
                                    let new_d = new Date(value.date);
                                    let type = value.type === 'deduction' ? 'Вычет' : 'Принятие'
                                    new_tr.append('<td>'+ type +'</td>')
                                    new_tr.append('<td>'+ value.item.name+'('+value.item.description+')' +'</td>')
                                    new_tr.append('<td>'+ value.count +'</td>')
                                    new_tr.append('<td>'+ value.to +'</td>')
                                    new_tr.append('<td>'+ new_d.toLocaleDateString() +'</td>')
                                    new_tr.append('<td>'+ value.comment +'</td>')
                                    TABLE_INFO.append(new_tr)
                                })
                                $(data.itog).each(function (index,value){
                                    addData(myChart,value.key,value.value)
                                    let new_tr = $('<tr></tr>')
                                    let col =
                                        new_tr.append('<td><i class="bx bxs-circle me-2" style="color:'+ chartColors[index % chartColors.length] +'"></i>'+ value.key +'</td>')
                                    new_tr.append('<td>'+ value.count +'</td>')
                                    new_tr.append('<td>'+ value.value +'<i class="bx bx-ruble"></i></td>')
                                    counting_place.append(new_tr)
                                })
                                total_sum_handler.html('<h4>'+ data.total_sum +'<i class="bx bx-ruble"></i></h4>')
                                no_info_place.html('<p class="nothing_found_td">запрос "'+ data.message +'"</p>')

                            }else{
                                $('.calc_total_sum_handler_table').removeClass('active')
                                if(data.message){
                                    no_info_place.html('<div class="nothing_found_td">по запросу"'+ data.message +'" ничего не найдено</div>')
                                }

                            }
                        }



                    }
                });
            })
        })
    </script>
    <div class="overlay toggle-icon"></div>
    <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

@endsection
