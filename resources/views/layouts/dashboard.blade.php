<h1 class="mt-4 mb-5">Привет, {{ Auth::user()->name }}. Сегодня {{ date('d-m-Y') }}</h1>
<form class="row g-3 border rounded m-0" action="{{url('/')}}" method="post">
    @csrf
    <div class="col-md-3">
        <label for="daily_total" class="form-label">Сумма за день</label>
        <input type="text" class="form-control" id="daily_total" name="daily_total" value="{{ old('daily_total') }}">
        @if ($errors->has('daily_total'))
            <div class="invalid-feedback d-block">
                {{ $errors->first('daily_total') }}
            </div>
        @endif
    </div>
    <div class="col-md-2">
        <label for="course_usd" class="form-label">Курс USD</label>
        <input type="text" class="form-control" id="course_usd" name="course_usd" value="{{ old('course_usd') }}">
        @if ($errors->has('course_usd'))
            <div class="invalid-feedback d-block">
                {{ $errors->first('course_usd') }}
            </div>
        @endif
    </div>
    <div class="col-md-3">
        <label for="count_of_checks" class="form-label">Количество чеков</label>
        <input type="number" class="form-control" id="count_of_checks" name="count_of_checks"
               value="{{ old('count_of_checks') }}">
        @if ($errors->has('count_of_checks'))
            <div class="invalid-feedback d-block">
                {{ $errors->first('count_of_checks') }}
            </div>
        @endif
    </div>
    <div class="col-md-4">
        <label for="number_of_fop" class="form-label">ФОП</label>
        <select id="number_of_fop" class="form-select" name="number_of_fop">
            <option
                @if(!old('number_of_fop'))
                    selected
                @endif
            >Выберете ФОП</option>
            @foreach ($aFop as $fop)
                <option
                    @if(old('number_of_fop')==$fop->id)
                        selected
                    @endif
                    value="{{$fop->id}}">{{$fop->name}}</option>
            @endforeach
        </select>
        @if ($errors->has('number_of_fop'))
            <div class="invalid-feedback d-block">
                {{ $errors->first('number_of_fop') }}
            </div>
        @endif
    </div>
    <div class="col-12 mb-2">
        <button type="submit" class="btn btn-primary">Сгенерировать</button>
    </div>
</form>
@if (isset($error))
    <div class="alert alert-danger mb-2 mt-2">
        {{ $error }}
    </div>
@endif
<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col"></th>
        <th scope="col">SKU</th>
        <th scope="col">Цена USD</th>
        <th scope="col">Цена UAH</th>
        <th scope="col">Название</th>
    </tr>
    </thead>
    <tbody>
    @if (!empty($checks))
        @foreach( $checks as $val)
            <tr>
                <th colspan="5" scope="col" class="table-primary text-center">Чек № {{$loop->iteration}}</th>
            </tr>
            @if (isset($val['sku']))
            <tr>
                <th>
                    <input class="form-check-input" type="checkbox" value="">
                </th>
                <th style="cursor: pointer" onclick="copy(this)">{{$val['sku']}}</th>
                <td>{{$val['price']}}</td>
                <td>{{$val['price_uah']}}</td>
                <td>{{$val['name']}}</td>
            </tr>
            @else
                @foreach($val as $valMultiple)
                    <tr>
                        <th>
                            <input class="form-check-input" type="checkbox" value="">
                        </th>
                        <th style="cursor: pointer" onclick="copy(this)">{{$valMultiple['sku']}}</th>
                        <td>{{$valMultiple['price']}}</td>
                        <td>{{$valMultiple['price_uah']}}</td>
                        <td>{{$valMultiple['name']}}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    @endif
    </tbody>
    <caption>Сумма по дню: {{ $sum_uah }} грн. или {{ $sum_usd  }} USD</caption>
</table>

