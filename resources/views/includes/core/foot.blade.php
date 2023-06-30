<!-- Bootstrap JS -->
<script src="{{asset('user_assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<!--plugins-->
<script src="{{asset('user_assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
<script src="{{asset('user_assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
<script src="{{asset('user_assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
<script src="{{asset('user_assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('user_assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('user_assets/plugins/input-tags/js/tagsinput.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.AreYouSure/1.9.0/jquery.are-you-sure.min.js" integrity="sha512-YuZemcyQ8r4w8tdxIzkJVgWfHrzSQN9PuF18I490DE8H97DOkrt+bolBf3/mve+9t8SLWODBzFCCPxH/vZYqBg==" crossorigin="anonymous"></script>

<script src="{{asset('user_assets/plugins/notifications/js/lobibox.js')}}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ru.min.js" integrity="sha512-tPXUMumrKam4J6sFLWF/06wvl+Qyn27gMfmynldU730ZwqYkhT2dFUmttn2PuVoVRgzvzDicZ/KgOhWD+KAYQQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    let MAIN_SEARCH_ROUTE = "{{route('main.search')}}"
</script>
<div>

</div>
<!--user scriprs-->
<script src="{{asset('user_assets/js/core.js?v=1')}}"></script>
@stack('scripts')

<!--custom scripts-->
<script>
    let arr = @json(session()->get('success_fixed'));
    if (arr) {
        AlertNotification.alertSuccessFixed(arr['title'],arr['subtitle'])
    }
    $('.bootstrap_date_picker').datepicker({
        weekStart: 1,
        autoclose: true,
        todayHighlight: true,
    });
    $('.bootstrap_date_picker').datepicker("setDate", new Date());

    $('#initCalcButton').click(function (){
        $.ajax({
            type: "POST",
            dataType : "json",
            url: "/calculate/prices",
            data: {
                priceColumnId:$('.price_column').val(),
                dateFrom:$('.calc_date_from').val(),
                dateTo: $('.calc_date_to').val(),
                tableId: $('#tableId').val(),
            },
            success: function(data){
                console.log(data);
            }
        });
    });

    //Передаем в js параметр rowId из параметров роутинга для дальнейшей фильтрации таблицы
    @if(!empty($rowId))
        DataTableWithButtons.vars.rowId = {{$rowId}};
    @endif
</script>
<script>
    let is_on_screen = true;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function () {
        DataTableWithButtons.init();
        mailExport.initEvents();

        /*PullToRefresh.init({
            mainElement: '#app',
            onRefresh() {

                window.location.reload();
            },
            distThreshold: 70,
            distReload: 70,
            disMax: 50,
            instructionsReleaseToRefresh:' <h3 class="pull_text">отпустите чтобы обновить</h3>',
            instructionsPullToRefresh:' <h3 class="pull_text">обновить</h3>',
            instructionsRefreshing:'',
            iconArrow:' <div class="font-22 text-white spining_item">	<i class="lni lni-reload"></i> </div>',
            iconRefreshing:' <div class="font-22 text-white spining_item whirling">	<i class="lni lni-reload"></i> </div>',

        });
*/

        //Фильтруем таблицу по id
        if(DataTableWithButtons.vars.rowId > 0){
            DataTableWithButtons.currentDataTable.search('Id-'+DataTableWithButtons.vars.rowId).draw()
        }
    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.js"></script>
@if(request()->route()->getName() !== 'costs.edit.items')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
@endif

@stack('vue')
