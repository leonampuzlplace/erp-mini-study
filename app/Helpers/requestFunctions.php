<?php
// Como configurar arquivo helper? 
// Adicione o caminho em composer.json na seção autoload, files. 
// Depois rodar comando composer dump-autoload -o

use Illuminate\Support\Facades\Cache;

if (!function_exists('tokenFromCurrentRequest')) {
  function tokenFromCurrentRequest(): string
  {
    return str_replace(
      "Bearer ",
      "",
      request()->header('Authorization') ?? ''
    );
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

    
