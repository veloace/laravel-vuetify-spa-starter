<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class WebAppShellController extends Controller
{
    //


    /**
     * Returns the Web App Shell with the appropriate data needed by our application
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['token']=json_encode(['csrfToken' => csrf_token()]);
        $routes = $this->getWebAPIRoutes();
        $data['routes']=json_encode($routes);
        return view('webAppShell',$data);
    }

    /**
     *
     * Gets all of the web API routes as an array for the front end to user. Array is keyed by the route name,
     * and the value is another array consting of the Route URL and the Route HTTP request method (GET, POST, PATCH, DELETE)
     *
     * @return array list of all web API routes
     */
    public function getWebAPIRoutes()
    {
        $allRoutes = Route::getRoutes();
        $baseURL = config('app.url');
        $webAPIRoutes = [];
        foreach ($allRoutes as $route)
        {


            if(strpos($route->uri, 'webAPI') !== false && $name = $route->getName())
            {//they must contain the webAPI prefix AND have to have a name
                foreach ( $route->methods as $method)
                {
                    if($route->methods !=='HEAD')
                    {
                        $requestMethod = $method;
                        break;
                    }
                }
                $webAPIRoutes[$name] = [
                        'url'=>"{$baseURL}/$route->uri",
                        'method'=>!empty($requestMethod)? $requestMethod:'GET',
                ];
            }


        }

        return $webAPIRoutes;
    }
}
