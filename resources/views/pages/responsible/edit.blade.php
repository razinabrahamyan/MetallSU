@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
                <div class="breadcrumb-title pe-3">Редактировать</div>
                <div class="breadcrumb-title px-3">Списки</div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 ">
            </div>
            <div class="card list_card">
                <div class="card-body">
                    <div class="card-title">
                        <div>
                            <h5>{{$base->name}}</h5>
                        </div>
                        <form action="{{route('lists.update')}}" method="post">
                            @csrf
                            <input type="hidden" value="{{$base->id}}" name="base">
                            <div class="d-flex">
                                <div>
                                    @foreach($base->bases as $item)
                                        @php($loop_index = $loop->index)

                                        <div class="main_edit_block">
                                            <div class="d-flex align-items-center position_edit_card" data-iteration="{{$loop->index}}"  data-parent="{{$item->id}}">
                                                <span>{{$loop->index+1}}</span>
                                                <div class="form-group p-1 inputs">
                                                    <input autocomplete="off" disabled required value="{{$item->name}}" type="text" name="items[{{$loop->index}}][title]" class="form-control w-auto main_value" >
                                                    <input  value="{{$item->id}}" type="hidden" name="items[{{$loop->index}}][id]" >
                                                </div>
                                                <div>
                                                    <button class="btn btn-light should_dis edit_button" type="button"><i class="bx bx-edit"></i></button>
                                                    <button class="btn btn-light should_dis text-danger delete_button" type="button"><i class="bx bx-trash"></i></button>
                                                    <button class="btn btn-light restore_button" type="button"><i class="bx bx-undo"></i></button>
                                                </div>
                                            </div>
                                            <div class="children">

                                            </div>
                                        </div>

                                    @endforeach
                                </div>
                                <div class="ms-3">
                                    <div class="new_items_added">

                                    </div>
                                    <div class="p-1"><button type="button" class="btn btn-light add_main_block">Добавить поле <i class="bx bx-plus"></i></button></div>
                                </div>
                            </div>
                            <div>
                                <div >
                                    <button type="submit" class="btn btn-light pos_save text-white mt-4">Сохранить<i class="bx bx-save"></i></button>
                                    <a href="{{route('lists.index')}}" class="btn text-decoration-underline text-white mt-4">Отмена</a>
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
                return ('<div class="d-flex align-items-center position_edit_card new_added">' +
                    '                <div class="form-group p-1 ps-4">' +
                    '                   <input autocomplete="off" required value="" type="text" name="new_child['+ index +'][title]" class="form-control w-auto main_value" >' +
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
                return ('<div class="d-flex align-items-center position_edit_card new_added">' +
                    '                <div class="form-group p-1 ps-4">' +
                    '                   <input autocomplete="off" required value="" type="text" name="new_items['+ index +'][children][][title]" class="form-control w-auto main_value" >' +
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
                return $('<div class="main_edit_block new_added">' +
                    '                <div class="d-flex align-items-center position_edit_card" data-index="'+ MAIN_INDEX +'">' +
                    '                <div class="form-group p-1">' +
                    '                <input autocomplete="off" required value="" type="text" name="new_items['+ MAIN_INDEX +'][title]" class="form-control w-auto main_value" >' +
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
            let CHILD_INDEX = 0;
            let MAIN_INDEX = 0;
            $(document).on('click','.position_edit_card .edit_button',function (){
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
            $('.add_main_block').click(function (){
                let  new_elem = mainElem();
                $('.new_items_added').append(new_elem);
                new_elem.find('.main_value').focus();
            })

        </script>
    @endpush

@endsection
