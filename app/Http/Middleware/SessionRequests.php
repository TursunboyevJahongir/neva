<?php
/**
 * Author Maxamadjonov Jaxongir.
 * https://github.com/jtscorpjaxon
 * Date: 15.09.2021
 * Time: 16:51
 */

namespace App\Http\Middleware;

use App\Services\History\HistoryService;
use Closure;
use Illuminate\Http\Request;

class SessionRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        (new HistoryService())->record($request);
        return $next($request);
    }
}