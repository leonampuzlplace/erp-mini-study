<?php

namespace App\Http\Repositories\Unit;

use App\Http\Repositories\BaseRepository;
use App\Models\Unit\Unit;

class UnitRepository extends BaseRepository
{
  public function __construct(Unit $unit)
  {
    parent::__construct($unit);
  }

  public static function make(): Self
  {
    return new self(new Unit);
  }
}
