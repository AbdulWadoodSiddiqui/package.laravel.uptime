<?php

namespace Uptime\Monitoring\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Uptime\Monitoring\Services\MonitoringService;

class ApiController
{
    protected MonitoringService $monitoringService;

    public function __construct(MonitoringService $monitoringService)
    {
        $this->monitoringService = $monitoringService;
    }

    /**
     * Log API response from remote project
     */
    public function logApiResponse(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'method' => 'required|string',
            'url' => 'required|url',
            'status_code' => 'required|integer|min:100|max:599',
            'response_time_ms' => 'required|numeric|min:0',
            'request_headers' => 'nullable|array',
            'response_headers' => 'nullable|array',
            'request_body' => 'nullable|array',
            'response_body' => 'nullable|array',
            'user_agent' => 'nullable|string',
            'ip_address' => 'nullable|ip',
            'user_id' => 'nullable|integer',
            'error_message' => 'nullable|string',
        ]);

        try {
            $project = $request->get('monitoring_project');
            
            \Uptime\Monitoring\Models\ApiLog::create([
                'method' => $validated['method'],
                'url' => $validated['url'],
                'status_code' => $validated['status_code'],
                'response_time_ms' => $validated['response_time_ms'],
                'request_headers' => $validated['request_headers'] ?? [],
                'response_headers' => $validated['response_headers'] ?? [],
                'request_body' => $validated['request_body'] ?? [],
                'response_body' => $validated['response_body'] ?? [],
                'user_agent' => $validated['user_agent'],
                'ip_address' => $validated['ip_address'],
                'user_id' => $validated['user_id'],
                'project_id' => $project->id,
                'error_message' => $validated['error_message'],
                'logged_at' => now(),
            ]);

            return response()->json(['message' => 'API response logged successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to log API response'], 500);
        }
    }

    /**
     * Log route access from remote project
     */
    public function logRouteAccess(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'route_name' => 'nullable|string',
            'route_uri' => 'nullable|string',
            'method' => 'required|string',
            'status_code' => 'required|integer|min:100|max:599',
            'response_time_ms' => 'required|numeric|min:0',
            'request_headers' => 'nullable|array',
            'response_headers' => 'nullable|array',
            'request_data' => 'nullable|array',
            'response_data' => 'nullable|array',
            'user_agent' => 'nullable|string',
            'ip_address' => 'nullable|ip',
            'user_id' => 'nullable|integer',
            'session_id' => 'nullable|string',
            'error_message' => 'nullable|string',
        ]);

        try {
            $project = $request->get('monitoring_project');
            
            \Uptime\Monitoring\Models\RouteLog::create([
                'route_name' => $validated['route_name'],
                'route_uri' => $validated['route_uri'],
                'method' => $validated['method'],
                'status_code' => $validated['status_code'],
                'response_time_ms' => $validated['response_time_ms'],
                'request_headers' => $validated['request_headers'] ?? [],
                'response_headers' => $validated['response_headers'] ?? [],
                'request_data' => $validated['request_data'] ?? [],
                'response_data' => $validated['response_data'] ?? [],
                'user_agent' => $validated['user_agent'],
                'ip_address' => $validated['ip_address'],
                'user_id' => $validated['user_id'],
                'project_id' => $project->id,
                'session_id' => $validated['session_id'],
                'error_message' => $validated['error_message'],
                'logged_at' => now(),
            ]);

            return response()->json(['message' => 'Route access logged successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to log route access'], 500);
        }
    }

    /**
     * Get project statistics
     */
    public function getProjectStats(Request $request): JsonResponse
    {
        $project = $request->get('monitoring_project');
        
        $stats = [
            'api_logs_count' => \Uptime\Monitoring\Models\ApiLog::where('project_id', $project->id)->count(),
            'route_logs_count' => \Uptime\Monitoring\Models\RouteLog::where('project_id', $project->id)->count(),
            'error_count' => \Uptime\Monitoring\Models\ApiLog::where('project_id', $project->id)
                ->where('status_code', '>=', 400)->count(),
            'avg_response_time' => \Uptime\Monitoring\Models\ApiLog::where('project_id', $project->id)
                ->avg('response_time_ms'),
        ];

        return response()->json($stats);
    }

    /**
     * Health check endpoint
     */
    public function healthCheck(): JsonResponse
    {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'version' => '1.0.0'
        ]);
    }
}
