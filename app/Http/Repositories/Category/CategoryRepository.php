<?php

namespace App\Http\Repositories\Category;

use App\Http\Repositories\BaseRepository;
use App\Models\Category;

class CategoryRepository extends BaseRepository
{
  public function __construct(Category $category)
  {
    parent::__construct($category);
  }

  public static function make(): Self
  {
    return new self(new Category);
  }
}
