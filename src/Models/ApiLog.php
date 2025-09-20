<?php

namespace Uptime\Monitoring\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'method',
        'url',
        'status_code',
        'response_time_ms',
        'request_headers',
        'response_headers',
        'request_body',
        'response_body',
        'user_agent',
        'ip_address',
        'user_id',
        'project_id',
        'error_message',
        'logged_at',
    ];

    protected $casts = [
        'request_headers' => 'array',
        'response_headers' => 'array',
        'request_body' => 'array',
        'response_body' => 'array',
        'logged_at' => 'datetime',
    ];

    protected $table = 'api_logs';
}
