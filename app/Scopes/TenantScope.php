<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
  public function __construct(
    protected int|null $currentTenantId
  ) {
  }

  public function apply(Builder $builder, Model $model){
    if ($this->currentTenantId) {
      $builder->where($model->getTable().'.tenant_id', $this->currentTenantId);
    }
  }
}