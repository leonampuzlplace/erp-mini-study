<?php

namespace App\Http\Repositories\Product;

use App\Exceptions\ModelNotFoundException;
use App\Http\Repositories\BaseRepository;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelData\Data;

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

  /**
   * Método executado dentro de BaseRepository.index()
   * Adicionar join de tabelas e mostrar colunas específicas
   *
   * @param Builder $queryBuilder
   * @return array
   * Retornar um array contendo queryBuilder e string de colunas a serem exibidas
   */
  public function indexInside(Builder $queryBuilder): array
  {
    return [
      $queryBuilder
        ->leftJoin('unit', 'unit.id', 'product.unit_id')
        ->leftJoin('category', 'category.id', 'product.category_id')
        ->leftJoin('brand', 'brand.id', 'product.brand_id'),
      'product.*, ' .
      'unit.abbreviation as unit_abbreviation, ' .
      'unit.description  as unit_description, ' .
      'category.name     as category_name, ' .
      'brand.name        as brand_name'
    ];
  }

  /**
   * Localizar um único registro por ID
   * Acrescenta with para mostrar relacionamentos
   *
   * @param integer $id
   * @return Data
   */
  public function show(int $id): Data
  {
    $modelFound = $this->model
      ->where('id', $id)
      ->with('unit')
      ->with('category')
      ->with('brand')
      ->first();

    throw_if(!$modelFound, new ModelNotFoundException(trans('message_lang.model_not_found') . ' id: ' . $id));
    return $modelFound->getData();
  }  
}
