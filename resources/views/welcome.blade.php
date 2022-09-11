@include('header')
<main class="flex-shrink-0">
    <div class="container">
        @if (Route::has('login'))
            @auth
                @include('layouts.dashboard')
            @else
                @include('layouts.not_login_main')
            @endauth
        @endif
    </div>
</main>
@include('footer')
