<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HttpService;
use App\Services\RoutingService;

class GatewayController extends Controller
{
    /**
     * Routes attributes.
     *
     * @var array
     */
    protected $route;

    /**
     * Routes data.
     */
    protected $route_data;

    /**
     * Route slug.
     */
    protected $route_slug;

    /**
     * Route parameters.
     */
    protected $route_params;

    /**
     * GatewayController construct.
     *
     * @param Request $request
     * @param HttpService $http_service
     * @param RoutingService $routing_service
     */
    public function __construct(Request $request, HttpService $http_service, RoutingService $routing_service)
    {
        $this->route = $request->route();
        $this->route_slug = $this->getRequestRouteSlug($request);
        $this->route_data = $routing_service->getRouteData($this->route_slug);
        $http_service->setHeaders($request);

        if ($this->includeParams()) {
            $this->route_params = $this->route[2];
        }
    }

    /**
     * Return slug from request route.
     *
     * @param Request $request
     */
    private function getRequestRouteSlug(Request $request)
    {
        return $request->path() . "." . strtolower($request->method());
    }

    /**
     * Method to check whether route include params or not.
     *
     * @return boolean
     */
    protected function includeParams()
    {
        return is_null($this->route[2]) ? false : true;
    }

    /**
     * Method to return an url to perform request.
     *
     * @return string $url
     */
    protected function url()
    {
        if ($this->includeParams()) {
            return $this->replaceParams($this->route_data['url']);
        }

        return $this->route_data['url'];
    }

    /**
     * Method to replace params if included.
     *
     * @return string $url
     */
    protected function replaceParams($url)
    {
        collect($this->route_params)
            ->each(function ($value, $key) use (&$url) {
                $wildcard = '{' . $key . '}';

                if (strpos($url, $wildcard) !== false) {
                    $url = str_replace($wildcard, $value, $url);
                }
            });

        return $url;
    }

    /**
     * Method to perform GET request.
     *
     * @param Request $request
     * @param HttpService $service
     * @return Response $response
     */
    public function get(Request $request, HttpService $service)
    {
        try {
            $response = $service->get($this->url(), $request->toArray());

            return response()->json($response['message'], $response['status']);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to perform POST request.
     *
     * @param Request $request
     * @param HttpService $service
     * @return Response $response
     */
    public function post(Request $request, HttpService $service)
    {
        try {
            $response = $service->post($this->url(), $request->toArray());

            return response()->json($response['message'], $response['status']);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to perform PATCH request.
     *
     * @param Request $request
     * @param HttpService $service
     * @return Response $response
     */
    public function patch(Request $request, HttpService $service)
    {
        try {
            $response = $service->patch($this->url(), $request->toArray());

            return response()->json($response['message'], $response['status']);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to perform DELETE request.
     *
     * @param Request $request
     * @param HttpService $service
     * @return Response $response
     */
    public function delete(Request $request, HttpService $service)
    {
        try {
            $response = $service->delete($this->url());

            return response()->json($response['message'], $response['status']);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
