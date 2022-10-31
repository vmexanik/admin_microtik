@include('header')
<main class="flex-shrink-0">
    <div class="container mt-2">
        <div class="row row-cols-3 row-cols-lg-3">
            <div class="col-auto">
                <label for="name" class="form-label">Логин</label>
                <input type="text" name="login" class="form-control" id="login" value="">
            </div>
            <div class="col-auto">
                <label for="name" class="form-label">Пароль</label>
                <input type="text" name="password" class="form-control" id="password" value="">
            </div>
            <div class="col-auto align-items-end d-flex">
            <button type="button" class="btn btn-outline-success" style="margin-right: 5px" id="update_password_selected"
                    onclick="updateUserPasswords()">Изменить пароли</button>
            </div>
        </div>
    </div>
    <div class="container">
        <table class="table table-hover mt-2">
            <thead class="thead-light">
            <tr>
                <th scope="col">
                    <input class="form-check-input" type="checkbox" name="check_all" value="all">
                </th>
                <th scope="col">#</th>
                <th scope="col">Название</th>
                <th scope="col">IP</th>
                <th scope="col">Статус</th>
            </tr>
            </thead>
            <tbody>
            @if (count($routers)>0)
                @foreach($routers as $key=>$router)
                    <tr class="align-middle">
                        <td scope="row">
                            <input class="form-check-input" type="checkbox" id="row_check_{{$router->id}}"
                                   name="row_check[{{$key+1}}]" value="{{$router->id}}" >
                        </td>
                        <td scope="row" router-id="{{$router->id}}">{{$router->id}}</td>
                        <td>{{$router->name}}</td>
                        <td>{{$router->ip_address}}:{{$router->port}}</td>
                        <td class="status"></td>
                    </tr>
                @endforeach
            @else
                <tr class="align-middle">
                    <td scope="row" colspan="7">Нет ни одного роутера</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</main>
@include('footer')
