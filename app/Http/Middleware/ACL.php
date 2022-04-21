<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ACL
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Obter dados do usuário e nome da rota
        $user = currentUser();
        $currentAction = $request->route()->getName();

        // Se usuário for admin, terá acesso a todos os recursos
        $isAllowed = $user['is_admin'];

        // Se usuário não for admin, precisa checar o acesso
        if ((!$isAllowed) && ($user['role']) && ($permission = $user['role']['role_permission'])) {
            $isAllowed = count(array_filter($permission, function ($item) use($currentAction) { 
                return ($item['action_name'] === $currentAction) && ($item['is_allowed'] === true);
            }));
        }

        throw_if(!$isAllowed, new \Exception(trans('auth_lang.not_authorized')));
        return $next($request);
    }
}