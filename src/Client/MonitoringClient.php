<?php

namespace Uptime\Monitoring\Client;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MonitoringClient
{
    protected string $baseUrl;
    protected string $apiKey;
    protected int $timeout;

    public function __construct(string $baseUrl, string $apiKey, int $timeout = 30)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->timeout = $timeout;
    }

    /**
     * Log API response
     */
    public function logApiResponse(array $data): bool
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->post($this->baseUrl . '/monitoring/api/log-api-response', $data);

            if ($response->successful()) {
                return true;
            }

            Log::warning('Failed to log API response', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Exception while logging API response: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Log route access
     */
    public function logRouteAccess(array $data): bool
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->post($this->baseUrl . '/monitoring/api/log-route-access', $data);

            if ($response->successful()) {
                return true;
            }

            Log::warning('Failed to log route access', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Exception while logging route access: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get project statistics
     */
    public function getProjectStats(): ?array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->get($this->baseUrl . '/monitoring/api/stats');

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Failed to get project stats', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Exception while getting project stats: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Health check
     */
    public function healthCheck(): bool
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->baseUrl . '/monitoring/api/health');

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Health check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get error message from response
     */
    protected function getErrorMessage(Response $response): ?string
    {
        $content = $response->getContent();
        $decoded = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['message'])) {
            return $decoded['message'];
        }

        return $response->statusText();
    }
}
