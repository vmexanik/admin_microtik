@include('header')
<main class="flex-shrink-0">
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
