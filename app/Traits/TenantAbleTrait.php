<?php

namespace App\Traits;

use App\Scopes\TenantScope;

trait TenantAbleTrait
{
  protected static function bootTenantAbleTrait(){
    $currentTenantId = currentTenantId();
    $saveExecuted = fn ($model) => $model->tenant_id = $currentTenantId;
    static::addGlobalScope(new TenantScope($currentTenantId));
    static::updating($saveExecuted);
    static::creating($saveExecuted);
  }

  public function tenant()
  {
    return $this->belongsTo(Tenant::class);
  }
}