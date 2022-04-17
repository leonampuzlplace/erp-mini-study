<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
  public function __construct(
    protected int|null $tenant_id
  ){
  }
  
  public function apply(Builder $builder, Model $model){
    if ($this->tenant_id) {
      $builder->where('tenant_id', $this->tenant_id);
    }
  }
}