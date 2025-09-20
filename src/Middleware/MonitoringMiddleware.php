<?php

namespace Uptime\Monitoring\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class MonitoringMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        $responseTime = (microtime(true) - $startTime) * 1000; // Convert to milliseconds
        
        // Log the request/response
        $monitoringManager = App::make(\Uptime\Monitoring\MonitoringManager::class);
        $monitoringManager->logRouteAccess($request, $response, $responseTime);
        
        return $response;
    }
}
