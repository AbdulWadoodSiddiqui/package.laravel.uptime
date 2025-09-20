<?php

namespace Uptime\Monitoring;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Uptime\Monitoring\Models\ApiLog;
use Uptime\Monitoring\Models\RouteLog;
use Uptime\Monitoring\Services\MonitoringService;

class MonitoringManager
{
    protected MonitoringService $service;

    public function __construct($app)
    {
        $this->service = new MonitoringService();
    }

    /**
     * Log API response
     */
    public function logApiResponse(Request $request, SymfonyResponse $response, float $responseTime): void
    {
        $this->service->logApiResponse($request, $response, $responseTime);
    }

    /**
     * Log route access
     */
    public function logRouteAccess(Request $request, SymfonyResponse $response, float $responseTime): void
    {
        $this->service->logRouteAccess($request, $response, $responseTime);
    }

    /**
     * Check if request should be logged based on configuration
     */
    protected function shouldLog(Request $request, SymfonyResponse $response): bool
    {
        $config = config('monitoring', []);
        
        // Check if monitoring is enabled
        if (!($config['enabled'] ?? true)) {
            return false;
        }

        // Check if we should only log errors
        if ($config['log_errors_only'] ?? false) {
            return $response->getStatusCode() >= 400;
        }

        // Check excluded routes
        $excludedRoutes = $config['excluded_routes'] ?? [];
        foreach ($excludedRoutes as $pattern) {
            if (fnmatch($pattern, $request->path())) {
                return false;
            }
        }

        return true;
    }
}
