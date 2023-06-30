function makeMoney(value, nullable = false){
    value+= '';
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
        if(nullable){
            result = ''
        }else{
            result = '0₽'
        }

    }
    return result;
}
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
function updateFilterActives(){
    $('.custom_dropdown').each(function (index,value){
        let toggler_button = $(value).find('.custom_dropdown_toggler');
        let has_value = false;
        $(value).find('input,select').each(function (index,input){
            if($(input).is('input')){
                if($(input).val()){

                    has_value = true;
                }
            }else if($(input).is('select')){
                if($(input).attr('multiple')){
                    if($(input).val().length){

                        has_value = true;
                    }
                }else{
                    if($(input).val()){

                        has_value = true;
                    }
                }

            }
        })
        if(has_value){
            toggler_button.addClass('has_value')
        }else{
            toggler_button.removeClass('has_value')
        }
    })
}
function splitItemDiv(title, sum, count){
    return $('<div class="d-flex m-1 align-items-center border radius-10 filter_item_result p-1 px-2">' +
        '                    <div class="text-white font-16">'+ title +' - </div>' +
        '            <div class="text-white font-20 ps-2"> '+ sum +'</div>' +
        '            <div class="ps-1 font-16">('+ count +')</div>' +
        '            </div>');
}
$(document).ready(function (){
    $('.stat_money,.should_money').each(function (index,value){
        $(value).text(makeMoney($(value).text()));
    })
    $('.items_multiselect').select2({
        closeOnSelect: false,
        templateSelection: renderCustom,
        width: 'auto',
        language: {
            noResults: function () {
                return $('<p style="color: grey" class="mb-0">Не найдено</p>');
            }
        },
    });
    $('#filter_items_select').on('select2:select',function (){
        let value = $(this).val()
        let select = $(this);
        let has_all_selected = false;
        let has_other_selected = false;
        let new_val = [];
        $(value).each(function (index,value){
            if(value === 'all'){
                has_all_selected = true;
            }else{
                new_val.push(value);
                has_other_selected = true;
            }
        })
        if(has_all_selected && !select.hasClass('all_selected')){
            select.val(['all']).trigger('change').select2('close')
            select.addClass('all_selected')
        }else{
            select.val(new_val).trigger('change')
            select.removeClass('all_selected')
        }


    })
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
    var now = new Date();

    var now_utc = new Date(now.toUTCString().slice(0, -4));
    $('#from').datepicker('setDate',new Date(now_utc.getFullYear(),now_utc.getMonth(),1))
    $('#to').datepicker('setDate',now_utc)
    $('.zero_date_field').click(function (){
        $(this).closest('.datetime_delete_handler').find('.datepicker').val('').trigger('change')
    })
    $('.reset_filters').click(function (){
        let form  = $($(this).data('target'));
        form.find('input[type="text"]').val('');
        var now = new Date();
        var now_utc = new Date(now.toUTCString().slice(0, -4));
        form.find('input[type="datetime"]').datepicker('setDate',now_utc)
        form.find('select:not(.should_stay)').val('').trigger('change');
        form.find('.date_main_part.cashless').removeClass('cashless');
        form.find('#cashless').prop('checked',false);
        updateFilterActives()
    })
    $('.select_2_select').on('change.select2',function (){
        let container = $(this).next('.select2').find('.select2-selection--single')
        if($(this).val()){
            container.addClass('chosen_valid')
        }
        else{
            container.removeClass('chosen_valid')
        }
    })
    $('.show_filter_result_items').click(function (){
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $('.split_desk_handler').removeClass('active');
        }else{
            $(this).addClass('active');
            $('.split_desk_handler').addClass('active');
        }
    })
    $('.statistic_select_input').select2({
        matcher: matchCustom,
        templateSelection: renderCustom,
        width: 'auto',
        language: {
            noResults: function () {
                return $('<p style="color: grey" class="mb-0">Не найдено</p>');
            }
        },
    });

    updateFilterActives()
    $(document).on('change keyup','.custom_dropdown input,.custom_dropdown select',function (){
        updateFilterActives()
    })

    $('#from_money,#to_money').on('keyup',function (){
        let value= $(this).val();
        let changedValue = makeMoney(value,true);
        $(this).val(changedValue);
        $(this).caret(changedValue.length-1)

    })







})
