<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */

function chanter() {
  return true;
}

function chanter_arg( String $newArg = '' ) {
  aether_arcane("Aether.entity.chanter_arg", true);
  global $CHANTER_ARG;
  if ($newArg !== '') {
    $CHANTER_ARG = $newArg;
  }
  return $CHANTER_ARG;
  aether_arcane("Aether.entity.chanter_arg: " . $newArg, true);
}

function chanter_args() {
  global $CHANTER_ARGS;
  return $CHANTER_ARGS;
}

function chanter_cast( String $arg ) {
  $arg = weaver_bind($arg, 'self', 'php ' . AETHER_FILE);
  return shell_exec($arg);
}

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
    if (
      defined('AETHER_FILE') &&
      substr($value, 0, strlen(AETHER_FILE)) === AETHER_FILE &&
      strlen($value) === strlen(AETHER_FILE)
    ) {
      $value = '';
    }

    return $value;
  }

  return true;
}

function chanter_option_clean(string $str) {
  $arg = preg_replace('/--\w+(=[^\s]+)?\s*/', '', $str);
  return trim($arg);
}
