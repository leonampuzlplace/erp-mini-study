<?php

namespace App\Traits;

use App\Scopes\TenantScope;

trait TenantAbleTrait
{
  protected static function bootTenantAbleTrait(){
    static::addGlobalScope(new TenantScope);
    
    static::creating(function ($model) {
      $model->tenant_id = currentTenantId();
    });
  }

  public function tenant()
  {
    return $this->belongsTo(Tenant::class);
  }
}