<?php

namespace App\Http\Repositories\Brand;

use App\Http\Repositories\BaseRepository;
use App\Models\Brand\Brand;

class BrandRepository extends BaseRepository
{
  public function __construct(Brand $brand)
  {
    parent::__construct($brand);
  }

  public static function make(): Self
  {
    return new self(new Brand);
  }
}
