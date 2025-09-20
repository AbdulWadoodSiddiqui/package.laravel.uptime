<?php

namespace Uptime\Monitoring\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RouteLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_name',
        'route_uri',
        'method',
        'status_code',
        'response_time_ms',
        'request_headers',
        'response_headers',
        'request_data',
        'response_data',
        'user_agent',
        'ip_address',
        'user_id',
        'project_id',
        'session_id',
        'error_message',
        'logged_at',
    ];

    protected $casts = [
        'request_headers' => 'array',
        'response_headers' => 'array',
        'request_data' => 'array',
        'response_data' => 'array',
        'logged_at' => 'datetime',
    ];

    protected $table = 'route_logs';
}
