<?php

namespace App\Http\Repositories\Product;

use App\Http\Repositories\BaseRepository;
use App\Models\Product;

class ProductRepository extends BaseRepository
{
  public function __construct(Product $product)
  {
    parent::__construct($product);
  }

  public static function make(): Self
  {
    return new self(new Product);
  }
}
