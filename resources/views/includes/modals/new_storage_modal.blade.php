<div class="modal fade" id="addStorageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить Склад</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('storageCreate')}}" method="post">
                @csrf
                <div class="modal-body">

                    <div class="form-group mt-2">
                        <label  class="form-label">Название</label>
                        <input class="form-control" required name="title" >
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
