<div class="modal fade" id="addTableModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить Таблицу</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('table.test.create')}}" method="post">
                @csrf
                <div class="modal-body">
                    <label for="exampleDataList" class="form-label">Площадка</label>
                    <select class="form-select" id="ploshad" name="category">

                        @foreach($create_categories as $category)
                            <option value="{{$category->id}}">{{$category->title}}</option>
                        @endforeach
                    </select>
                    <label class="form-label mt-2" for="template_type">Категория</label>
                    <select class="form-select" id="template_type" name="template">
                        @foreach($templates as $template)
                            <option value="{{$template->id}}">{{$template->title}}</option>
                        @endforeach
                    </select>
                    <div class="form-group mt-2">
                        <label for="title" class="form-label">Название</label>
                        <input autocomplete="off" required class="form-control mb-3" type="text" placeholder="Default input" name="title" id="title" aria-label="default input example">
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="zero_tab" id="zeroFiled">
                        <label class="form-check-label" for="zeroFiled">Нулевая Таблица</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-light px-4">Создать</button>
                </div>
            </form>
        </div>
    </div>
</div>
