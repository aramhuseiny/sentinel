<?php

namespace App\Http\Middleware;

use Closure;
use Hedi\Sentinel\Models\PermissionRouteMapping;
use Illuminate\Support\Facades\Route;
use Sentinel;

class AuthenticatedUsersMiddleware
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
        $uri = $request->getRequestUri();
        $method = $request->method();

        $currentPath = "/".Route::getFacadeRoot()->current()->uri();

//        dd($request->all());
        //users should be authenticated
        if( Sentinel::check() )
        {
            $perms = PermissionRouteMapping::where('route','=',$uri)->orWhere('route','=',$currentPath)->get();

            if ( sizeof( $perms ) == 0 )
            {
                abort(403, 'Unauthorized action');
            }
            foreach ( $perms as $perm )
            {
                if( ($perm->route == $currentPath or $perm->route == $uri) and strtolower( $perm->method ) == strtolower($method) )
                {

                    $user = Sentinel::getUser();

                    $permission = $perm->permission;


                    if ($user->hasAccess([$permission]))
                    {
                        //authenticated users should have permission o access this route
                        return $next($request);
                    }
                }
            }

             abort(403, 'Unauthorized action');
        }

        // redirect the user to "/login"
        // and stores the url being accessed on session
        return redirect()->guest('login');


    }
}
