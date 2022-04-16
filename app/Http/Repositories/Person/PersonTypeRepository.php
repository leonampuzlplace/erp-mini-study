<?php

namespace App\Http\Repositories\Person;

use App\Http\Repositories\BaseRepository;
use App\Models\PersonType;

class PersonTypeRepository extends BaseRepository
{
  public function __construct(PersonType $personType)
  {
    parent::__construct($personType);
  }

  public static function make(): Self
  {
    return new self(new PersonType);
  }
}
