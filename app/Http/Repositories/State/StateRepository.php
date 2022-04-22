<?php

namespace App\Http\Repositories\State;

use App\Http\Repositories\BaseRepository;
use App\Models\State\State;

class StateRepository extends BaseRepository
{
  public function __construct(State $model)
  {
    parent::__construct($model);
  }

  public static function make(): Self
  {
    return new self(new State);
  }
}
