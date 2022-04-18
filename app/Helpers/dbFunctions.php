<?php
// Como configurar arquivo helper? 
// Adicione o caminho em composer.json na seção autoload, files. 
// Depois rodar comando composer dump-autoload -o

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

// Criar uma conexao de apenas leitura no banco de dados
if (!function_exists('query')) {
  function query(string $table): Builder
  {
    return tap(DB::table($table))
      ->useWritePdo();
  }
}
