@include('header')
<main class="flex-shrink-0">
    <div class="container">
        <div class="container d-flex justify-content-center">
            <form method="POST" action="{{ route('login') }}" class="w-50 border rounded p-3 align-self-center mt-3">
                @csrf
                <!-- Email Address -->
                <div class="mb-3">
                    <label for="name" class="form-label">Имя</label>
                    <input id="name" name="name" value="{{old('name')}}" class="form-control"
                           aria-describedby="emailHelp">
                    @if ($errors->has('name'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>
                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" class="form-control" id="password"
                           name="password" required autocomplete="current-password">
                </div>
                <!-- Remember Me -->
                <div class="mb-3 form-check">
                    <input name="remember" type="checkbox" class="form-check-input" id="remember_me" >
                    <label class="form-check-label" for="exampleCheck1">Запомнить меня</label>
                </div>
                <button type="submit" class="btn btn-primary">Вход</button>
            </form>
        </div>
    </div>
</main>
@include('footer')
