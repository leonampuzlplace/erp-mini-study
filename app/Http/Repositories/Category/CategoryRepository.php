<?php

namespace App\Http\Repositories\Category;

use App\Http\Repositories\BaseRepository;
use App\Models\Category\Category;

class CategoryRepository extends BaseRepository
{
  public function __construct(Category $model)
  {
    parent::__construct($model);
  }

  public static function make(): Self
  {
    return new self(new Category);
  }
}
