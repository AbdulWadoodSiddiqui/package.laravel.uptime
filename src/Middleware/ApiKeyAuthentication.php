<?php

namespace Uptime\Monitoring\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiKeyAuthentication
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $apiKey = $request->header('X-API-Key') ?? $request->query('api_key');
        
        if (!$apiKey) {
            return response()->json([
                'error' => 'API key required',
                'message' => 'Please provide an API key in the X-API-Key header or api_key query parameter'
            ], 401);
        }

        // Validate API key against your projects table
        $project = \App\Models\Project::where('api_key', $apiKey)
            ->where('api_monitoring_enabled', true)
            ->first();

        if (!$project) {
            return response()->json([
                'error' => 'Invalid API key',
                'message' => 'The provided API key is invalid or monitoring is disabled for this project'
            ], 401);
        }

        // Store project in request for later use
        $request->merge(['monitoring_project' => $project]);

        return $next($request);
    }
}
