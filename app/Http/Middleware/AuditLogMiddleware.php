<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Models\AuditLog;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     * Log POST/PUT/PATCH/DELETE requests with user id and request data (excluding sensitive fields).
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $method = strtoupper($request->method());
            if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                $userId = Auth::check() ? Auth::id() : null;

                // Remove sensitive fields
                $input = $request->except(['password', 'password_confirmation', '_token', 'current_password']);

                // Avoid storing uploaded files binary data; replace files with filenames
                foreach ($request->files->all() as $key => $file) {
                    if (is_array($file)) {
                        $input[$key] = array_map(fn($f) => is_object($f) && method_exists($f, 'getClientOriginalName') ? $f->getClientOriginalName() : null, $file);
                    } else {
                        if (is_object($file) && method_exists($file, 'getClientOriginalName')) {
                            $input[$key] = $file->getClientOriginalName();
                        }
                    }
                }

                AuditLog::create([
                    'user_id' => $userId,
                    'method' => $method,
                    'path' => $request->path(),
                    'url' => $request->fullUrl(),
                    'ip_address' => $request->ip(),
                    'status_code' => $response->getStatusCode(),
                    'input' => $input,
                ]);
            }
        } catch (\Throwable $e) {
            // Do not interrupt the main request, but log exception
            Log::error('Audit log failed: ' . $e->getMessage());
        }

        return $response;
    }
}
