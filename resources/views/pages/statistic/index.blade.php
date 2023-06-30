@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content statistic_tab">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
                <div class="breadcrumb-title pe-3">{{$responsible->name}}</div>
            </div>

            <div class="row ">
                <div class="col-lg-3 col-12 my-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title stat_card_sums">
                                <div>
                                    <h5>Затраты за этот месяц</h5>
                                </div>
                                <div>

                                    <h3 ><span class="should_money">{{$responsible->costs->where('date','>=',now()->firstOfMonth())->sum('value')}}</span></h3>
                                </div>
                                <div>
                                    <h5>Затраты за все время</h5>
                                </div>

                                <div>
                                    <h3 class="should_money">{{$responsible->costs->sum('value')}}</h3>
                                </div>

                                <div class="chart-container-2 mx-auto my-3">
                                    <canvas id="chart_round" class="active"></canvas>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-9 col-12 my-3">
                    <div class="card h-100">
                        <div class="card-body pb-0">
                            <div class="card-title">
                                <div class="mt-3">
                                    <div id="chart"></div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
            <div class="p-2">
                <form id="stat_form">
                    <div class="">
                        <div class="row m-0 align-items-center justify-content-lg-start justify-content-between div_for_filters">
                            <div class="col-auto order-1 me-2 filter_title_handler">
                                <h4>Фильтры</h4>
                            </div>
                            <div class="col-lg-auto col-12 border radius-10 py-2 px-1 filter_but_handler order-3 order-lg-2">
                                <input type="hidden" name="responsible" value="{{$responsible->id}}">
                                <div class="d-flex justify-content-between filter_form_buttons flex-wrap">
                                    <div class="custom_dropdown position_dropdown col-md-auto col-6 col-sm-auto">
                                        <button  type="button" class="btn btn-light button_no_nothing custom_dropdown_toggler m-1"><i class="bx bx-calendar"></i><span>Дата</span></button>
                                        <div class="custom_dropdown_menu px-2 " >
                                            <label class="group_label">введите даты</label>
                                            <div class="form-group">
                                                <label for="from" class="ps-2">От</label>
                                                <div class="datetime_delete_handler">
                                                    <input readonly placeholder="выберите дату" autocomplete="off" class="form-control datepicker" type="text" id="from" name="from">
                                                    <button type="button" class="zero_date_field">x</button>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="to" class="ps-2">До</label>
                                                <div class="datetime_delete_handler">
                                                    <input readonly placeholder="выберите дату" autocomplete="off" class="form-control datepicker" type="text" id="to" name="to">
                                                    <button type="button" class="zero_date_field">x</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom_dropdown position_dropdown col-md-auto col-6 col-sm-auto ">
                                        <button  type="button" class="btn btn-light button_no_nothing custom_dropdown_toggler m-1"><i class="bx bx-ruble"></i>Сумма</button>
                                        <div class="custom_dropdown_menu px-2 " >
                                            <label class="group_label">введите суммы</label>
                                            <div class="form-group">
                                                <label for="from_money" class="ps-2">От</label>
                                                <input class="form-control" type="text" id="from_money" name="from_money">
                                            </div>
                                            <div class="form-group">
                                                <label for="to_money" class="ps-2">До</label>
                                                <input class="form-control" type="text" id="to_money" name="to_money">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom_dropdown col-md-auto col-6 col-sm-auto ">
                                        <button type="button" class="btn btn-light button_no_nothing m-1 custom_dropdown_toggler"><i class="bx bx-cylinder"></i><span>Предмет/Услуга</span></button>
                                        <div class="custom_dropdown_menu px-2">
                                            <div class="form-group stat_item_select">
                                                <label class="group_label">выберите предметы/услуги</label>
                                                <select   class="items_multiselect form-select select_2_select" name="items[]" multiple="multiple" id="filter_items_select">
                                                    <option value="all">Выбрать все</option>
                                                    @foreach($items as $item)
                                                        @if(count($item->subItems))
                                                            <optgroup value="ATZ" class="select2-result-selectable" label="{{$item->title}}">
                                                                @foreach($item->subItems as $subItem)
                                                                    <option data-parent="{{$item->title}}" data-match="{{$item->title.'|'}}@foreach($subItem->defaultResponsibles as $defaultResponsible){{$defaultResponsible->name.'|'}}@endforeach" value="{{$subItem->id}}">{{$subItem->title}}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @else
                                                            <option data-match="@foreach($item->defaultResponsibles as $defaultResponsible){{$defaultResponsible->name.'|'}}@endforeach" value="{{$item->id}}">{{$item->title}}</option>
                                                        @endif

                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom_dropdown col-md-auto col-6 col-sm-auto ">
                                        <button type="button" class="btn btn-light button_no_nothing m-1 custom_dropdown_toggler"><i class="bx bx-folder-plus"></i>Ответственные</button>
                                        <div class="custom_dropdown_menu px-2">
                                            <div class="d-flex flex-wrap selects_min">
                                                @foreach($statistics as $additional)
                                                    <div class="stat_select_box_handler">
                                                        <div class="border m-2 radius-10 p-1 stat_select_box">
                                                            <h6 class=" pb-2">{{$additional->name}}</h6>
                                                            <div class="form-group">
                                                                <select  class="statistic_select_input form-select select_2_select" name="additionals[]" >
                                                                    <option selected value="" class="ppp" data-mix="222"> Не Указывать</option>

                                                                    @foreach($additional->responsibles as $responsible)
                                                                        <option  value="{{$responsible->id}}">{{$responsible->name}}</option>
                                                                    @endforeach

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-auto reset_block order-2 order-lg-3">
                                <button type="button" data-target="#stat_form"  class="btn reset_filters btn-light radius-30 m-1"><i class="bx bx-x-circle"></i>Сбросить</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="button" id="apply_filters" class="btn btn-light">
                            <i class="bx bx-refresh static"></i>
                            <div class="spinner-border dynamic spinner-border-sm me-2 ms-1" role="status">
                            </div>
                            Применить
                        </button>
                    </div>


                </form>
            </div>

            <div class="card">

                <div class="card-body">
                    <div class="card-title">
                        <div class="d-none" id="filter_result_outcome">
                            <div class="border radius-10 p-2">
                                <h5>Общая сумма по указанным фильтрам <span class="filter_from_back"></span></h5>
                                <div class="d-flex align-items-center ms-2">
                                    <h3 class="mb-0" id="filter_request_sum"></h3>
                                    <button class="btn button_no_nothing show_filter_result_items border ms-2">
                                        <i class="bx me-0 bx-exclude show_items_icon"></i>
                                        <i class="bx me-0 bx-unite hide_items_icon text-white"></i>
                                    </button>
                                </div>
                                <div class="split_desk_handler">
                                    <div class="d-flex flex-wrap" id="split_desk">

                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="table-responsive">

                            <table id="small_table" class="table position_table table-striped table-bordered beautiful_overflow">
                                <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th class="responsible_th">Пред./Услуга</th>
                                    <th>Кол.</th>
                                    <th >Сумма</th>
                                    @foreach($additionalColumns as $additional)
                                        <th @if($loop->index%2 === 0) class="responsible_th" @endif>{{$additional->name}}</th>
                                    @endforeach

                                    <th>Комментарий</th>
                                    <th>Ред.</th>
                                </tr>
                                </thead>
                                <tbody id="my_tbody">
                                @foreach($costs as $cost)
                                    <tr>
                                        <td>
                                            <div class="position-relative">
                                                {{\Carbon\Carbon::parse($cost->date)->format('d.m.Y')}}
                                                <div class="table_comment_desc ">
                                                    <div class="inner">
                                                        <div>
                                                            <span class="time">{{\Carbon\Carbon::parse($cost->created_at)->format('H:i')}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{\Carbon\Carbon::parse($cost->created_at)->format('d/m/Y')}}</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$cost->item->title}}</td>
                                        <td>{{$cost->count}}</td>
                                        <td class=" @if($cost->cashless === '1') cashless @endif">{{$cost->formatted_value}}</td>
                                        @foreach($additionalColumns as $additional)
                                            @if(Arr::exists($cost->responsibleValues,$additional->id))
                                                <td>{{$cost->responsibleValues[$additional->id]->first()->name}}</td>
                                            @else
                                                <td></td>
                                            @endif

                                        @endforeach
                                        <td>{{Str::limit($cost->comment, $limit = 50, $end = ' ...')}}</td>
                                        <td><a href="{{route('edit.cost.page',$cost->id)}}" class="font-18"><i class="bx bx-edit"></i></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
            <div class="card">
                <div class="card-body">
                    <div class="card-title">


                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/caret/1.3.7/jquery.caret.min.js" integrity="sha512-DR6H+EMq4MRv9T/QJGF4zuiGrnzTM2gRVeLb5DOll25f3Nfx3dQp/NlneENuIwRHngZ3eN6w9jqqybT3Lwq+4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('user_assets/plugins/apexcharts-bundle/js/apexcharts.min.js')}}"></script>
    <script src="{{asset('user_assets/plugins/chartjs/js/Chart.min.js')}}"></script>
    <script src="{{asset('user_assets/js/statistics.js')}}"></script>
    <script src="{{asset('user_assets/js/customDropdown.js')}}"></script>
    <script>
        $(document).ready(function (){
            let table = $('.position_table').DataTable({
                ordering:false,
                scrollY:   '600px',
                scrollX: true,
                scrollCollapse: true,
                aLengthMenu: [[15, 20, 25, -1], [15, 20, 25, "Все"]],
                aaSorting: [],
                iDisplayLength: 15,
                buttons: [
                    {
                        extend:'excel', text:'excel',

                    },
                    {
                        extend:'pdf', text:'pdf',

                    },
                    {
                        extend:'copy', text:'коп.',

                    },
                    {
                        extend: 'print',
                        text: 'печать',

                    },
                ],
                language:{
                    paginate:{
                        next:"след",
                        previous:"пред"
                    },
                    zeroRecords:'нет результатов',
                    emptyTable:"нет результатов",
                    lengthMenu: "Показать _MENU_ строк"
                }
            });
            table.buttons().container()
                .appendTo($('#small_table_wrapper').find('.row:eq(0)').find('.col-md-6:eq(0)'));
            $('#apply_filters').click(function (){
                let button = $(this);
                button.addClass('non_active').attr('disabled',true)
                $.ajax({
                    type: "GET",
                    dataType : "json",

                    url: "{{route('statistic.filter.responsibles')}}"+"?"+$('#stat_form').serialize(),
                    data: {

                    },
                    success: function(data){

                        $('#filter_result_outcome').removeClass('d-none')
                        button.removeClass('non_active').attr('disabled',false)
                        if(data.success === 'success'){
                            table.clear().rows.add(data.costs).draw()
                            $('#filter_request_sum').text(data.sum + ' (' + data.count + ')' + ' || ' + 'Нал:' + data.cash + ',Безнал:' + data.cashless)
                            $('#split_desk').empty();
                            $(data.split_item_arr).each(function (index,value){
                                $('#split_desk').append(splitItemDiv(value.title,value.value,value.count))
                            })
                            $('.filter_from_back').text('( ' + data.show_filters + ' )');

                        }
                    },
                    error:function (err){
                        console.log(err)
                    }
                });
            })
            let ctx = document.getElementById("chart_round").getContext('2d');
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
            let round_chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels:[],
                    datasets: [{
                        backgroundColor: chartColors,
                        data: [],
                        borderWidth: 0.5
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
                        displayColors:false,
                        callbacks: {
                            label: function(tooltipItem, data) {


                                return data.labels[tooltipItem.index] + ':' + makeMoney(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]);
                            }
                        }
                    }
                },
                chart:{
                    width: 'auto'
                }
            });
            round_chart.canvas.parentNode.style.width = '200px';
            round_chart.canvas.parentNode.style.height = '200px';
            let round_chart_costs = @json($round_chart_costs);
            $(round_chart_costs).each(function (index,value){
                addData(round_chart,value.key,value.value)
            })
            function addData(chart, label, data) {
                chart.data.labels.push(label);
                chart.data.datasets.forEach((dataset) => {
                    dataset.data.push(data);
                });
                chart.update();
            }
            let chart_data =@json($chart_data);
            var options = {
                chart: {
                    foreColor: 'white',
                    height: 260,
                    type: 'area',
                    zoom: {
                        enabled: false
                    },
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    area: {
                        isRange: true
                    }
                },
                stroke: {
                    curve: 'straight'
                },
                grid: {
                    show: true,
                    borderColor: 'rgb(10,64,172)',
                    strokeDashArray: 4,
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val,data) {

                            let head =  '<div class="statistic_chart_header">'+ makeMoney(val) +'</div><div>'

                            return head;
                        }
                    },
                },
                colors: ["white"],
                series: [{
                    name: 'Затраты',
                    data: chart_data,
                }],
                title: {
                    text: 'Статистика',
                    align: 'left',
                    style: {
                        fontSize: '20px'
                    }
                },
                xaxis: {
                    type: 'datetime',
                },
            }
            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();


        })
    </script>

@endsection
