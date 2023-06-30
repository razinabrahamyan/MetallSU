function makeChosen(){
    $(".chosen-select").chosen({
        disable_search_threshold: 200,
        max_selected_options:1,
        allow_single_deselect:true,
        width:'100%',
    });
    return true
}
function newPricesWidget(){
    return $('<div class="widget-content">\n' +
        '                                    <div class="default-form">\n' +
        '                                        <label>Основная Категория </label>\n' +
        '                                    </div>\n' +
        '                                    <div class="main_category_handler">\n' +
        '                                        <div class="main_category">\n' +
        '                                            <select name="prices[0][category]" class="chosen-select main_category_select">\n' +
        '                                            </select>\n' +
        '                                            <div class="default-form flexible_desc" data-id="0">\n' +
        '                                                <div class="row pl-2  main_subcategory">\n' +
        '                                                    <div class="col-lg-5 col-8 form-group sub_select">\n' +
        '                                                        <label class="required" for="">Подкатегория</label>\n' +
        '                                                        <select required name="prices[0][0][subcategory]" class="chosen-select  subcategory_select">\n' +
        '                                                        </select>\n' +
        '                                                    </div>\n' +
        '                                                    <div class="col-lg-5 col-8 form-group price_select">\n' +
        '                                                        <label class="required" for="">Цена</label>\n' +
        '                                                        <input required name="prices[0][0][price]" type="text">\n' +
        '                                                    </div>\n' +

        ' <div class="category_delete_handler"><div class="btn-box">\n' +
        '                                                    <button type="button" class=" delete_subcategory_button btn-style-delete"><span class="flaticon-delete-button"></span> Удалить </button>\n' +
        '                                                </div></div>' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                            <div>\n' +
        '                                                <button class="btn btn-style-three ml-auto d-block add_subcategory" type="button" data-add="1" data-main="0" data-current="1">Добавить Подкатегорию</button>\n' +
        '                                            </div>\n' +
        '\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                    <div>\n' +
        '                                        <button type="button" data-add="1" data-current="1" class="theme-btn btn-style-two mt-3" id="add_category">Добавить Категорию</button>\n' +
        '                                    </div>\n' +
        '\n' +
        '                                </div>')
}


function newMainCategoryItem(id){
    return $('<div class="main_category mt-3">\n' +
        '                                        <select name="prices['+ id +'][category]" class="chosen-select main_category_select">\n' +
        '                                        </select>\n' +
        '                                        <div class="default-form flexible_desc" data-id="'+ id +'">\n' +
        '                                            <div class="row pl-2 main_subcategory">\n' +
        '                                                <div class="col-lg-5 col-8 form-group sub_select">\n' +
        '                                                    <label class="required" for="">Подкатегория</label>\n' +
        '                                                    <select required name="prices['+ id +'][0][subcategory]" class="chosen-select subcategory_select">\n' +
        '                                                    </select>\n' +
        '                                                </div>\n' +
        '                                                <div class="col-lg-5 col-8 form-group price_select">\n' +
        '                                                    <label class="required" for="">Цена</label>\n' +
        '                                                    <input required name="prices['+ id +'][0][price]" type="text">\n' +
        '                                                </div>\n' +
        ' <div class="category_delete_handler">\n' +
        '                                                    <div class="btn-box">\n' +
        '                                                        <button type="button" class=" delete_subcategory_button btn-style-delete"><span class="flaticon-delete-button"></span> Удалить </button>\n' +
        '                                                    </div>\n' +
        '                                                </div>' +
        '                                            </div>\n' +
        '                                        </div>\n' +
        '\n' +
        '<div>' +
        '<button class="btn btn-style-three ml-auto d-block add_subcategory" type="button" data-add="1" data-main="'+ id +'" data-current="1">Добавить Подкатегорию</button>\n' +
        '                                        </div>'+
        '                                    </div>')
}


function newSubcategoryItem(mainCategoryID,newSubcategoryID){
    return $('<div class="row pl-2 main_subcategory sv">\n' +
        '                                                <div class="col-lg-5 col-8 form-group sub_select">\n' +
        '                                                    <label class="required" for="">Подкатегория</label>\n' +
        '                                                    <select required name="prices['+mainCategoryID+']['+newSubcategoryID+'][subcategory]" class="chosen-select subcategory_select">\n' +
        '                                                    </select>\n' +
        '                                                </div>\n' +
        '                                                <div class="col-lg-5 col-8 form-group price_select">\n' +
        '                                                    <label class="required" for="">Цена</label>\n' +
        '                                                    <input required name="prices['+mainCategoryID+']['+newSubcategoryID+'][price]" type="text">\n' +
        '                                                </div>\n' +
        ' <div class="category_delete_handler">\n' +
        '                                                    <div class="btn-box">\n' +
        '                                                        <button type="button" class=" delete_subcategory_button btn-style-delete"><span class="flaticon-delete-button"></span> Удалить </button>\n' +
        '                                                    </div>\n' +
        '                                                </div>' +
        '\n' +
        '                                            </div>')

}
function startPricesWidget(mainCategories,subcategories){
    let item = newPricesWidget();
    let main_select = item.find('.main_category_select');
    let subcategory_select = item.find('.subcategory_select');
    mainCategories.forEach(function (category){
        main_select.append('<option>'+ category +'</option>')
    })
    main_select.append('<option data-purpose="add_new" class="add_new_custom_subcategory">Добавить Новое</option>')
    subcategories.forEach(function (category){
        subcategory_select.append('<option>'+ category +'</option>')
    })
    subcategory_select.append('<option class="add_new_custom_subcategory">Добавить Новое</option>')
    $('#prices_widget').append(item)
    return true;
}
function addNewMainCategory(id,mainCategories,subcategories){
    let item = newMainCategoryItem(id);
    let main_select = item.find('.main_category_select');
    let subcategory_select = item.find('.subcategory_select');
    mainCategories.forEach(function (category){
        main_select.append('<option>'+ category +'</option>')
    })
    main_select.append('<option class="add_new_custom_subcategory">Добавить Новое</option>')
    subcategories.forEach(function (category){
        subcategory_select.append('<option>'+ category +'</option>')
    })
    subcategory_select.append('<option class="add_new_custom_subcategory">Добавить Новое</option>')
    $('.main_category_handler')
        .append(item)
    return true;
}
function addNewSubcategory(mainCategoryID,newSubcategoryID,categories){
    let item = newSubcategoryItem(mainCategoryID,newSubcategoryID);
    let select = item.find('select');
    categories.forEach(function (category){
        select.append('<option>'+ category +'</option>')
    })
    select.append('<option class="add_new_custom_subcategory">Добавить Новое</option>')
    $('.flexible_desc[data-id='+ mainCategoryID +']').append(item);
    return true;
}


let main_categories_array = ['Черный','Медь','Алюминий','Нержавейка','Бронза','Латунь','Свинец','Аккумуляторы'];
let subcategories_array = ['3А','5А','12А','17А (чугун)','19А','20А','22А','Стружка','Микс','Оцинкованный']

$(document).on('click','.add_subcategory',function () {
    let btn = $(this);
    let newSubcategoryID = btn.data('add');
    let mainCategoryID = btn.data('main');
    let current_count = btn.data('current');
    addNewSubcategory(mainCategoryID,newSubcategoryID,subcategories_array)
    btn.data('add',newSubcategoryID +1)
    btn.data('current',current_count +1)
    makeChosen();

})
$(document).on('click','#add_category',function () {
    let btn = $(this);
    let newCategoryId = btn.data('add');
    let current_mains_count = btn.data('current')
    addNewMainCategory(newCategoryId,main_categories_array,subcategories_array);
    makeChosen();
    btn.data('add',newCategoryId+1)
    btn.data('current',current_mains_count+1)

})

$(document).on('change','.main_category_select',function () {

    if($(this).find(':selected').data('purpose') === 'add_new'){
        let select = $(this);
        let name = select.attr('name');
        let new_input = $('<input required type="text">');
        new_input.attr('name',name);
        select.after(new_input);
        select.chosen("destroy");
        select.remove();
        new_input.focus();
    }
})

$(document).on('click','.delete_subcategory_button',function () {
    let btn = $(this);
    let add_subcategory_btn = btn.closest('.main_category').find('.add_subcategory');
    let current_subs_count = add_subcategory_btn.data('current');
    let main_adding_button = $('#add_category');
    let current_mains_count = main_adding_button.data('current');
    if (current_subs_count > 1){
        btn.closest('.main_subcategory').animate({
            opacity:0.5
        },200,function (){
            btn.closest('.main_subcategory').remove();
        });
        add_subcategory_btn.data('current',current_subs_count-1)
    }else{
        if(main_adding_button.data('current') > 1){
            btn.closest('.main_category').animate({
                opacity:0.5
            },200,function (){
                btn.closest('.main_category').remove()
            });
            main_adding_button.data('current',current_mains_count-1);
        }else{
            $('#prices_widget').empty().after('<button type="button" class="btn btn-style-two" id="start_prices_widget">Добавить Цены</button>');
        }

    }

})
$(document).on('click','.delete_main_category_button',function () {
    let btn = $(this);
    let category = btn.closest('.main_category');
    category.remove();
})
$(document).on('click','#start_prices_widget',function () {
    let btn = $(this);
    startPricesWidget(main_categories_array,subcategories_array)
    btn.remove()
    makeChosen();
})
$.fn.isInViewport = function() {
    let elementTop = $(this).offset().top;
    let viewportTop = $(window).scrollTop();

    return elementTop > viewportTop +85  && elementTop < viewportTop+200;
};
let CURRENT_ITEM = null;
$(document).on('scroll',function (){
    $('.ls-widget').each(function (){
        if($(this).isInViewport() && CURRENT_ITEM !== $(this).attr('id')){

            let ID = $(this).attr('id');
            $('.ls-widget').removeClass('active');
            $(this).addClass('active');
            $('.listing-content-list li').removeClass('active');
            $('.listing-content-list li[data-target="#'+ ID +'"]').addClass('active');
            CURRENT_ITEM = $(this).attr('id');
        }
    })
})

$('.working_title_button').click(function (){
    if($(this).parent().hasClass('active')){
        $(this).parent().removeClass('active')
    }else{
        $('.time-table-block').removeClass('active')
        $(this).parent().addClass('active')
    }

})
$('.delete_worktime_button').click(function (){
    let parent_elem = $(this).parents('.time-table-block');
    parent_elem.animate({
        opacity:0
    },300,function () {
        parent_elem.remove()
    })

})
$("input.file-upload-input").MultiFile({
    list: ".file-upload-previews"
});


