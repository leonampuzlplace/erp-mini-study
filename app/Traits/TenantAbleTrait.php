<?php

namespace App\Traits;

use App\Scopes\TenantScope;

trait TenantAbleTrait
{
  protected static function bootTenantAbleTrait(){
    $currentTenantId = currentTenantId();
    static::addGlobalScope(new TenantScope($currentTenantId));
    static::creating(fn ($model) => $model->tenant_id = $currentTenantId);
    static::updating(fn ($model) => $model->tenant_id = $currentTenantId);
  }

  public function tenant()
  {
    return $this->belongsTo(Tenant::class);
  }
}