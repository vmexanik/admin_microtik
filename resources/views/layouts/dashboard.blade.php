<h1 class="mt-4 mb-5">Привет, {{ Auth::user()->name }}. Сегодня {{ date('d-m-Y') }}</h1>
{{--<form class="row g-3 border rounded m-0" action="{{url('/')}}" method="post">--}}
{{--    @csrf--}}

{{--        @if ($errors->has('number_of_fop'))--}}
{{--            <div class="invalid-feedback d-block">--}}
{{--                {{ $errors->first('number_of_fop') }}--}}
{{--            </div>--}}
{{--        @endif--}}
{{--</form>--}}
@if (!empty($error))
    <div class="alert alert-danger mb-2 mt-2">
        {{ $error }}
    </div>
@endif
<table class="table table-hover">
    <thead class="thead-light">
    <tr>
        <th scope="col">#</th>
        <th scope="col">Название</th>
        <th scope="col">IP</th>
        <th scope="col">Время работы</th>
        <th scope="col">Версия ОС</th>
        <th scope="col">Статус</th>
    </tr>
    </thead>
    <tbody>
    @foreach($routers as $router)
        <tr class="align-middle">
            <td scope="row">{{$router->id}}</td>
            <td>{{$router->name}}</td>
            <td>{{$router->ip_address}}</td>
            @if(!isset($router->error))
                <td>{{$router->uptime}}</td>
                <td>{{$router->os_version}}</td>
            @else
                <td colspan="2"></td>
            @endif
            <td>
                @if(isset($router->error))
                    <span class="badge bg-danger">{{$router->status}}</span>
                    <span class="invalid-feedback d-block">
                        {{$router->error}}
                    </span>
                @else
                    <span class="badge bg-success">{{$router->status}}</span>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

