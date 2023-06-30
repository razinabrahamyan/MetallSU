@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
                <div class="breadcrumb-title pe-3">Редактировать Выплату</div>
            </div>
            <div class="w-100">
                <form method="POST" action="{{route('cost.delete')}}" id="delete_cost_form">
                    @csrf
                    <input type="hidden" name="cost" value="{{$cost->id}}">
                    <div class="">
                        <button type="button" id="delete_cost_button" class="d-block ms-auto btn btn-danger">Удалить</button>
                    </div>
                </form>
            </div>
            <div class="card position_adding_card edit_card col-8 should_animate animate__animated ">
                <div class="card-body">
                    <div class="card-title">
                        <form action="{{route('cost.update.save')}}" method="post">

                            @csrf
                            <div class="row m-0">
                                <div class="col-12 m-0 p-2">
                                    <div class="card-title">
                                        <div>
                                            <h5></h5>
                                        </div>
                                        <input type="hidden" id="cost_id" name="cost_id" value="{{$cost->id}}">
                                        <input type="hidden" id="cost_id" name="prev_page" value="{{$prev_page}}">
                                        <div class=" align-items-end vers_2_main">
                                            <div class="row date_main_part">
                                                <div class="col-6">
                                                    <div class="form-group ">
                                                        <label for="date">Дата <span class="required_label">*</span></label>
                                                        <input value="{{\Carbon\Carbon::parse($cost->date)->format('d.m.Y')}}" autocomplete="off" required class="form-control datepicker" type="datetime" id="date" placeholder="выберите дату" name="date">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="position-relative">
                                                            <label for="item">Предмет / Услуга <span class="required_label">*</span></label>
                                                            <input type="hidden" class="new_value" name="new_value">
                                                            <select class="item_select select_2_select" id="item" name="item" required>
                                                                <option></option>
                                                                @foreach($items as $item)
                                                                    @if(count($item->subItems))
                                                                        <optgroup label="{{$item->title}}">
                                                                            @foreach($item->subItems as $subItem)
                                                                                <option @if($subItem->id === $cost->item_id) selected @endif data-match="{{$item->title.'|'}}@foreach($subItem->defaultResponsibles as $defaultResponsible){{$defaultResponsible->name.'|'}}@endforeach" value="{{$subItem->id}}">{{$subItem->title}}</option>
                                                                            @endforeach
                                                                        </optgroup>
                                                                    @else
                                                                        <option @if($item->id === $cost->item_id) selected @endif data-match="@foreach($item->defaultResponsibles as $defaultResponsible){{$defaultResponsible->name.'|'}}@endforeach" value="{{$item->id}}">{{$item->title}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row date_main_part">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="sum">Сумма (₽) <span class="required_label">*</span></label>
                                                        <input value="{{$cost->value}}" required type="text" placeholder="Введите сумму" class="form-control sum_input" id="sum" name="sum">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="item">Тип оплаты</label>
                                                        <div class="position-relative">
                                                            <select class="select_2_select" id="cashless" name="cashless">
                                                                <option @if($cost->cashless == '0') selected @endif value="0" class="ppp">Наличные</option>
                                                                <option @if(!$cost->cashless == '1') selected @endif value="1" class="ppp">Безнал</option>
                                                                <option @if(!$cost->cashless == '2') selected @endif value="2" class="ppp">Карта</option>
                                                                <option value="2" class="ppp">Карта</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="count">Количество</label>
                                                        <input type="number" value="{{$cost->count}}" class="form-control" id="count" name="count">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="comment">Комментарий</label>
                                                        <textarea id="comment" name="comment" class="form-control comment_input"   cols="30" rows="4">{{$cost->comment}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row  position-relative pt-3">
                                                <div class="additional_sign">дополнительно</div>
                                                @foreach($responsibles as $responsibleGroup)
                                                    <div class="col-4 additional_selects mt-2">
                                                        <div class="form-group">
                                                            <label for="item">{{$responsibleGroup->name}}</label>
                                                            <div>
                                                                @if(Arr::exists($cost->groupedResponsibles,$responsibleGroup->id))
                                                                    <input type="hidden" value="{{$cost->groupedResponsibles[$responsibleGroup->id]->first()->id }}" name="additionals[{{$loop->index}}][old_value]">
                                                                @else
                                                                    <input type="hidden" value="" name="additionals[{{$loop->index}}][old_value]">
                                                                @endif
                                                                <select class="additional_select_input select_2_select" name="additionals[{{$loop->index}}][value]" >
                                                                    <option selected value="" class="ppp" data-mix="222"> Не Указывать</option>
                                                                    @foreach($responsibleGroup->responsibles as $responsible)
                                                                        <option @if(Arr::exists($cost->groupedResponsibles,$responsibleGroup->id) && $cost->groupedResponsibles[$responsibleGroup->id]->first()->id === $responsible->id) selected @endif value="{{$responsible->id}}">{{$responsible->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div>
                                            <div><button type="submit" class="btn btn-light pos_save text-white ms-3 mt-4">Сохранить<i class="bx bx-save"></i></button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="before_loading"><div class="spinner-border" role="status"> <span class="visually-hidden">Loading...</span>
        </div></div>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/caret/1.3.7/jquery.caret.min.js" integrity="sha512-DR6H+EMq4MRv9T/QJGF4zuiGrnzTM2gRVeLb5DOll25f3Nfx3dQp/NlneENuIwRHngZ3eN6w9jqqybT3Lwq+4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function (){
                $('.position_adding_card').removeClass('should_animate').addClass('animate__fadeIn')
                $('.before_loading').remove()
                $('.sum_input').val(makeMoney($('.sum_input').val()))
            })
            $('.sum_input').on('keydown',function (e){
                if(!(e.which >= 48 && e.which <= 57) && !(e.which >= 96 && e.which <= 105) && e.which !== 8){
                    e.preventDefault();
                }

            })
            function renderCustom(data){
                if ($(data.element).data('parent')) {
                    return $(data.element).data('parent')+'/'+data.text
                }
                return data.text
            }
            function matchCustom(params, data) {
                // If there are no search terms, return all of the data
                if ($.trim(params.term) === '') {
                    return data;
                }
                // Do not display the item if there is no 'text' property
                if (typeof data.text === 'undefined') {
                    return null;
                }
                // `params.term` should be the term that is used for searching
                // `data.text` is the text that is displayed for the data object
                if (data.text.toUpperCase().indexOf(params.term.toUpperCase()) > -1 ) {
                    var modifiedData = $.extend({}, data, true);
                    // You can return modified objects from here
                    // This includes matching the `children` how you want in nested data sets
                    return modifiedData;
                }
                if ($(data.element).data('match') && $(data.element).data('match').toUpperCase().indexOf(params.term.toUpperCase()) > -1 ) {
                    var modifiedData = $.extend({}, data, true);
                    // You can return modified objects from here
                    // This includes matching the `children` how you want in nested data sets
                    return modifiedData;
                }
                if (typeof data.children !== 'undefined') {
                    var filteredChildren = [];
                    $.each(data.children, function (idx, child) {
                        if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) > -1 || ($(child.element).data('match') && $(child.element).data('match').toUpperCase().indexOf(params.term.toUpperCase()) > -1)) {
                            filteredChildren.push(child);
                        }
                    });
                    // If we matched any of the timezone group's children, then set the matched children on the group
                    // and return the group object
                    if (filteredChildren.length) {
                        var modifiedData = $.extend({}, data, true);
                        modifiedData.children = filteredChildren;

                        // You can return modified objects from here
                        // This includes matching the `children` how you want in nested data sets
                        return modifiedData;
                    }
                }
                // `data.children` contains the actual options that we are matching against
                // Return `null` if the term should not be displayed
                return null;
            }
            $('.item_select').select2({
                matcher: matchCustom,
                templateSelection: renderCustom,
                width: 'auto',
                language: {
                    noResults: function () {
                        return $('<p style="color: grey" class="mb-0">Не найдено</p>');
                    }
                },
                placeholder: 'предмет / услуга'
            });
            $('.select_2_select').select2({
                matcher: matchCustom,
                templateSelection: renderCustom,
                width: 'auto',
                language: {
                    noResults: function () {
                        return $('<p style="color: grey" class="mb-0">Не найдено</p>');
                    }
                },
                placeholder: 'предмет / услуга'
            });
            $('.additional_select_input').select2({
                matcher: matchCustom,
                templateSelection: renderCustom,
                width: 'auto',
                language: {
                    noResults: function () {
                        return $('<p style="color: grey" class="mb-0">Не найдено</p>');
                    }
                },
            });
            $('#delete_cost_button').click(function (){
                if(confirm("Вы действительно хотите удалить выплату ?")){
                    $('#delete_cost_form').submit()
                }
            })
            $('.sum_input').on('keyup',function (e){
                let value= $(this).val();
                let changedValue = makeMoney(value);
                $(this).val(changedValue);
                $(this).caret(changedValue.length-1)

            })
            $('.should_money').each(function (index,value){
                let a = $(value).val();
                if(a){
                    $(value).val(makeMoney(a))
                }
            })
            function makeMoney(value){
                value = value.replace(/\D/g,'')
                if(value[0] === '0'){
                    value = value.substr(1,value.length);
                }
                let result = '';
                let check = true;
                while(check){
                    if(value.length>3){
                        result = ',' + value.substr(value.length-3,3)+result;
                        value = value.substr(0,value.length-3)
                    }else{
                        result = value + result;
                        check = false;
                    }
                }
                if(result.length){
                    result += '₽'
                }else{
                    result = '0₽'
                }
                return result;
            }
        </script>
    @endpush



@endsection

