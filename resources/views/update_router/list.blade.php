@include('header')
<main class="flex-shrink-0">
    <div class="container">
        <table class="table table-hover mt-2">
            <thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Название</th>
                <th scope="col">IP</th>
                <th scope="col">Статус</th>
                <th scope="col">Установленная версия</th>
                <th scope="col">Последняя версия</th>
                <th scope="col">Действие</th>
            </tr>
            </thead>
            <tbody>
            @if (count($routers)>0)
                @foreach($routers as $router)
                    <tr class="align-middle">
                        <td scope="row">{{$router->id}}</td>
                        <td>{{$router->name}}</td>
                        <td>{{$router->ip_address}}:{{$router->port}}</td>
                        <td>{{$router->status}}</td>
                        <td>{{$router->installed_version}} - {{$router->channel}}</td>
                        <td>
                            @if ($router->latest_version!='')
                                Версия: {{$router->latest_version}} - {{$router->channel}}
                            @endif
                        </td>
                        <td class="d-flex">
                            @if ($router->latest_version!=$router->installed_version && $router->latest_version!='')
                            <a type="button" class="btn btn-outline-success" style="margin-right: 5px"
                               href="/update_router/{{$router->id}}/update">Обновить ПО</a>
                            @else
                                <a type="button" class="btn btn-outline-success disabled" style="margin-right: 5px"
                                   href="#">Обновить ПО</a>
                            @endif
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
    </div>
</main>
@include('footer')
