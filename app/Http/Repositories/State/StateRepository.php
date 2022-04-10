<?php

namespace App\Http\Repositories\State;

use App\Http\Repositories\BaseRepository;
use App\Models\State;

class StateRepository extends BaseRepository
{
  private function __construct(State $state)
  {
    parent::__construct($state);
  }

  public static function make(): Self
  {
    return new self(new State);
  }  
}