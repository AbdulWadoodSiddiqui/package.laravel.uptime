<?php

namespace Uptime\Monitoring\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Uptime\Monitoring\Models\ApiLog;
use Uptime\Monitoring\Models\RouteLog;
use Illuminate\Support\Facades\Log;

class MonitoringService
{
    /**
     * Log API response
     */
    public function logApiResponse(Request $request, SymfonyResponse $response, float $responseTime): void
    {
        try {
            ApiLog::create([
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'status_code' => $response->getStatusCode(),
                'response_time_ms' => $responseTime,
                'request_headers' => $this->sanitizeHeaders($request->headers->all()),
                'response_headers' => $this->sanitizeHeaders($response->headers->all()),
                'request_body' => $this->sanitizeRequestBody($request),
                'response_body' => $this->sanitizeResponseBody($response),
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'user_id' => auth()->id(),
                'project_id' => $this->getProjectId($request),
                'error_message' => $this->getErrorMessage($response),
                'logged_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log API response: ' . $e->getMessage());
        }
    }

    /**
     * Log route access
     */
    public function logRouteAccess(Request $request, SymfonyResponse $response, float $responseTime): void
    {
        try {
            RouteLog::create([
                'route_name' => $request->route()?->getName(),
                'route_uri' => $request->route()?->uri(),
                'method' => $request->method(),
                'status_code' => $response->getStatusCode(),
                'response_time_ms' => $responseTime,
                'request_headers' => $this->sanitizeHeaders($request->headers->all()),
                'response_headers' => $this->sanitizeHeaders($response->headers->all()),
                'request_data' => $this->sanitizeRequestData($request),
                'response_data' => $this->sanitizeResponseData($response),
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'user_id' => auth()->id(),
                'project_id' => $this->getProjectId($request),
                'session_id' => session()->getId(),
                'error_message' => $this->getErrorMessage($response),
                'logged_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log route access: ' . $e->getMessage());
        }
    }

    /**
     * Sanitize request headers
     */
    protected function sanitizeHeaders(array $headers): array
    {
        $sensitiveHeaders = ['authorization', 'cookie', 'x-api-key'];
        
        return collect($headers)->map(function ($value, $key) use ($sensitiveHeaders) {
            if (in_array(strtolower($key), $sensitiveHeaders)) {
                return '[REDACTED]';
            }
            return is_array($value) ? $value[0] : $value;
        })->toArray();
    }

    /**
     * Sanitize request body
     */
    protected function sanitizeRequestBody(Request $request): ?array
    {
        $sensitiveFields = ['password', 'token', 'secret', 'key'];
        $data = $request->all();
        
        return $this->sanitizeData($data, $sensitiveFields);
    }

    /**
     * Sanitize request data for route logging
     */
    protected function sanitizeRequestData(Request $request): ?array
    {
        $sensitiveFields = ['password', 'token', 'secret', 'key'];
        $data = $request->all();
        
        return $this->sanitizeData($data, $sensitiveFields);
    }

    /**
     * Sanitize response body for logging
     */
    protected function sanitizeResponseBody(SymfonyResponse $response): ?array
    {
        $content = $response->getContent();
        
        if (empty($content)) {
            return null;
        }

        $decoded = json_decode($content, true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return ['raw_content' => substr($content, 0, 1000)]; // Limit raw content
    }

    /**
     * Sanitize response data for route logging
     */
    protected function sanitizeResponseData(SymfonyResponse $response): ?array
    {
        return $this->sanitizeResponseBody($response);
    }

    /**
     * Sanitize data by removing sensitive fields
     */
    protected function sanitizeData(array $data, array $sensitiveFields): array
    {
        foreach ($data as $key => $value) {
            if (in_array(strtolower($key), $sensitiveFields)) {
                $data[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $data[$key] = $this->sanitizeData($value, $sensitiveFields);
            }
        }

        return $data;
    }

    /**
     * Get error message from response
     */
    protected function getErrorMessage(SymfonyResponse $response): ?string
    {
        if ($response->getStatusCode() < 400) {
            return null;
        }

        $content = $response->getContent();
        $decoded = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['message'])) {
            return $decoded['message'];
        }

        return $response->statusText();
    }

    /**
     * Get project ID from request
     */
    protected function getProjectId(Request $request): ?int
    {
        // This can be customized based on how you identify projects
        return $request->header('X-Project-ID') ?: null;
    }
}