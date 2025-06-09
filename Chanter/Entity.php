<?php

function chanter() {}

function chanter_arg( String $newArg = '' ) {
  global $CHANTER_ARG;
  if ($newArg !== '') {
    $CHANTER_ARG = $newArg;
  }
  return $CHANTER_ARG;
}

function chanter_args() {
  global $CHANTER_ARGS;
  return $CHANTER_ARGS;
}

function chanter_cast( String $arg ) {
  $arg = weaver_bind($arg, 'self', 'php ' . AETHER_FILE);
  return shell_exec($arg);
}

// function chanter_option( string $name ) {
//   global $CHANTER_ARG; // Ini udah string ya, bukan array
//   $search = '--' . $name;
//   // Cari posisi nama opsinya
//   $pos = strpos($CHANTER_ARG, $search);
//   if ($pos === false) {
//     return false;
//   }
//   // Cek apakah setelah nama ada '='
//   $after = substr($CHANTER_ARG, $pos + strlen($search));
//   if (isset($after[0]) && $after[0] === '=') {
//     // Ambil nilai setelah '='
//     $valueStart = $pos + strlen($search) + 1;
//     $valueEnd = strpos($CHANTER_ARG, ' ', $valueStart);
//     if ($valueEnd === false) {
//       return substr($CHANTER_ARG, $valueStart);
//     } else {
//       return substr($CHANTER_ARG, $valueStart, $valueEnd - $valueStart);
//     }
//   }
//   // Kalau nggak ada '=', berarti cuma flag doang
//   return true;
// }
function chanter_option(string $name) {
  global $CHANTER_ARG_REAL;
  $search = '--' . $name;

  $pos = strpos($CHANTER_ARG_REAL, $search);
  if ($pos === false) {
    return false;
  }

  $nextCharPos = $pos + strlen($search);
  $nextChar = $CHANTER_ARG_REAL[$nextCharPos] ?? '';

  if ($nextChar !== '' && $nextChar !== '=' && $nextChar !== ' ') {
    return false;
  }

  if ($nextChar === '=') {
    $valueStart = $nextCharPos + 1;
    $valueEnd = strpos($CHANTER_ARG_REAL, ' ', $valueStart);
    $value = $valueEnd === false
      ? substr($CHANTER_ARG_REAL, $valueStart)
      : substr($CHANTER_ARG_REAL, $valueStart, $valueEnd - $valueStart);

    // ✨ Hapus AETHER_FILE dari awal hanya jika persis sama
    if (
      defined('AETHER_FILE') &&
      substr($value, 0, strlen(AETHER_FILE)) === AETHER_FILE &&
      strlen($value) === strlen(AETHER_FILE) // Harus sama panjang!
    ) {
      $value = ''; // bersihkan total
    }

    return $value;
  }

  return true;
}








function chanter_option_clean(string $str) {
  // Hapus semua --option[=value] di mana value bisa pakai karakter non-spasi
  $arg = preg_replace('/--\w+(=[^\s]+)?\s*/', '', $str);
  return trim($arg);
}
