<?php
// Como configurar arquivo helper? 
// Adicione o caminho em composer.json na seção autoload, files. 
// Depois rodar comando composer dump-autoload -o

use Illuminate\Support\Facades\Cache;

if (!function_exists('tokenFromCurrentRequest')) {
  function tokenFromCurrentRequest(): string
  {
    if (!$authorization = request()->header('Authorization')) {
      $authorization = request()->input('Authorization', '');
    }
    
    return str_replace("Bearer ", "", $authorization ?? '');
  }
}

if (!function_exists('currentUser')) {
  function currentUser(): array
  {
    return Cache::get(tokenFromCurrentRequest()) ?? [];
  }
}

if (!function_exists('currentTenantId')) {
  function currentTenantId(): int|null
  {
    return currentUser()['tenant_id'] ?? null;
  }
}

if (!function_exists('getRouteParameter')) {
  function getRouteParameter($route)
  {
    $parameters = $route->parameters ?? [];
    return array_shift($parameters);
  }
}