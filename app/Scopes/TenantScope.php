<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
  public function apply(Builder $builder, Model $model){
    if ($tenantId = currentTenantId()) {
      $builder->where('tenant_id', $tenantId);
    }
  }
}