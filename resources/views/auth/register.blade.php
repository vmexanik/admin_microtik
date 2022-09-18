@include('header')
<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-4 mb-5">Регистрация пользователей</h1>
        <div class="row justify-content-md-center">
            @if (session('errors'))
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br/>
                    @endforeach
                </div>
            @endif
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="col-7">
                <form class="row gy-2 gx-3 align-items-end border rounded m-0" method="post"
                      action="{{ route('register') }}">
                    @csrf
                    <div class="row m-0 mb-2">
                        <label for="name" class="form-label">Имя</label>
                        <input name="name" type="text" class="form-control" id="name" placeholder=""
                               required autofocus value="{{old('name')}}">
                    </div>
                    <div class="row m-0 mb-2">
                        <label for="password" class="form-label">Пароль</label>
                        <input name="password" type="text" class="form-control" id="password" placeholder=""
                               required autocomplete="new-password">
                    </div>
                    <div class="row m-0 mb-2">
                        <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                        <input name="password_confirmation" type="text" class="form-control" id="password_confirmation" placeholder=""
                               required >
                    </div>
                    <div class="row m-0 mb-2">
                        <button type="submit" class="btn btn-primary">Зарегистрировать</button>
                    </div>
                </form>
                <table class="table table-hover mt-3 align-middle">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Имя</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    @foreach ($users as $user)
                        <tr>
                            <th scope="row">
                                <p class="fs-5 m-0">
                                    {{$user->id}}
                                </p>
                            </th>
                            <td>
                                <p class="fs-5 m-0">
                                    {{$user->name}}
                                </p>
                            </td>
                            <td>
                                <div class="d-flex">
                                    @if ($user->user_type!='admin')
                                        <form action="{{route('user.delete')}}" method="POST" class="me-1">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{$user->id}}">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-trash"></i> Удалить
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{route('password.reset')}}" method="get" class="me-1">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$user->id}}">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Изменить пароль
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="row">
            </div>
        </div>
</main>
@include('footer')
