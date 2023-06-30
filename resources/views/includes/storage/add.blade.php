<div class="card storage_adding_card">
    <div class="card-body">
        <h5 class="my-3">Добавить В Склад</h5>
        <form action="{{route('storage.add')}}" method="post">
            @csrf
            <div class="form-group">
                <input type="hidden" value="{{$storage->id}}" name="storage_id">
            </div>
            <div class="form-group">
                @if($storage->items->count())
                    <label class="form-label" for="add_item_type">Предмет</label>
                    <select required type="text" class="add_item_type form-select" name="product">
                        @foreach($storage->items as $item)
                            <option value="{{$item->id}}"> {{$item->name.'  ( '.$item->description.' ) '}}</option>
                        @endforeach
                        <option value="add_new" class="add_new">Добавить новое</option>
                    </select>
                @else
                    <label class="form-label" for="add_item_type">Предмет</label>
                    <input type="text"  class="form-control" name="product_new" placeholder="введите название">
                    <label class="form-label" for="desc">Описание</label>
                    <input type="text"  class="form-control" name="description" placeholder="введите название">
                    <label class="form-label mt-3" for="desc"> Цена <i class="bx bx-ruble m-0"></i>(за 1шт)</label>
                    <input type="number"  class="form-control" name="price" placeholder="введите цену">
                @endif

            </div>
            <div class="form-group mt-3">
                <label class="form-label" for="count">Цена <i class="bx bx-ruble m-0"></i>(за 1шт)</label>
                <input type="number"  class="form-control" name="price" placeholder="введите цену">
            </div>
            <div class="form-group mt-3">
                <label for="" class="form-label">Дата</label>
                <input autocomplete="off" required class="form-control datepicker" type="datetime" placeholder="выберите дату" name="date">
            </div>

            <div class="form-group mt-3">
                <label class="form-label" for="count">Количество</label>
                <input required value="1" type="number"  class="form-control" name="count" placeholder="введите количество">
            </div>
            <div class="d-grid mt-3"> <button type="submit" class="btn btn-light">+ Добавить</button>
            </div>
        </form>

    </div>
</div>



