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
        <th scope="col">Router</th>
        <th scope="col">IP</th>
        <th scope="col">Uptime</th>
        <th scope="col">RouterOS Version</th>
        <th scope="col">Status</th>
        <th scope="col">Last backup</th>
    </tr>
    </thead>
    <tbody>
    @foreach($routers as $router)
        <tr class="align-middle">
            <td scope="row" router-id="{{$router->id}}">{{$router->id}}</td>
            <td>{{$router->name}}</td>
            <td>{{$router->ip_address}}</td>
            <td id="uptime-{{$router->id}}"></td>
            <td id="os_version-{{$router->id}}"></td>
            <td id="router_status-{{$router->id}}"></td>
            <td>{{$router->last_backup}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

