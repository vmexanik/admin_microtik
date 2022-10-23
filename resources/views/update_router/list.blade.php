@include('header')
<main class="flex-shrink-0">
    <div class="container mt-2 text-end">
        <button type="button" class="btn btn-outline-success disabled" disabled style="margin-right: 5px" id="update_selected"
                onclick="updateRoutes()">Обновить ПО</button>
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
                <th scope="col">Установленная версия</th>
                <th scope="col">Последняя версия</th>
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
                        <td id="status-{{$router->id}}"></td>
                        <td id="installed_version-{{$router->id}}"></td>
                        <td id="latest_version-{{$router->id}}"></td>
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
