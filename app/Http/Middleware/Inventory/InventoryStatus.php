<?php

namespace Apps\Http\Middleware\Inventory;

use Closure;

// Models
use Apps\Models\Inventory\Inventory;

class InventoryStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $parameters = $request->route()->parameters();

        $inventory = Inventory::findOrFail($parameters['inventory']);

        if ($inventory->status_id != 30) {
            return response()->json([
                'message' => 'Sorry, you are not authorized to access this resource.',
                'errors' => [
                    'status' => ['El estado actual del Inventario no permite realizar esta acción.']
                ]
            ], 402);
        }

        return $next($request);
    }
}
