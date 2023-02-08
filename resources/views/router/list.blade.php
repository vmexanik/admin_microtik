@include('header')
<main class="flex-shrink-0">
    <div class="container">
        <table class="table table-hover mt-2">
            <thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Router Name</th>
                <th scope="col">IP</th>
                <th scope="col">Login</th>
                <th scope="col">Password</th>
                <th scope="col">API Port</th>
                <th scope="col">SSH Port</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @if (count($routers)>0)
                @foreach($routers as $router)
                    <tr class="align-middle">
                        <td scope="row">{{$router->id}}</td>
                        <td>{{$router->name}}</td>
                        <td>{{$router->ip_address}}</td>
                        <td>{{$router->login}}</td>
                        <td>{{$router->password}}</td>
                        <td>{{$router->port}}</td>
                        <td>{{$router->ssh_port}}</td>
                        <td class="d-flex">
                            <a type="button" class="btn btn-outline-success" style="margin-right: 5px" href="{{ route('router.edit',$router->id) }}">Edit</a>
                            <form action="{{ route('router.destroy',$router->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{$router->id}}">
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="align-middle">
                    <td scope="row" colspan="7">Nothing..</td>
                </tr>
            @endif
            </tbody>
        </table>
        <h3>Add New Router</h3>
        <form class="row gy-2 gx-3 align-items-end border rounded m-0 pb-2" method="get" action="{{ route('router.create') }}">
            @csrf
            <div class="mb-3">
                @if ($errors->any())
                    <div class="invalid-feedback d-block">
                        @foreach ($errors->all() as $error)
                            {{$error}}
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-auto">
                <label for="name" class="form-label">Router Name</label>
                <input type="text" name="name" class="form-control" id="name" value="{{old('name')}}">
            </div>
            <div class="col-auto">
                <label for="name" class="form-label">IP</label>
                <input type="text" name="ip_address" class="form-control" id="ip_address" value="{{old('ip_address')}}">
            </div>
            <div class="col-auto">
                <label for="name" class="form-label">API Port</label>
                <input type="number" name="port" class="form-control" id="port" value="@if (!old('port')){{8728}}@else{{old('port')}}@endif">
            </div>
            <div class="col-auto">
                <label for="name" class="form-label">Login</label>
                <input type="text" name="login" class="form-control" id="login" value="{{old('login')}}">
            </div>
            <div class="col-auto">
                <label for="name" class="form-label">Password</label>
                <input type="text" name="password" class="form-control" id="password" value="{{old('password')}}">
            </div>
            <div class="col-auto">
                <label for="name" class="form-label">SSH Port</label>
                <input type="number" name="ssh_port" class="form-control" id="port_ssh" value="{{old('ssh_port')}}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">Add</button>
            </div>
        </form>
        @if (Auth::user()->user_type=='admin')
            <h3 class="mt-2">Backup Path</h3>
            <form class="row gy-2 gx-3 align-items-end border rounded m-0 pb-2" method="get" action="/path">
                @csrf
                <div class="mb-3">
                    @if ($errors->any())
                        <div class="invalid-feedback d-block">
                            @foreach ($errors->all() as $error)
                                {{$error}}
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col">
                    <label for="name" class="form-label">root-path</label>
                    <input type="text" name="path" class="form-control" id="path" value="{{$path->path}}">
                </div>
                <div class="col">
                    <label for="name" class="form-label">User</label>
                    <input type="text" name="user" class="form-control" id="path" value="{{$path->user}}">
                </div>
                <div class="col">
                    <label for="name" class="form-label">Password</label>
                    <input type="text" name="password" class="form-control" id="path" value="{{$path->password}}">
                </div>
                <div class="col">
                    <label for="name" class="form-label">FTP Host</label>
                    <input type="text" name="host" class="form-control" id="path" value="{{$path->host}}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary">Add</button>
                </div>
            </form>
        @endif
    </div>
</main>
@include('footer')
