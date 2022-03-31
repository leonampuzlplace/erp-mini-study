<?php

namespace App\Http\Dto\Company;

use App\Http\Dto\BaseDto;

class CompanyDto extends BaseDto
{
  private int $company_id;
  private string $business_name;
  private string $alias_name;
  private string $company_ein;
  private string $state_registration;
  private int $icms_taxpayer;
  private string $municipal_registration;
  private string $note_general;
  private string $internet_page;

  private function __construct()
  {
  } 
  
  public static function make()
  {
    return new self();
  }

  public function fromArray(array $data = [])
  {
    $this->company_id = $data['company_id'] ?? 0;
    $this->business_name = $data['business_name'] ?? '';
    $this->alias_name = $data['alias_name'] ?? '';
    $this->company_ein = $data['company_ein'] ?? '';
    $this->state_registration = $data['state_registration'] ?? '';
    $this->icms_taxpayer = $data['icms_taxpayer'] ?? 0;
    $this->municipal_registration = $data['municipal_registration'] ?? '';
    $this->note_general = $data['note_general'] ?? '';
    $this->internet_page = $data['internet_page'] ?? '';

    return $this;
  }

  public function toArray(): array
  {
    return [
      'company_id' => $this->company_id,
      'business_name' => $this->business_name,
      'alias_name' => $this->alias_name,
      'company_ein' => $this->company_ein,
      'state_registration' => $this->state_registration,
      'icms_taxpayer' => $this->icms_taxpayer,
      'municipal_registration' => $this->municipal_registration,
      'note_general' => $this->note_general,
      'internet_page' => $this->internet_page,
    ];
  }
}
