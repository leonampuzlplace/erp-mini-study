<?php

namespace App\Http\Repositories\Brand;

use App\Http\Repositories\BaseRepository;
use App\Models\Brand\Brand;

class BrandRepository extends BaseRepository
{
  public function __construct(Brand $model)
  {
    parent::__construct($model);
  }

  public static function make(): Self
  {
    return new self(new Brand);
  }
}
