<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
/**
 * Lista de controle de acessos
 * Se for usuário marcado como administrador (is_admin), terá acesso a todas as rotas
 * Quando não existir regra cadastrada, irá permitir o acesso
 * Quando existir regra cadastrada, irá consultar se é permitido ou não o acesso (is_allowed)
 */
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
        $user = currentUser();
        $currentAction = $request->route()->getName();
        $isAllowed = $user['is_admin'];

        // Se não for admin, irá checar o acesso
        if ((!$isAllowed) && ($user['role']) && ($permission = $user['role']['role_permission'])) {
            // Procurar por regra de acesso
            $ruleExists = array_filter($permission, fn ($i) => $i['action_name'] === $currentAction);

            // Se existir regra cadastrada, irá consultar se é permitido ou não o acesso (is_allowed)
            // Se não existir regra cadastrada, irá permitir o acesso
            $isAllowed = match (count($ruleExists)) {
                1 => [...$ruleExists][0]['is_allowed'],
                0 => true,
            };
        }

        throw_if(!$isAllowed, new \Exception(trans('auth_lang.not_authorized')));
        return $next($request);
    }
}