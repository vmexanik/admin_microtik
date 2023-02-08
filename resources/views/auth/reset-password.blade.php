@include('header')
<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-4 mb-5">Change password of {{$user->name}}</h1>
        <div class="row justify-content-md-center">
            @if (session('errors'))
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br/>
                    @endforeach
                </div>
            @endif
            <div class="col-7">
                <form class="row gy-2 gx-3 align-items-end border rounded m-0" method="post"
                      action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{$token}}">
                    <input name="name" type="hidden" value="{{$user->name}}">
                    <div class="row m-0 mb-2">
                        <label for="password" class="form-label">Password</label>
                        <input name="password" type="text" class="form-control" id="password" placeholder=""
                               required autocomplete="new-password">
                    </div>
                    <div class="row m-0 mb-2">
                        <label for="password_confirmation" class="form-label">Confirm password</label>
                        <input name="password_confirmation" type="text" class="form-control" id="password_confirmation"
                               placeholder=""
                               required>
                    </div>
                    <div class="row m-0 mb-2">
                        <button type="submit" class="btn btn-primary">Change password</button>
                    </div>
                </form>
            </div>
            <div class="row">
            </div>
        </div>
</main>
@include('footer')
