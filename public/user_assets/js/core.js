var tableModify = new (function () {
    this.vars = {
        last_row_id:$('#lastRowId').val(),
        current_row: $('.data_item').length+1,
        current_col: $('.title_item_input').length,
        is_first:true
    };

    this.newItem = function (data_col, data_row,data_col_value,data_row_value, disable = 'disabled',type = null,columnName = null) {
        let input_type = 'text';
        let classname = '';
        let query_classname = '';
        let append;
        switch (type){
            case 'string':input_type = 'text';
                break;
            case 'price':input_type = 'number';
                break;
            case 'count':input_type = 'number';
                query_classname = 'count';
                break;
            case 'time':input_type = 'datetime';
                classname = 'datepicker';
                break;

        }
        if(type === 'count'){
            append = $('<td class="query_row_wrapper '+ query_classname +'">' +
                '<input type="hidden" class="query_row_id" name="data['+data_row+']['+data_col+'][queryId]" value="" '+disable+'>' +
                '<input type="hidden" name="data['+data_row+']['+data_col+'][rowId]" value="'+ data_row_value +'" '+disable+'>' +
                '<input type="hidden" name="data['+data_row+']['+data_col+'][for]" value="" '+disable+'>' +
                '<input type="hidden" name="data['+data_row+']['+data_col+'][colId]" value="'+ data_col_value +'" '+disable+'>' +
                '<input type="hidden" name="data['+data_row+']['+data_col+'][comment]" data-columname="'+ columnName +'" data-purpose="comment" value="" '+disable+'>' +
                '<input autocomplete="off" class="query_row '+ classname +'" type="'+ input_type +'" name="data['+data_row+']['+data_col+'][value]" value="" '+disable+'>' +
                '</td>');
        }else{
            append = $('<td class="query_row_wrapper '+ query_classname +'">' +
                '<input type="hidden" class="query_row_id" name="data['+data_row+']['+data_col+'][queryId]" value="" '+disable+'>' +
                '<input type="hidden" name="data['+data_row+']['+data_col+'][rowId]" value="'+ data_row_value +'" '+disable+'>' +
                '<input type="hidden" name="data['+data_row+']['+data_col+'][colId]" value="'+ data_col_value +'" '+disable+'>' +
                '<input type="hidden" name="data['+data_row+']['+data_col+'][comment]" data-columname="'+ columnName +'" data-purpose="comment" value="" '+disable+'>' +
                '<input autocomplete="off" class="query_row '+ classname +'" type="'+ input_type +'" name="data['+data_row+']['+data_col+'][value]" value="" '+disable+'>' +
                '</td>');
        }
        append.append(' <div class="comment_sign">\n' +
            '                            </div>')

        return $(append);
    };

    this.newRow = function (data_row) {
        let new_tr = $(' <tr class="data_item editable_row" data-row="' + data_row + '"></tr>');
        new_tr.append('<td>Id - </td>');
        for (let i = 0; i < tableModify.vars.current_col; i++) {
            new_tr.append(tableModify.newItem(i, data_row,$('.col_id_handler').eq(i).val() ,tableModify.vars.last_row_id,'',$('.col_id_handler').eq(i).data('type'),$('.col_id_handler').eq(i).data('name')))
        }

        //Добавляем иконку удаления
        new_tr.append('<td class="qr_row"><i class="fadeIn animated bx bx-barcode"></i></td>');
        new_tr.append('<td class="trash_new_row" onclick="tableModify.removeNewRow(this)"><i class="fadeIn animated bx bx-trash"></i></td>');
        tableModify.vars.last_row_id++;
        return new_tr;
    };

    this.removeNewRow = function (target) {
        $(target).parent().remove()
    }

    this.newTitleField = function (data_col,colValue,colType,colFor = null) {
        return $('<th>' +
            '<input type="hidden" name="column['+data_col+'][type]" value="'+colType+'">' +
            '<input type="hidden" name="column['+data_col+'][for]" value="'+colFor+'">' +
            '<input type="text" required data-col="' + data_col + '" data-role="title" name="column[' + data_col + '][value]" value="'+colValue+'" placeholder="добавьте название колонки" class="title_item_input">' +
            '</th>')
    };

    this.newFootItem = function (data_col) {
        return $('<th data-col="' + data_col + '" class="foot_item"></th>')
    };

    this.initEvents = function () {
        let MAIN_TABLE_BODY = $('#main_table_body');
        let changed_form = false;
        $('#modify_form').areYouSure( {'message':'Your profile details are not saved!'} );
        //Повесим события для редактирования таблиц
        $('#add_table_row').click(function () {
            if(!changed_form){
                $('#modify_form').trigger('checkform.areYouSure');
                changed_form = true;
                console.log(8888)
            }
            let new_row = tableModify.newRow(tableModify.vars.current_row)
            MAIN_TABLE_BODY.append(new_row)
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
            tableModify.vars.current_row++;
            if(tableModify.vars.is_first){
                $('#main_table_body').find('.dataTables_empty').parent().remove();
                tableModify.vars.is_first = false;
            }

        });
        $('#column_type').change(function (){
            if($(this).val() === 'count'){
                $('.for_select_handler').addClass('active')
                $('#column_for').attr('required',true)
            }else{
                $('.for_select_handler').removeClass('active')
                $('#column_for').attr('required',false)
            }
            console.log($(this).val())
        })
        $('#add_table_column_button').click(function () {
            let columnName = $('#columnName').val();
            let colTypeListInput = $('#column_type').val();
            let column_for = $('#column_for').val();
            $('#columnName,#colTypeListInput').removeClass('validation_input_error');

            if (columnName !== "" && colTypeListInput && (colTypeListInput !== "count" || column_for)) {
                $('tr.data_item').each(function () {
                    let disabled = ($(this).hasClass('editable_row')) ? '' : 'disabled';
                    $(this).append(tableModify.newItem(tableModify.vars.current_col, $(this).data('row'), null, null));
                })

                $('#titles_head').append(tableModify.newTitleField(tableModify.vars.current_col, columnName, colTypeListInput,column_for));
                $('#titles_foot').append(tableModify.newFootItem(tableModify.vars.current_col));
                tableModify.vars.current_col++;
                $('#newColModal').modal('hide')
                $('#modify_form').submit();
            } else {
                if (columnName === "") {
                    $('#columnName').addClass('validation_input_error');
                }else{
                    $('#columnName').removeClass('validation_input_error');
                }
                if (!colTypeListInput) {
                    $('#column_type').addClass('validation_input_error');
                }else{
                    $('#column_type').removeClass('validation_input_error');
                }
                if (colTypeListInput === "count" && !column_for){
                    $('#column_for').addClass('validation_input_error');
                }else{
                    $('#column_for').removeClass('validation_input_error');
                }


            }
        });

        $(document).on('input', '.title_item_input', function () {
            let val = $(this).val();
            let col = $(this).data('col');
            $('.foot_item[data-col=' + col + ']').text(val)
        });

        let isMobile = false;
        if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
            || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) {
            isMobile = true;
        }

        let rowEditEvents = function (event, target) {
            let closestRow = target.closest('tr');
            let closestRowInputs = closestRow.find('input');

            closestRowInputs.prop("disabled", false);
            closestRow.addClass('editable_row');
            target.find('.query_row').focus();
        }

        if (!isMobile) {
            $(document).on("dblclick", ".query_row_wrapper", function (event) {
                rowEditEvents(event,$(this));
            });

        } else {
            $(document).on("click", ".query_row_wrapper", function (event) {
                event.preventDefault();
                rowEditEvents(event,$(this));
            });
        }


    };
});

var AlertNotification = new (function () {
    this.alertSuccess = function (message){
        $('.alert_div').remove();
        let desk = $('<div class="alert_div animate__animated card" id="alert_success"> </div>')
        let text = $('<p class="mb-0" id="alert_message"></p>');
        desk.append(text);
        $('#main_wrapper').prepend(desk);
        text.text(message)
        desk.css('display','block');
        desk.addClass('animate__fadeInDown');
        setTimeout(function () {
            desk.removeClass('animate__fadeInDown');
            desk.addClass('animate__bounceOutRight');
        },3000)
    }
    this.alertError = function (message){
        $('.alert_div').remove();
        let desk = $('<div class="alert_div animate__animated card" id="alert_error"> </div>')
        let text = $('<p class="mb-0" id="alert_message"></p>');
        desk.append(text);
        $('#main_wrapper').prepend(desk);
        text.text(message)
        desk.css('display','block');
        desk.addClass('animate__fadeInDown');
        setTimeout(function () {
            desk.removeClass('animate__fadeInDown');
            desk.addClass('animate__bounceOutRight');
        },3000)
    }
    this.alertInfo = function (message){
        $('.alert_div').remove();
        let desk = $('<div class="alert_div animate__animated card" id="alert_info"> </div>')
        let text = $('<p class="mb-0" id="alert_message"></p>');
        desk.append(text);
        $('#main_wrapper').prepend(desk);
        text.text(message)
        desk.css('display','block');
        desk.addClass('animate__fadeInDown');
        setTimeout(function () {
            desk.removeClass('animate__fadeInDown');
            desk.addClass('animate__bounceOutRight');
        },3000)
    }
    this.alertSuccessFixed = function (message,submessage= null){
        $('#alert_success').remove();
        let desk = $('<div class="alert_div animate__animated card" id="alert_success"> </div>')
        let head_div = $(' <div class="d-flex justify-content-between">\n' +
            '                <p class="mb-0" id="alert_message">'+ message +'</p>\n' +
            '                <button class="btn close_alert_button"><i class="bx bx-x"></i></button>\n' +
            '            </div>')
        let sybText= $('<p class="mb-0" id="alert_message"></p>');
        desk.append(head_div);
        if(submessage){
            desk.append(sybText);
        }
        $('#main_wrapper').prepend(desk);
        sybText.text(submessage)
        desk.css('display','block');
        desk.addClass('animate__fadeInDown');

    }
})
var mailExport = new (function () {
    this.vars = {
        emails_badges:'',
        error_current_count:0,
        has_changed_modal_info:true,
        emails_select:$('#emails_select'),
        send_button:$('#send_email_button'),
        emails:[],
        tag_input:$('.bootstrap-tagsinput')

    };
    this.emptyMailModal = function (){
        $('#tab_id').val('')
        $('#additional_message').val('')
        $('.bootstrap-tagsinput').removeClass('error')
    };
    this.initEvents = function () {
        mailExport.vars.emails_badges = $('#emails_select').tagsinput();

        mailExport.vars.emails_badges
        mailExport.vars.emails_select.change(function () {
            mailExport.vars.has_changed_modal_info = true;
        })
        $(document).on('click','.badge.error span[data-role="remove"]',function (){
            mailExport.vars.error_current_count--;


            if(! mailExport.vars.error_current_count){
                $('.bootstrap-tagsinput').removeClass('error')
            }
        })
        $(document).on('click','.badge:not(.error) span[data-role="remove"]',function (){
            if(!$('.bootstrap-tagsinput span').length){
                mailExport.vars.emails_select.empty()
            }

        })
        $(document).on('click','.close_alert_button',function (){
            let desk = $('#alert_success');
            desk.removeClass('animate__fadeInDown');
            desk.addClass('animate__bounceOutRight');
        })
        $('.dt-buttons button').click(function (e){
            e.preventDefault()

        })
        mailExport.vars.send_button.click(function (){
            let select = mailExport.vars.emails_select.children('[selected]');
            select.each(function (item,value){
                mailExport.vars.emails.push($(value).val());
            })
            if(!mailExport.vars.emails.length){
                $('.bootstrap-tagsinput').addClass('error')
                $('.bootstrap-tagsinput').find('input').focus()
            }else{

                if(mailExport.vars.has_changed_modal_info && !mailExport.vars.error_current_count){
                    $('.bootstrap-tagsinput').removeClass('error')
                    $.ajax({
                        type: "POST",
                        dataType : "json",
                        url: "/mail-send",
                        data: {
                            table:$('#tab_id').val(),
                            emails:mailExport.vars.emails,
                            message:$('#additional_message').val(),
                            type:$('#mail_type').val()
                        },
                        success: function(data){
                            if(data.success === 'success'){
                                AlertNotification.alertSuccess(data.message)
                                mailExport.emptyMailModal();
                                mailExport.vars.emails_badges[0].removeAll();
                            }else{
                                mailExport.vars.has_changed_modal_info = false;
                                setTimeout(function (){
                                    $('#sendMailModal').modal('show')
                                },500)
                                console.log(data)

                                let errors = data.errors;
                                mailExport.vars.error_current_count = 0;
                                for(let error in errors){
                                    if(error.indexOf('emails') === 0){
                                        $('.bootstrap-tagsinput').addClass('error')
                                        $('.bootstrap-tagsinput').find('.badge').eq(parseInt(error.slice(7))).addClass('error')
                                        mailExport.vars.error_current_count++
                                    }
                                }
                            }
                        }
                    });
                    $('#sendMailModal').modal('hide')
                }
            }
        })
    }

})
var DataTableWithButtons = new (function () {
    this.vars = {
        rowId: 0, //Используется для фильтрации таблиц
    };
    this.currentDataTable = undefined;

    this.init = function () {
        $('.main_data_table.shouldInit').each(function( ) {
            let TAB_ID = $(this).data('tab')
            let col_length = $(this).data('cols')
            let pr_cols = [];
            for(let i = 0; i < col_length; i++){
                pr_cols.push(i+1)
            }
            let table = $(this).DataTable({
                lengthChange: false,
                ordering:false,
                aaSorting: [],
                buttons: [
                    {
                        extend:'excel', text:'excel',
                        exportOptions: {
                            columns: pr_cols
                        }
                    },
                    {
                        extend:'pdf', text:'pdf',
                        exportOptions: {
                            columns: pr_cols
                        }
                    },
                    {
                        extend:'copy', text:'коп.',
                        exportOptions: {
                            columns: pr_cols
                        }
                    },
                    {
                        extend: 'print',
                        text: 'печать',
                        exportOptions: {
                            columns: pr_cols
                        }
                    },
                    {
                        text: 'mail',
                        className:'main_modal_opener',
                        action: function ( e, dt, node, config ) {
                            $('#sendMailModal').modal('show')
                            $('#tab_id').val(TAB_ID)
                        }
                    }],
                language:{
                    paginate:{
                        next:"след",
                        previous:"пред"
                    },
                    zeroRecords:'нет результатов',
                    emptyTable:"нет результатов"
                }
            });

            DataTableWithButtons.currentDataTable = table;
            table.buttons().container()
                .appendTo($(this).parents('.dataTables_wrapper').find('.col-md-6:eq(0)'));

        });
    }

})
var SweetAlert = new (function (){
    this.rowDeleteAlert = function (target, rowId){
        Swal.fire({
            title: '<p style="color: #ffffff;font-size: 23px;">Вы уверены что хотите удалить?</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Удалить!<i class="bx bx-trash"></i>',
            cancelButtonText: 'Отменить',
            background: 'rgba(0,0,0,0.6)',
            iconColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    dataType : "json",
                    url: window.location.origin+'/table/row/delete',
                    data: {
                        'rowId': rowId,
                        'tableId' : $('[name=tabeleId]').val(),
                    },
                    success: function(data){
                        AlertNotification.alertSuccess('Поле удалено!');
                        target.parent().remove();
                    },
                    error: function (e){
                        console.log('Error!', e.message);
                    }
                });
            }
        })
    }
    this.tableDelete = function (tableId){
        Swal.fire({
            title: '<p style="color: #ffffff;font-size: 23px;">Вы уверены что хотите удалить?</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Удалить!<i class="bx bx-trash"></i>',
            cancelButtonText: 'Отменить',
            background: 'rgba(0,0,0,0.6)',
            iconColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.replace(window.location.origin+'/deleteTable/'+tableId);
            }
        })
    }

});

let PrintQr = new (function (){
    this.printUrlQr = function (elementId){
        var mywindow = window.open('', 'PRINT', 'height=250,width=250');

        mywindow.document.write('<html><head>');
        mywindow.document.write('</head><body >');
        mywindow.document.write(document.getElementById(elementId).innerHTML);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        mywindow.close();

        return true;
    }
});

let dataTableHelper = new (function () {
    this.selectedMoney = [];
    this.exportAllDataTableExcel = function (e, dt, button, config) {
        var self = this;
        var oldStart = dt.settings()[0]._iDisplayStart;
        dt.one('preXhr', function (e, s, data) {
            // Just this once, load all data from the server...
            data.start = 0;
            data.length = 2147483647;
            dt.one('preDraw', function (e, settings) {
                // Call the original action function
                if (button[0].className.indexOf('buttons-copy') >= 0) {
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                    $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                    $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                    $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-print') >= 0) {
                    $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                }
                dt.one('preXhr', function (e, s, data) {
                    // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                    // Set the property to what it was before exporting.
                    settings._iDisplayStart = oldStart;
                    data.start = oldStart;
                });
                // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                setTimeout(dt.ajax.reload, 0);
                // Prevent rendering of the full data to the DOM
                return false;
            });
        });
        // Requery the server with the new one-time export settings
        dt.ajax.reload();
    }
    this.makeMoney = function (value, nullable = false) {
        value += '';
        value = value.replace(/\D/g, '')
        if (value[0] === '0') {
            value = value.substr(1, value.length);
        }
        let result = '';
        let check = true;
        while (check) {
            if (value.length > 3) {
                result = ',' + value.substr(value.length - 3, 3) + result;
                value = value.substr(0, value.length - 3)
            } else {
                result = value + result;
                check = false;
            }
        }
        if (result.length) {
            result += '₽'
        } else {
            if (nullable) {
                result = ''
            } else {
                result = '0₽'
            }

        }
        return result;
    }
    this.sumColumnMoney = function (target, selector) {
        $('.calc_handler').addClass('triggered');
        let current_sum = $(selector).text();
        current_sum = current_sum.replace(/\D/g, '');
        current_sum = parseInt(current_sum);

        let moneyIndex = target.find('span').attr('data-money-index');
        if (target.closest('td').hasClass('activated')) {
            var index = dataTableHelper.selectedMoney.indexOf(moneyIndex);
            if (index !== -1) {
                dataTableHelper.selectedMoney.splice(index, 1);
            }

            target.closest('td').removeClass('activated')
            let this_one = target.closest('td').text();
            this_one = this_one.replace(/\D/g, '');
            this_one = parseInt(this_one);
            current_sum -= this_one;
        } else {
            dataTableHelper.selectedMoney.push(moneyIndex);
            target.closest('td').addClass('activated')
            let this_one = target.closest('td').text();
            this_one = this_one.replace(/\D/g, '');
            this_one = parseInt(this_one);
            current_sum += this_one;
        }

        $(selector).text(dataTableHelper.makeMoney(current_sum))
    }

    this.recoverActivesOnTableChange = function () {
        dataTableHelper.selectedMoney.map(function (value) {
            let elem = $('td:has([data-money-index=' + value + '])');
            if (elem.is(":visible")) {
                elem.addClass('activated');
            }
        });
    }

    this.resetCalcButton = function () {
        $('#calc_result').text('0₽');
        $('td.activated').removeClass('activated');
        $('.calc_handler').removeClass('triggered');
        dataTableHelper.selectedMoney = [];
    }
});

$(document).ready(function () {
    "use strict";

    var $body = $("body");

    $body.on("mousedown.drag", ".draggable-handle", function (event) {
        var $draggable = $(this).closest(".draggable"),
            initX = event.pageX,
            initY = event.pageY,
            top = $draggable.offset().top,
            left = $draggable.offset().left;

        $body.on("mousemove.drag", function (event) {
            $draggable.css({
                top: top + event.pageY - initY,
                left: left + event.pageX - initX
            });
        });

        $body.one("mouseup.drag", function (event) {
            $body.off("mousemove.drag");
        });
    });
    if ($('#success').val()) {
        AlertNotification.alertSuccess($('#success').val())
    }

    let iter = 1;
    $('.animate__animated').each(function (){
        let item = $(this)
        if(item.data('shouldclass')){
            setTimeout(function (){
                item.css('display','block').addClass()
            },iter*30)
            iter++;
        }

    })
    $(document).on('click', function (event) {
        if (!$(event.target).closest('#main_search_div').length) {
            $('#search_result_div').addClass('animate__fadeOut');
        }
    });
    let search_result_colors = ['#ffbc00','#00ff08','#0095ff','#fb00ff','#ff1919'];
    $('#main_search_input').keyup(function () {
        console.log($('#search_result_div'))
        $('#search_result_div').addClass('animate__fadeIn active');
        $('#search_result_div').removeClass('animate__fadeOut');
        let text = $(this).val();
        if(text){
            $.ajax({
                type: "GET",
                dataType : "json",
                url: MAIN_SEARCH_ROUTE,
                data: {'text': text},
                success: function(data){
                    console.log(data)
                    if(data.success === 'success'){
                        $('#search_result_div').empty()
                        if(data.link === 'store'){
                            let tables = data.tables;
                            if(tables.length){
                                $(tables).each(function (item,value){
                                    $('#search_result_div').append(' <div class="search_result_item">\n' +
                                        '                            <i class="bx bx-grid-alt"></i>\n' +
                                        '                            <a href="/'+ data.link +'/'+ value.id +'">'+ value.title+'/' + '<span class="search_result_category">' + value.category.title +' </span> </a>\n' +
                                        '                        </div>')
                                })
                            }else{
                                $('#search_result_div').append('<div class="search_result_item">\n' +
                                    '                            <p class="no_result" >Нет Результатов</p>\n' +
                                    '                        </div>')
                            }

                        }else{
                            let responsibles = data.responsibles;
                            let items = data.items;
                            let workers = data.workers;
                            if(responsibles.length || items.length || workers.length){
                                if(items.length){
                                    $('#search_result_div').append(' <div class="search_result_item heading">\n' +
                                        '<p>Предмет Услуга</p>'+
                                        '                        </div>')
                                    $(items).each(function (item,value){
                                        let icon = 'bx-grid-alt';
                                        let main_div = $('<div class="search_result_item"><i class="bx '+ icon +'"></i></div>');
                                        let main_link = $('<a href="/product-item/'+ value.id +'">'+ value.title + '</a>')
                                        if(value.parent_id){
                                            main_link.append('/<span class="search_result_category_admin">'+ value.parent_item.title +'</span>');
                                        }
                                        main_div.append(main_link);


                                        $('#search_result_div').append(main_div)
                                    })
                                }
                                if(responsibles.length){
                                    $('#search_result_div').append(' <div class="search_result_item heading">\n' +
                                        '<p>Ответственные</p>'+
                                        '                        </div>')
                                    $(responsibles).each(function (item,value){
                                        let icon = 'bx-user';
                                        switch (value.type.name.toUpperCase()){
                                            case 'МАШИНА': icon = 'bx-car';
                                                break;
                                            case 'ПЛОЩАДКА': icon = 'bx-building-house';
                                                break;
                                        }
                                        $('#search_result_div').append(' <div class="search_result_item">\n' +
                                            '                            <i class="bx '+ icon +'" style="color: '+ search_result_colors[value.type.id-1] +'"></i>\n' +
                                            '                            <a href="/responsible/'+ value.id +'">'+ value.name+'/' + '<span style="color: '+ search_result_colors[value.type.id-1] +'" class="search_result_category_admin">' + value.type.name +' </span> </a>\n' +
                                            '                        </div>')
                                    })
                                }
                                if(workers.length){
                                    $('#search_result_div').append(' <div class="search_result_item heading">\n' +
                                        '<p>Сотрудники</p>'+
                                        '                        </div>')
                                    $(workers).each(function (item,value){
                                        let icon = 'bxs-user';
                                        $('#search_result_div').append(' <div class="search_result_item">\n' +
                                            '                            <i class="bx text-white '+ icon +'"></i>\n' +
                                            '                            <a href="/workers/homepage?id='+ value.id +'">'+ value.name+' </a>\n' +
                                            '                        </div>')
                                    })
                                }


                            }else{
                                $('#search_result_div').append('<div class="search_result_item">\n' +
                                    '                            <p class="no_result" >Нет Результатов</p>\n' +
                                    '                        </div>')
                            }

                        }

                    }
                }
            });
        }else{
            $('#search_result_div').empty()
            $('#search_result_div').removeClass('animate__fadeIn active');
            $('#search_result_div').addClass('animate__fadeOut');
        }

    });

    /*Запросы на удаления*/
    //Soft-Delete для поля
    $('.trash_row').click(function () {
        SweetAlert.rowDeleteAlert($(this),$(this).parent().find('.hiddenRowId').val());
    });
    //Soft-Delete для таблиц
    $('.table-delete-btn').click(function () {
        SweetAlert.tableDelete($('[name="tabeleId"]').val());
    });

    /*Замена темы по куке*/
    if(localStorage.getItem('activeTheme') !== null){
        $('#theme'+ localStorage.getItem('activeTheme')).click()
    }
    /*$.fn.datepicker.defaults.format = 'dd-mm-yy';*/
    //Ставим на поля datepicker
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

    /*Events*/
    $(document).on('click', 'td:has(.should_money)', function () {
        dataTableHelper.sumColumnMoney($(this), '#calc_result')
    })

    $(document).on('click', '#reset_calc_button', function () {
        dataTableHelper.resetCalcButton();
    })
    /*Events*/
    /*PWA*/
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('https://m1-su.ru/serviceworker.js').then(function (registration) {
            // console.log('PWA: ServiceWorker registration successful with scope: ', registration.scope);
        }, function (err) {
            console.log('PWA: ServiceWorker registration failed: ', err);
        });
    } else {
        console.log('ServiceWorker Not Found');
    }

    //Определим кнопку
    const installApp = document.getElementById('pwa-install');
    var deferredPrompt;

    //Событие вызова окна установки
    window.addEventListener('beforeinstallprompt', function (e) {
        $('#pwa-install').removeClass('d-none')
        e.preventDefault();
        deferredPrompt = e;
        return false;
    });

    installApp.addEventListener('click', function () {
        if (deferredPrompt !== undefined) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(function (choiceResult) {
                if (choiceResult.outcome == 'dismissed') {
                    //
                } else {
                    $('#pwa-install').hide()
                }
                deferredPrompt = null;
            });
        }
    });

    if (deferredPrompt !== undefined) {
        //Если приложение нельзя установить или оно уже установлено, то скрываем кнопку
        $('#pwa-install').removeClass('d-none')
    }
    /*PWA*/
});
function previewFile() {
    let preview = document.getElementById('profile_image');
    let file    = document.querySelector('input[type=file]').files[0];
    let reader  = new FileReader();
    reader.addEventListener("load", function () {
        preview.src = reader.result;
    }, false);
    if (file) {
        reader.readAsDataURL(file);
    }
}

function setCookie(name, value, exp, path = '', domain = '', secure = '') {
    var date = new Date(new Date().getTime() + exp);
    var cookie_string = name + "=" + escape(value);
    if (exp) {
        cookie_string += ";expires=" + date.toUTCString();
    }
    if (path !== '') cookie_string += ";path=" + escape(path);
    if (domain !== '') cookie_string += ";domain=" + escape(domain);
    if (secure !== '') cookie_string += ";secure";
    document.cookie = cookie_string;
}

function getCookie(cookie_name) {
    var results = document.cookie.match('(^|;)?' + cookie_name + '=([^;]*)(;|$)');
    if (results) return (unescape(results[2])); else return null;
}

function deleteCookie(name) {
    document.cookie = name + "=" + ";expires=Thu,01 Jan 1970 00:00:01 GMT";
}

$('#image').change(function () {
    previewFile();
});

$('#image_upload_button').click(function () {
    $('#image').trigger('click');
});


