<?php

namespace App\Http\Controllers;

use App\Models\Fop;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Exceptions\BadCredentialsException;
use RouterOS\Exceptions\ClientException;
use RouterOS\Exceptions\ConfigException;
use RouterOS\Exceptions\ConnectException;
use RouterOS\Exceptions\QueryException;

class Router extends Controller
{
    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     */

    public function index(): Factory|View|Application
    {
        $routers=\App\Models\Router::orderBy('id', 'asc')->get();

        return view('router/list',[
            'routers'=>$routers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'ip_address'=>'required|ip',
                'login'=>'required',
                'password'=>'required',
                'port'=>'required'
            ]);

        if ($validator->fails()) {
            return redirect('/router')
                ->withInput()
                ->withErrors($validator);
        }

        $router = new \App\Models\Router();
        $router->name = $request->name;
        $router->ip_address = $request->ip_address;
        $router->login = $request->login;
        $router->password = $request->password;
        $router->port = $request->port;
        $router->save();

        return redirect('/router')->with('status','Сохранение успешно!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Router $fop
     * @return void
     */
    public function show(\App\Models\Router $fop): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Router $fop
     * @return Response
     */
    public function edit(\App\Models\Router $fop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\Models\Router $fop
     * @return Response
     */
    public function update(Request $request, \App\Models\Router $fop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Router $router
     * @return Redirector|Application|RedirectResponse
     */
    public function destroy(\App\Models\Router $router): Redirector|Application|RedirectResponse
    {
        $router->delete();

        return redirect('/router')->with('status','Удаление успешно!');
    }
}
