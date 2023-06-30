@extends('layouts.core')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <a href="{{route('storage.index')}}"><div class="breadcrumb-title should_opac pe-3">Склады</div></a>
                <div class="add_table_handler">
                    <button type="button" class="btn btn-light px-4 radius-10 ms-3" data-bs-toggle="modal"
                            data-bs-target="#addStorageModal">Добавить Склад<i class="lni lni-plus"></i></button>
                </div>
            </div>


            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
                @foreach($storages as $storage)
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5>{{$storage->title}} </h5>
                                    <a class="card-title current-table-redirect-title" href="{{route('storage.show',$storage->id)}}">
                                        <div class="table_edit_handler">
                                        <span class="small_edit_icon">
                                            <i class="fadeIn animated bx bx-edit"></i>
                                        </span>
                                            <div>ред</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="mt-3"></div>
                                @foreach($storage->recentItemsLimited as $item)
                                    <div class="d-flex align-items-center mt-1">
                                        <div class="fm-file-box bg-transparent"><i class='bx bx-menu-alt-right'></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2 overflow-hidden">
                                            <h6 class="mb-0">{{$item->name}}</h6>
                                            <p class="mb-0 text-secondary">{{$item->description}}</p>
                                        </div>
                                        <h6 class="mb-0">{{$item->count}}</h6>
                                    </div>
                                @endforeach


                            </div>
                        </div>
                    </div>
                @endforeach


            </div>

        </div>

    </div>
    <script>
        $(document).ready(function (){
            $('#add_item_type').change(function (){
                if($(this).val() === 'add_new'){
                    let input = $('<input type="text" id="name" class="form-control" name="name_new" placeholder="введите название">');
                    $(this).after(input);
                    $(this).remove()
                    input.focus()
                }
                console.log($(this).val())
            })
        })
    </script>
    @include('includes.modals.new_storage_modal')
    <div class="overlay toggle-icon"></div>
    <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

@endsection
