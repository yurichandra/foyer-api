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

    public function includeParams()
    {
        is_null($this->route[2]) ? false : true;
    }

    public function url()
    {
        if ($this->includeParams()) {
            return $this->rearrangeUrl($this->route_data['url']);
        }

        return $this->route_data['url'];
    }

    public function get(Request $request, HttpService $service)
    {
        try {
            $response = $service->get($this->url(), $request->toArray());

            return response()->json($response['message'], $response['status']);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function post(Request $request, HttpService $service)
    {
        try {
            $response = $service->post($this->url(), $request->toArray());

            return response()->json($response['message'], $response['status']);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function patch(Request $request, HttpService $service)
    {
        try {
            $response = $service->patch($this->url(), $request->toArray());

            return response()->json($response['message'], $response['status']);
        } catch (\Exception $e) {
            throw $e;
        }
    }

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
