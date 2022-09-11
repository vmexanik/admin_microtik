@include('header')
<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-4 mb-5">Загрузка списков</h1>
        <div class="row justify-content-md-center">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <form class="row g-3 border rounded" action="{{url('price')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row mb-1">
                    <div class="col-md-4">
                        <label for="number_of_fop" class="form-label">ФОП</label>
                        <select id="number_of_fop" class="form-select" name="number_of_fop">
                            <option selected>Выберете ФОП</option>
                            @foreach ($aFop as $fop)
                                <option value="{{$fop->id}}" >{{$fop->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('number_of_fop'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('number_of_fop') }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <label for="file" class="form-label">Таблица цен</label>
                        <input class="form-control" type="file" id="file" name="file">
                    </div>
                </div>
                <div class="row align-items-end">
                    <div class="col-md-2">
                        <label for="start_row" class="form-label">№ строки начала загрузки</label>
                        <input type="text" class="form-control" id="start_row" name="start_row" value="2">
                    </div>
                    <div class="col-md-2">
                        <label for="sku_col" class="form-label">№ колонки SKU</label>
                        <input type="text" class="form-control" id="sku_col" name="sku_col" value="2">
                    </div>
                    <div class="col-md-2">
                        <label for="name_col" class="form-label">№ колонки названия</label>
                        <input type="text" class="form-control" id="name_col" name="name_col" value="3">
                    </div>
                    <div class="col-md-2">
                        <label for="price_usd" class="form-label">№ колонки цены в USD</label>
                        <input type="text" class="form-control" id="price_usd" name="price_usd" value="4">
                    </div>
                </div>
                <div class="col-12 mb-2">
                    <button type="submit" class="btn btn-primary">Загрузить</button>
                </div>
            </form>
        </div>
    </div>
</main>
@include('footer')
