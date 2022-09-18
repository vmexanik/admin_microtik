<h1 class="mt-4 mb-5">Привет, {{ Auth::user()->name }}. Сегодня {{ date('d-m-Y') }}</h1>
{{--<form class="row g-3 border rounded m-0" action="{{url('/')}}" method="post">--}}
{{--    @csrf--}}

{{--        @if ($errors->has('number_of_fop'))--}}
{{--            <div class="invalid-feedback d-block">--}}
{{--                {{ $errors->first('number_of_fop') }}--}}
{{--            </div>--}}
{{--        @endif--}}
{{--</form>--}}
@if (isset($error))
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
        <th scope="col">Статус</th>
        <th scope="col">Действие</th>
    </tr>
    </thead>
    <tbody>
    <tr class="align-middle">
        <td scope="row">1</td>
        <td>САМЫЙ ГЛАВНЫЙ РОУТЕР</td>
        <td>000.000.000.000</td>
        <td>СПИТ</td>
        <td>
            <button type="button" class="btn btn-success">Управление</button>
        </td>
    </tr>
    </tbody>
</table>

