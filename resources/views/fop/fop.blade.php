@include('header')
<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-4 mb-5">Списки ФОП</h1>
        <div class="row justify-content-md-center">
            <div class="col-7">

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="row gy-2 gx-3 align-items-end border rounded m-0" method="get" action="{{ route('fop.create') }}">
                    @csrf
                    <div class="col-auto">
                        <label for="name" class="form-label">Имя</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{old('name')}}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                    <div class="mb-3">
                        @if ($errors->has('name'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>
                </form>
                <table class="table table-hover mt-3 align-middle">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Имя</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    @if (count($aFop) > 0)
                        @foreach ($aFop as $fop)
                            <tr>
                                <th scope="row">
                                    <p class="fs-5 m-0">
                                        {{$fop->id}}
                                    </p>
                                </th>
                                <td>
                                    <p class="fs-5 m-0">
                                        {{$fop->name}}
                                    </p>
                                </td>
                                <td>
                                    <form action="{{ route('fop.destroy',$fop->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{$fop->id}}">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Удалить
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">
                                <p class="lead">
                                    Не создано ни одного ФОПа
                                </p>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</main>
@include('footer')
