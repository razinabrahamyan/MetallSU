@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
                <div class="breadcrumb-title pe-3">Редактировать</div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 ">
            </div>
            <div class="card ">
                <div class="card-body">
                    <div class="card-title">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-wrap">
                                <h5>Группы Экспорта</h5>
                                <button class="btn btn-light show_all_children ms-1" type="button">
                                    <i class="bx bx-exclude"></i> <span class="show_all_span">Показать все предметы</span> <span class="hide_all_span">Скрыть все предметы</span>
                                </button>
                            </div>



                        </div>
                        <form action="{{route('export.groups.update')}}" method="post">
                            @csrf

                            <div class="mt-2 export_groups_form_inner">
                                <div class="new_items_added">

                                </div>
                                <div class="d-flex">
                                    <div>
                                        @forelse($positions as $position)
                                            @php($loop_index = $loop->index)

                                            <div class="main_edit_block has_children_block hidden">
                                                <div style="" class="d-flex align-items-center position_edit_card" data-iteration="{{$loop->index}}"  data-parent="{{$position->id}}">

                                                    <div class="form-group p-1 inputs">
                                                        <input autocomplete="off" disabled required value="{{$position->title}}" type="text" name="items[{{$loop->index}}][title]" class="form-control main_value" >
                                                        <input  value="{{$position->id}}" type="hidden" name="items[{{$loop->index}}][id]" >
                                                    </div>
                                                    <div class="d-flex flex-nowrap">
                                                        <button class="btn btn-light should_dis edit_button" type="button"><i class="bx bx-edit"></i></button>

                                                        <button class="btn btn-light should_dis text-danger delete_button" type="button"><i class="bx bx-trash"></i></button>
                                                        <button class="btn btn-light restore_button" type="button"><i class="bx bx-undo"></i></button>
                                                        <button class="btn btn-light should_dis open_children_button ms-1" type="button">
                                                            <i class="bx bx-exclude open_children_icon"></i>
                                                            <i class="bx bx-unite close_children_icon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="children p-2">

                                                    @forelse($position->items as $item)
                                                        <p class="m-0 export_item_list">
                                                            <span>{{($loop->index +1).'. '}}</span>
                                                            @if($item->parentItem) <span class="export_category_span">{{$item->parentItem->title.' / '}}</span>@endif
                                                            <span>{{$item->title}}</span>
                                                            @if($item->parentItem)
                                                                <span class="export_category_span">({{$item->parentItem->position->title}})</span>
                                                            @else
                                                                <span class="export_category_span">({{$item->position->title}})</span>
                                                            @endif



                                                        </p>
                                                    @empty
                                                    @endforelse

                                                </div>
                                            </div>


                                        @empty
                                            <div class="p-3">
                                                <h6>Список Пуст</h6>

                                            </div>

                                        @endforelse
                                    </div>
                                    <div class="ms-3">

                                        <div class="p-1 new_item_button_handler"><button type="button" class="btn btn-light add_main_block">Добавить поле <i class="bx bx-plus"></i></button></div>
                                    </div>
                                </div>
                            </div>


                            <div class="edit_form_submit">
                                <div >
                                    <button type="submit" class="btn btn-light pos_save text-white mt-4">Сохранить<i class="bx bx-save"></i></button>
                                    <a href="{{route('costs.edit.items')}}" class="btn text-decoration-underline text-white mt-4">Отмена</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/caret/1.3.7/jquery.caret.min.js" integrity="sha512-DR6H+EMq4MRv9T/QJGF4zuiGrnzTM2gRVeLb5DOll25f3Nfx3dQp/NlneENuIwRHngZ3eN6w9jqqybT3Lwq+4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            function childElem(parent_id){
                let index = CHILD_INDEX++;
                return ('<div class="d-flex align-items-center position_edit_card editable new_added">' +
                    '                <div class="form-group p-1 ps-4">' +
                    '                   <input autocomplete="off" required value="" type="text" name="new_child['+ index +'][title]" class="form-control  main_value" >' +
                    '                   <input value="'+ parent_id +'" type="hidden" name="new_child['+ index +'][parent_id]" class="form-control w-auto main_value" >' +
                    '                </div>' +
                    '            <div>' +
                    '                <button class="btn btn-light should_dis edit_button" type="button"><i class="bx bx-edit"></i></button>' +
                    '                <button class="btn btn-light text-danger should_dis delete_child_button" type="button"><i class="bx bx-trash"></i></button>' +
                    '                <button class="btn btn-light text-danger restore_child_button" type="button"><i class="bx bx-trash"></i></button>' +
                    '            </div>' +
                    '        </div>');
            }
            function newChildElem(index){
                return ('<div class="d-flex align-items-center position_edit_card editable new_added">' +
                    '                <div class="form-group p-1 ps-4">' +
                    '                   <input autocomplete="off" required value="" type="text" name="new_items['+ index +'][children][][title]" class="form-control  main_value" >' +
                    '                </div>' +
                    '            <div>' +
                    '                <button class="btn btn-light should_dis edit_button" type="button"><i class="bx bx-edit"></i></button>' +
                    '                <button class="btn btn-light should_dis text-danger delete_child_button" type="button"><i class="bx bx-trash"></i></button>' +
                    '                <button class="btn btn-light text-danger restore_child_button" type="button"><i class="bx bx-trash"></i></button>' +
                    '            </div>' +
                    '        </div>');
            }
            function mainElem(){
                let index = MAIN_INDEX++;
                return $('<div class="main_edit_block new_added ">' +
                    '                <div class="d-flex align-items-center editable position_edit_card" data-index="'+ MAIN_INDEX +'">' +
                    '                <div class="form-group p-1">' +
                    '                <input autocomplete="off" required value="" type="text" name="new_items['+ MAIN_INDEX +'][title]" class="form-control  main_value" >' +
                    '                </div>' +
                    '            <div>' +
                    '                <button class="btn btn-light should_dis edit_button" type="button"><i class="bx bx-edit"></i></button>' +
                    '                <button class="btn btn-light should_dis text-danger delete_button" type="button"><i class="bx bx-trash "></i></button>' +
                    '                <button class="btn btn-light text-danger restore_button" type="button"><i class="bx bx-trash"></i></button>' +
                    '            </div>' +
                    '        </div>' +
                    '        <div class="children">' +
                    '        </div>' +
                    '</div>');
            }
            $('#delete_position').click(function (){
                if(confirm("Вы действительно хотите удалить позицию ?")){
                    $('#position_form').submit()
                }

            })
            $('.open_children_button').click(function (){
                $(this).closest('.main_edit_block').toggleClass('hidden')
            })
            let CHILD_INDEX = 0;
            let MAIN_INDEX = 0;
            $(document).on('click','.position_edit_card .edit_button',function (){
                $(this).closest('.position_edit_card').addClass('editable')
                let input = $(this).parents('.position_edit_card').find('.main_value');
                input.attr('disabled',false).focus().caret(input.val().length);

            })
            function addChild(elem,has_focus = true){
                let parent_elem = elem.parents('.position_edit_card');
                let parent_id = parent_elem.data('parent');
                let child_elem;
                if(parent_id){
                    child_elem = $(childElem(parent_id));
                }else{
                    child_elem = $(newChildElem(parent_elem.data('index')))
                }
                elem.parents('.main_edit_block').find('.children').append(child_elem);
                parent_elem.addClass('has_children')
                if(has_focus){
                    child_elem.find('.main_value').focus();
                }

            }
            $(document).on('click','.position_edit_card .add_button',function (){
                if($(this).parents('.position_edit_card').hasClass('has_children')){
                    addChild($(this))
                }else{
                    addChild($(this))
                    addChild($(this),false)
                }
            })
            $(document).on('click','.position_edit_card .delete_button',function (){
                if($(this).parents('.main_edit_block').hasClass('new_added')){
                    $(this).parents('.main_edit_block').remove()
                }else{
                    let parent = $(this).parents('.position_edit_card');
                    let iter = parent.data('iteration')
                    parent.find('.inputs').append('<input  value="1" class="deleted_sign" type="hidden" name="items['+ iter +'][deleted]" >');
                    parent.addClass('deleted');
                    parent.find('.main_value').attr('disabled',true)
                    let child_block = $(this).parents('.main_edit_block').find('.children');
                    $(this).parents('.main_edit_block').addClass('deleted');
                    $(this).parents('.main_edit_block').find('button.should_dis').attr('disabled',true)
                    child_block.find('input').attr('disabled',true);
                    child_block.find('.position_edit_card').addClass('deleted');
                }
            })
            $(document).on('click','.position_edit_card .delete_child_button',function (){
                if($(this).parents('.position_edit_card').hasClass('new_added')){
                    let div_to_check = $(this).parents('.children');
                    $(this).parents('.position_edit_card').remove()
                    if(!div_to_check.find('.position_edit_card').length){
                        div_to_check.parents('.main_edit_block').find('.position_edit_card ').removeClass('has_children')
                    }
                }else{
                    let parent = $(this).parents('.position_edit_card');
                    let iter = parent.data('iteration')
                    parent.find('.inputs').append('<input  value="1" type="hidden" class="deleted_sign" name="items['+ iter +'][deleted]" >');
                    parent.addClass('deleted');
                    parent.find('button.should_dis').attr('disabled',true)

                }
            })

            $(document).on('click','.position_edit_card .restore_child_button',function (){
                if($(this).parents('.position_edit_card').hasClass('new_added')){

                }else{
                    let parent = $(this).parents('.position_edit_card');
                    parent.find('.inputs').find('.deleted_sign').remove();
                    parent.removeClass('deleted');
                    parent.find('button.should_dis').attr('disabled',false)
                    parent.find('input').attr('disabled',false);

                }
            })
            $(document).on('click','.position_edit_card .restore_button',function (){
                if($(this).parents('.position_edit_card').hasClass('new_added')){

                }else{
                    let parent = $(this).parents('.position_edit_card');
                    parent.find('.inputs').find('.deleted_sign').remove();
                    parent.removeClass('deleted');
                    let child_block = $(this).parents('.main_edit_block').find('.children');
                    $(this).parents('.main_edit_block').removeClass('deleted');
                    $(this).parents('.main_edit_block').find('button.should_dis').attr('disabled',false)
                    child_block.find('input').attr('disabled',false);
                    child_block.find('.position_edit_card').removeClass('deleted');

                }
            })
            $('.show_all_children').click(function (){
                if($(this).hasClass('shown')){
                    $('.main_edit_block').addClass('hidden')
                }else{
                    $('.main_edit_block').removeClass('hidden')
                }
                $(this).toggleClass('shown')

            })
            $('.add_main_block').click(function (){
                let  new_elem = mainElem();
                $('.new_items_added').append(new_elem);
                new_elem.find('.main_value').focus();
            })

        </script>
    @endpush

@endsection
