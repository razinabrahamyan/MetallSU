<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Профиль</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('index')}}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Новый пользователь</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="container">
            <div class="main-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{route('user.create')}}" method="post">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Имя</h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="firstName"
                                                   placeholder="Введите имя" required/>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Фамилия</h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="lastName"
                                                   placeholder="Введите фамилию"/>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Email</h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="email"
                                                   placeholder="Введите почту" required/>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Телефон</h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="phone"
                                                   placeholder="Введите телефон"/>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Уровень доступа</h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="roleId" aria-label="example" required>
                                                @foreach($roles as $role)
                                                    <option value="{{$role->id}}">{{$role->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Доступ к категориями</h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="categories[]" aria-label="example" multiple required>
                                                @foreach($userCategories as $category)
                                                    <option value="{{$category->id}}">{{$category->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9">
                                            <button type="submit" class="btn btn-light px-4">Создать</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
