<div class="modal fade" id="sendMailModal" tabindex="-1" aria-labelledby="sendMailModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Отправить таблицу</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" id="tab_id">
            <div class="modal-body">
                <label class="form-label">Выберите Тип (.pdf/.xmls)</label>
                <select class="form-select mb-3" aria-label="Default select example" id="mail_type">
                    <option selected value="pdf">pdf</option>
                    <option value="xlsx">xlsx</option>
                </select>

                <label class="form-label">Введите Эл. Адреса</label>
                <select class="form-control" multiple="multiple" data-role="tagsinput" id="emails_select">
                    @if(auth()->user()->lastMailSent)
                        <option value="{{auth()->user()->lastMailSent->email}}">{{auth()->user()->lastMailSent->email}}</option>
                    @endif

                </select>

                <div class="form-group mt-2">
                    <label for="title" class="form-label">Прикрепить сообщение</label>
                    <textarea class="form-control mb-3" type="text" id="additional_message"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button id="send_email_button" type="button" class="btn btn-light px-4">Отправить</button>
            </div>
        </div>
    </div>
</div>
