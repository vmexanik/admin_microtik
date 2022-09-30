@include('header')
<main class="flex-shrink-0">
    <div class="container">
        <table class="table table-hover mt-2">
            <thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Название</th>
                <th scope="col">IP</th>
                <th scope="col">Логин</th>
                <th scope="col">Пароль</th>
                <th scope="col">Порт</th>
                <th scope="col">Действие</th>
            </tr>
            </thead>
            <tbody>
            @if (count($routers)>0)
                @foreach($routers as $router)
                    <tr class="align-middle">
                        <td scope="row">{{$router->id}}</td>
                        <td>{{$router->name}}</td>
                        <td>{{$router->ip_address}}</td>
                        <td>{{$router->login}}</td>
                        <td>{{$router->password}}</td>
                        <td>{{$router->port}}</td>
                        <td class="d-flex">
                            <a type="button" class="btn btn-outline-success" style="margin-right: 5px" href="{{ route('router.edit',$router->id) }}">Редактировать</a>
                            <form action="{{ route('router.destroy',$router->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{$router->id}}">
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fa fa-trash"></i> Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="align-middle">
                    <td scope="row" colspan="7">Нет ни одного роутера</td>
                </tr>
            @endif
            </tbody>
        </table>
        <form class="row gy-2 gx-3 align-items-end border rounded m-0" method="get" action="{{ route('router.create') }}">
            @csrf
            <div class="mb-3">
                @if ($errors->any())
                    <div class="invalid-feedback d-block">
                        @foreach ($errors->all() as $error)
                            {{$error}}
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-auto">
                <label for="name" class="form-label">Имя</label>
                <input type="text" name="name" class="form-control" id="name" value="{{old('name')}}">
            </div>
            <div class="col-auto">
                <label for="name" class="form-label">IP</label>
                <input type="text" name="ip_address" class="form-control" id="ip_address" value="{{old('ip_address')}}">
            </div>
            <div class="col-auto">
                <label for="name" class="form-label">Порт</label>
                <input type="text" name="port" class="form-control" id="port" value="@if (!old('port')){{8728}}@else{{old('port')}}@endif">
            </div>
            <div class="col-auto">
                <label for="name" class="form-label">Логин</label>
                <input type="text" name="login" class="form-control" id="login" value="{{old('login')}}">
            </div>
            <div class="col-auto">
                <label for="name" class="form-label">Пароль</label>
                <input type="text" name="password" class="form-control" id="password" value="{{old('password')}}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">Добавить</button>
            </div>
        </form>
    </div>
</main>
@include('footer')
