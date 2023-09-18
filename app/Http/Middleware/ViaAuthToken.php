<?php
namespace App\Http\Middleware;

use Closure;
use App\Helpers\JsonResponse;

class ViaAuthToken {
    use JsonResponse;

    public function handle($request, Closure $next)
    {

        $authToken = $request->bearerToken();

        if (!$authToken || $authToken !== env('APP_TOKEN')) {
           return $this->setError('Token invalid', [], 401 );
        }

        return $next($request);
    }
}
