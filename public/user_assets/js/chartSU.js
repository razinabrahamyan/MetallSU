var colors = [
    '#FF0000',
    '#0000FF',
    '#FFA500',
    '#800080',
    '#008000',
    '#F4E400',
]
let dataLabels =['jsdgfjs dgf','eueueu'];
let vayvay =[];
let vayvayKey =[];

function newElem(key,mey){
    key = key?key:'-'
    mey =  mey?mey:'';
    return ('<tr>' +
        '<td class="chart_label_key">'+ key +'</td>' +
        '<td class="chart_label_comm">'+ mey +'</td>' +
        '</tr>');
}
var options = {
    chart: {
        type: 'area',
        height: 400,
        foreColor: 'red',
        events: {
            markerClick: function(event, chartContext, { seriesIndex, dataPointIndex, config}) {
                console.log(dataPointIndex)
            }
        }
    },
    noData:{
        text:'Нет Данных',
    },
    colors:colors,
    filter:'none',
    tooltip: {
        style: {
            fontSize: '12px',
            fontFamily: undefined
        },
        fillSeriesColor: false,

        x: {
            show:false,
            formatter: function(value, series) {
                return vayvayKey[series.dataPointIndex];

            }
        },
        y:{
            title: {
                formatter:(seriesName) => seriesName ,
            },
            formatter:function(value, series) {
                let main_div = $('<div>' +
                    '<div style="margin-bottom: 5px; color: red;border-radius: 5px; border: 1px solid red;width: min-content;padding: 2px 4px">'+ vayvayKey[series.dataPointIndex] +'</div>' +
                    '<table>' +
                    '<tbody>' +
                    '</tbody>' +
                    '</table>' +
                    '</div>');
                $(vayvay[series.dataPointIndex]).each(function (index,value){
                    main_div.find('tbody').append(newElem(value.value,value.comment))
                })
                // use series argument to pull original string from chart data

                return main_div.html();

            } ,
        },
        z:{
            formatter:function(value, series) {
                return 888;

            }
        },
        items:{
            display:'flex'
        }

    },
    /*tooltip: {
        custom: function({series, seriesIndex, dataPointIndex, w}) {
            let main_div = $('<div>' +
                '<table>' +
                '<tbody>' +
                '</tbody>' +
                '</table>' +
                '</div>');
            $(vayvay[dataPointIndex]).each(function (index,value){
                main_div.find('tbody').append(newElem(value.value,value.comment))
            })
            // use series argument to pull original string from chart data
            return main_div.html();
        }
    },*/
    series:
        [ ],
    xaxis: {
        type:'datetime',
    },

}
setTimeout(function (){
    dataLabels = ['11111']
},10000)
let chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();

function newStatItem (title,sum,color='none',calc_col){
    return (' <div class="col total_stat_item">\n' +
        '                        <div class="card">\n' +
        '                            <div class="card-body">\n' +
        '                                <div class="d-flex mb-3 align-items-center">\n' +
        '                                    <div class="stat_round_icon" style="background: '+ color +'">\n' +
        '\n' +
        '                                    </div>\n' +
        '                                    <p class="mb-0 ps-2 text-white">'+ title +'</p>\n' +
        '                                </div>\n' +
        '\n' +
        '                                <h3>'+ sum +'<span class="stat_cat">:'+ calc_col +'</span></h3>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                    </div>')
}



$(document).ready(function (){
    $('#download_chart_button').click(function (){
        let CHART_BUTTON = $(this);
        /*let sort_col = $('#sort_column').val();*/
        let calc_col = $('#calculate_column').val();
        let tab_id = $('#calc_id').val();
        let format = $('#format_column').val();
        $.ajax({
            type: "GET",
            dataType : "json",
            url: "/get-chart-data",
            data: {
               /* sort_col:sort_col,*/
                calculate_col:calc_col,
                table_id:tab_id,
                format_type:format,
            },
            success: function(data){
                if(data.success === 'success'){
                    console.log(data)
                    $('.stat_data').empty()
                    let chart_values = [];
                    let chart_categories = [];
                    $(data.data).each(function (index,value){
                        chart_values.push({
                            x:value.key+' GMT',
                            y:value.value
                        })
                        vayvay[index] = value.malue
                        vayvayKey[index] = value.keyFormatted
                       // $('.stat_data').append(newStatItem(value.key,value.total,colors[index % colors.length],data.calc_col))
                    })
                    chart.updateOptions({
                        series:[{
                            name:data.calc_col,
                            data:chart_values
                        }],
                        title:{
                            text:data.calc_col,
                            style:{
                                color:'#fff',
                                fontSize: '20px'
                            }
                        }
                    })
                    CHART_BUTTON.html('Обновить Статистику <i class="bx bx-refresh"></i>');
                }

            },
            error:function (err){

            }
        });
    })

})

