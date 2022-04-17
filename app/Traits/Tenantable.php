<?php

namespace App\Traits;

use App\Scopes\TenantScope;

trait Tenantable
{
  protected static function bootTenantable(){
    $tenant_id = auth()->user()->tenant_id ?? null;
    static::addGlobalScope(new TenantScope($tenant_id));
    
    static::creating(function ($model) use ($tenant_id) {
      $model->tenant_id = $tenant_id;
    });
  }

  public function tenant()
  {
    return $this->belongsTo(Tenant::class);
  }
}
