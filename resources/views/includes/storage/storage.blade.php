<div class="card storage_items_card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h5 class="mb-0 font-weight-bold">Склад</h5>
            <div>
                <input type="text" class="store_items_search_field form-control" placeholder="поиск...">
            </div>
        </div>


        <div class="mt-3"></div>
        <div class="active static_store_items">
            @forelse($storage->recentItemsLimited as $item)
                <div class="d-flex align-items-center mt-1">
                    <div class="fm-file-box bg-transparent"><i class='bx bx-menu-alt-right'></i>
                    </div>
                    <div class="flex-grow-1 ms-2 overflow-hidden  p-1">
                        <h6 class="mb-0">{{$item->name}}<span class="storage_item_price"> ({{$item->price}}<i class="bx bx-ruble m-0"></i>) </span></h6>
                        <p class="mb-0 storage_item_card_comment">{{$item->description}}</p>
                    </div>
                    <h6 class="mb-0 storage_item_count_handler" data-handlerded="{{$item->id}}">{{$item->count}}</h6>
                    <div class="dropdown" >
                        <div class="cursor-pointer font-24 dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i>
                        </div>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{route('edit.storage.item',$item->id)}}"><span><i class="bx bx-edit pe-1"></i></span>Ред</a>
                        </div>
                    </div>
                </div>

            @empty
                <p>Склад пуст</p>
            @endforelse
        </div>
        <div class="dynamic_store_items">

        </div>
    </div>
</div>

