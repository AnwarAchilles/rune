<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 *
 * Example:
 * function starter() {}
 */



function aether_stopwatch() {
  global $AETHER_STOPWATCH;
  $text = number_format(microtime(true) - $AETHER_STOPWATCH, 6) . 's';
  aether_log("Aether.entity.aether_stopwatch: " . $text);
  return $text;
}

function aether_memoryusage() {
  $text = aether_formatFileSize(memory_get_usage()) . ', with peak is ' . aether_formatFileSize(memory_get_peak_usage());
  aether_log("Aether.entity.aether_memoryusage: " . $text);
  return $text;
}

function aether_formatFileSize($size, $precision = 2) {
  $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
  $i = 0;
  while ($size >= 1024) {
      $size /= 1024;
      $i++;
  }
  return round($size, $precision) . '' . $units[min($i, count($units) - 1)];
}

function aether_has_entity( $function ) {
  return (function_exists($function)) ? true : false;
}
function aether_has_ether( $rune ) {
  return (defined($rune)) ? true : false;
}
function aether_has_essence( $rune ) {
  return (isset($GLOBALS[$rune])) ? true : false;
}


function aether_whisper( $text ) {
  print(preg_replace('/\{\{.*?\}\}/', '', $text));
}


function aether_log( String $text ) {
  if (aether_has_entity('forger')) {
    forger_folder(AETHER_REPO . '/.echoes');
    forger_folder(AETHER_LOGS);
    $text = '[' . date('Y-m-d H:i:s') . '] ' . $text;
    $last = (forger_file(AETHER_LOGS.date('Y-m-d').'.txt')) ?: '';
    $data = $last . PHP_EOL . $text;
    $data = str_replace(PHP_EOL.PHP_EOL, PHP_EOL, $data);
    forger_set(AETHER_LOGS.date('Y-m-d').'.txt', $data);
  }
}

function aether_log_clear() {
  if (aether_has_entity('forger')) {
    forger_folder(AETHER_REPO . '/.echoes');
    forger_folder(AETHER_LOGS);
    forger_set(AETHER_LOGS.date('Y-m-d').'.txt', '');
  }
}

function aether_dd($data) { 
  print_r($data); 
  die;
}






/* ARCANE
 * need: Forger, Keeper
 *  */
function aether_arcane( String $text ) {
  if (aether_has_entity('forger')) {
    forger_folder(AETHER_REPO . '/.echoes');
    forger_folder(AETHER_LOGS);
    $text = '[' . date('Y-m-d H:i:s') . '] ' . $text;
    $last = (forger_file(AETHER_LOGS.date('Y-m-d').'.txt')) ?: '';
    $data = $last . PHP_EOL . $text;
    $data = str_replace(PHP_EOL.PHP_EOL, PHP_EOL, $data);
    forger_set(AETHER_LOGS.date('Y-m-d').'.txt', $data);
  }
}
