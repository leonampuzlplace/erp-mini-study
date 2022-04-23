<?php
// Como configurar arquivo helper? 
// Adicione o caminho em composer.json na seção autoload, files. 
// Depois rodar comando composer dump-autoload -o

use Carbon\Carbon;

// Extrair apenas números de uma string
if (!function_exists('onlyNumbers')) {
  function onlyNumbers(string $value): string
  {
    return preg_replace('/[^0-9]/', '', $value); 
  }
}

// Formatar Data
if (!function_exists('formatDate')) {
  function formatDate(string $value, string $format = 'Y-m-d H:i:s', bool $startOfDay = false, bool $endOfDay = false): string
  {
    $result = Carbon::parse($value);

    // Horário de início de dia
    if ($startOfDay) {
      $result = $result->startOfDay();
    }
    // Horário de final de dia
    if ($endOfDay) {
      $result = $result->endOfDay();
    }
    // Formatar
    $result = $result->format($format);
    return $result;
  }
}


// Validar CPF
if (!function_exists('cpfIsValid')) {
  function cpfIsValid(string $value): bool
  {
    $c = preg_replace('/\D/', '', $value);
    if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
      return false;
    }

    for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
    if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
      return false;
    }

    for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
    if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
      return false;
    }

    return true;
  }
}

// Validar CNPJ
if (!function_exists('cnpjIsValid')) {
  function cnpjIsValid(string $value): bool
  {
    $c = preg_replace('/\D/', '', $value);
    $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    if (strlen($c) != 14) {
        return false;
    } elseif (preg_match("/^{$c[0]}{14}$/", $c) > 0) {
      return false;
    }

    for ($i = 0, $n = 0; $i < 12; $n += $c[$i] * $b[++$i]);
    if ($c[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
      return false;
    }

    for ($i = 0, $n = 0; $i <= 12; $n += $c[$i] * $b[$i++]);
    if ($c[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
      return false;
    }

    return true;
  }
}

// Validar CPF ou CNPJ
if (!function_exists('cpfOrCnpjIsValid')) {
  function cpfOrCnpjIsValid(string $value): bool
  {
    return strlen(onlyNumbers($value)) === 11
      ? cpfIsValid($value)
      : cnpjIsValid($value);
  }
}

// Formatar CPF ou CNPJ
if (!function_exists('formatCpfCnpj')) {
  function formatCpfCnpj(string $value): string
  {
    return strlen(onlyNumbers($value)) === 11
      ? preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $value)
      : preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $value);
  }
}

// Remover chaves do array
if (!function_exists('array_except')) {
  function array_except(array $array, array $keys): array
  {
    foreach ($keys as $key) {
      unset($array[$key]);
    }
    return $array;
  }
}