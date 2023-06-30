<div class="modal fade" id="positionModal" tabindex="-1" aria-labelledby="addPeriodModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить Позицию</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('position.add')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group mt-2">
                        <label for="name" class="form-label">Название</label>
                        <input autocomplete="off" required class="form-control mb-3" type="text" placeholder="Введите Название" name="name" id="name" aria-label="default input example">
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
