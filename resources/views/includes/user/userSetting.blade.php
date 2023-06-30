<div class="card col-12 col-lg-5 profile_card">
    <div class="card-body">
        <form action="{{route('user.update',['id',$user->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$user->id}}">
            <div class="row mb-3">
                <div class="col-sm-3">
                    <h6 class="mb-0">Фото</h6>
                </div>
                <div class="col-sm-9 ">
                    <div class="d-flex align-items-center">
                        <input type="file" style="display: none" name="image" id="image">
                        <div>
                            <img id="profile_image" src="{{asset('storage/user/images/avatar/'.$user->avatar)}}" alt="">
                        </div>
                        <div class="ps-2">
                            <button type="button" id="image_upload_button" class="btn btn-light"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload text-white"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></button>

                        </div>
                    </div>

                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <h6 class="mb-0">Имя</h6>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="firstName" placeholder="Введите имя"
                           value="{{$user->first_name}}" required/>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <h6 class="mb-0">Фамилия</h6>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="lastName"
                           placeholder="Введите фамилию" value="{{$user->last_name}}"/>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <h6 class="mb-0">Email</h6>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="email" placeholder="Введите почту"
                           value="{{$user->email}}" required/>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <h6 class="mb-0">Телефон</h6>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="phone" placeholder="Введите телефон"
                           value="{{$user->phone}}"/>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <h6 class="mb-0">Доступ</h6>
                </div>
                <div class="col-sm-9">
                    <select class="form-select" name="roleId" aria-label="example" required>
                        @foreach($roles as $role)
                            <option value="{{$role->id}}"
                                    @if($user->role_id == $role->id) selected @endif>{{$role->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <h6 class="mb-0">Статус</h6>
                </div>
                <div class="col-sm-9">
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="status" type="checkbox"
                               id="flexSwitchCheckChecked" @if($user->status == 1) checked @endif/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <button type="submit" class="btn btn-light px-4">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
</div>

