@include('header')
<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-4 mb-5">Статистика</h1>
        <div class="row justify-content-md-center">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="col-7">
                <form class="row gy-2 gx-3 align-items-end border rounded m-0" method="post"
                      action="{{ url('statistic') }}">
                    @csrf
                    <div class="col-auto">
                        <label for="date" class="form-label">Выберите дату</label>
                        <input name="date_search" type="text" class="form-control flatpickr" id="date" placeholder=""
                               readonly value="{{old('date_search')}}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Поиск</button>
                    </div>
                    <div class="mb-3">
                    </div>
                </form>
            </div>
            <div class="row">
                @if (!empty($parcedData))
                    @foreach( $parcedData as $item)
                        <div class="row g-3 border rounded mt-2">
                            <div class="col-md-3">
                                <label for="daily_total" class="form-label">Сумма за день</label>
                                <input readonly disabled type="text" class="form-control" id="daily_total"
                                       value="{{$item['daily_total']}}">
                            </div>
                            <div class="col-md-2">
                                <label for="course_usd" class="form-label">Курс USD</label>
                                <input readonly disabled type="text" class="form-control" id="course_usd"
                                       value="{{$item['course_usd']}}">
                            </div>
                            <div class="col-md-3">
                                <label for="count_of_checks" class="form-label">Количество чеков</label>
                                <input readonly disabled type="number" class="form-control" id="count_of_checks"
                                       value="{{$item['count_checks']}}">
                            </div>
                            <div class="col-md-4">
                                <label for="number_of_fop" class="form-label">ФОП</label>
                                <input readonly disabled type="text" class="form-control" id="number_of_fop"
                                       value="{{$item['name_fop']}}">
                            </div>
                            <div class="col-md-3">
                                <label for="daily_total" class="form-label">Пользователь</label>
                                <input readonly disabled type="text" class="form-control" id="daily_total"
                                       value="{{$item['user_name']}}">
                            </div>
                            <div class="col-md-3">
                                <label for="daily_total" class="form-label">Дата</label>
                                <input readonly disabled type="text" class="form-control" id="daily_total"
                                       value="{{$item['updated_at']}}">
                            </div>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">SKU</th>
                                    <th scope="col">Цена USD</th>
                                    <th scope="col">Цена UAH</th>
                                    <th scope="col">Название</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $item['checks'] as $val)
                                    <tr>
                                        <th colspan="4" scope="col" class="table-primary text-center">
                                            Чек № {{$loop->iteration}}
                                        </th>
                                    </tr>
                                    @if (isset($val['sku']))
                                        <tr>
                                            <th style="cursor: pointer" onclick="copy(this)">{{$val['sku']}}</th>
                                            <td>{{$val['price']}}</td>
                                            <td>{{$val['price_uah']}}</td>
                                            <td>{{$val['name']}}</td>
                                        </tr>
                                    @else
                                        @foreach($val as $valMultiple)
                                            <tr>
                                                <th style="cursor: pointer" onclick="copy(this)">{{$valMultiple['sku']}}</th>
                                                <td>{{$valMultiple['price']}}</td>
                                                <td>{{$valMultiple['price_uah']}}</td>
                                                <td>{{$valMultiple['name']}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @else
                    <caption>Данные не найдены</caption>
                @endif
            </div>
        </div>
</main>
@include('footer')
