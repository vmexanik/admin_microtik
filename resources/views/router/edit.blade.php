@include('header')
<main class="flex-shrink-0">
    <div class="container">
            <form action="{{ route('router.update',$router->id) }}" method="POST"
                  class="row gy-2 gx-3 align-items-end border rounded m-0">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="col-auto">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{$router->name}}">
                </div>
                <div class="col-auto">
                    <label for="name" class="form-label">IP</label>
                    <input type="text" name="ip_address" class="form-control" id="ip_address" value="{{$router->ip_address}}">
                </div>
                <div class="col-auto">
                    <label for="name" class="form-label">Порт</label>
                    <input type="text" name="port" class="form-control" id="port" value="{{$router->port}}">
                </div>
                <div class="col-auto">
                    <label for="name" class="form-label">Логин</label>
                    <input type="text" name="login" class="form-control" id="login" value="{{$router->login}}">
                </div>
                <div class="col-auto">
                    <label for="name" class="form-label">Пароль</label>
                    <input type="text" name="password" class="form-control" id="password" value="{{$router->password}}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary">Сохранить</button>
                </div>
            </form>
    </div>
</main>
@include('footer')
