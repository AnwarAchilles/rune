<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */

function aether_formatFileSize($size, $precision = 2) {
  if ($size <= 0) return '0 B';

  static $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  $base = floor(log($size, 1024));
  $base = min($base, count($units) - 1); // Biar nggak keluar dari array

  $scaledSize = $size / pow(1024, $base);
  aether_arcane('Aether.entity.aether_formatFileSize');
  return sprintf("%.{$precision}f %s", $scaledSize, $units[$base]);
}

function aether_stopwatch() {
  global $AETHER_STOPWATCH;
  $output = microtime(true) - $AETHER_STOPWATCH;
  aether_arcane('Aether.entity.aether_stopwatch');
  return $output;
}


function aether_memoryusage() {
  $output = [memory_get_usage(), memory_get_peak_usage()];
  aether_arcane("Aether.entity.aether_memoryusage");
  return $output;
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





function aether_log( String $text ) {
  // if (aether_has_entity('forger')) {
  //   forger_folder(AETHER_REPO . '/.echoes');
  //   forger_folder(AETHER_LOGS);
  //   aether_arcane($text);
  //   $text = '[' . date('Y-m-d H:i:s') . '] ' . $text;
  //   $last = (forger_file(AETHER_LOGS.date('Y-m-d').'.txt')) ?: '';
  //   $data = $last . PHP_EOL . $text;
  //   $data = str_replace(PHP_EOL.PHP_EOL, PHP_EOL, $data);
  //   forger_set(AETHER_LOGS.date('Y-m-d').'.txt', $data);
  // }
}

function aether_log_clear() {
  // if (aether_has_entity('forger')) {
  //   forger_folder(AETHER_REPO . '/.echoes');
  //   forger_folder(AETHER_LOGS);
  //   forger_set(AETHER_LOGS.date('Y-m-d').'.txt', '');
  // }
}

function aether_dd($data) { 
  print_r($data); 
  die;
}







/* PHANTASM OF WHISPER */
function aether_whisper( $text ) {
  if (aether_has_entity('whisper')) {
    whisper_nl($text);
  }else {
    print(preg_replace('/\{\{.*?\}\}/', '', $text).PHP_EOL);
  }
}
function aether_whisper_nl( $text ) {
  if (aether_has_entity('whisper')) {
    whisper_nl($text);
  }else {
    print(preg_replace('/\{\{.*?\}\}/', '', $text).PHP_EOL);
  }

  aether_arcane("Aether.entity.aether_whisper_nl");
}


function aether_arised() {
  global $AETHER_RUNE_ETHER;
  global $AETHER_RUNE_ESSENCE;
  global $AETHER_RUNE_ENTITY;

  $return = array_merge(
    $AETHER_RUNE_ETHER,
    $AETHER_RUNE_ESSENCE,
    $AETHER_RUNE_ENTITY
  );
  $return = array_unique($return);

  return $return;
}


/* ARCANE
 * need: Forger, Keeper
 *  */
function aether_arcane(String $text, String $value = NULL) {
  global $AETHER_ARCANE_ITEMS;
  global $AETHER_STOPWATCH;

  $now = microtime(true);

  $global_stopwatch = $now - $AETHER_STOPWATCH;

  $AETHER_ARCANE_ITEMS[] = [
    time(),
    $global_stopwatch,
    $text,
    $value
  ];
}

function aether_arcane_pretty_print() {
  global $AETHER_ARCANE_ITEMS;

  whisper_nl("");
  whisper_nl("{{COLOR-DANGER}} AETHER ARCANE");

  $datas = '';
  foreach ($AETHER_ARCANE_ITEMS as $item) {
    $datetime = date('Y-m-d H:i:s', $item[0]);
    $stopwatch = number_format($item[1], 4);
    $response = $item[2];
    whisper_il("{{COLOR-SECONDARY}} [$datetime] ");
    whisper_il("{{COLOR-DEFAULT}} [$stopwatch] ");

    if (strpos($response,'arise') !== false) {
      whisper_il("{{COLOR-PRIMARY}} $response ");
    }else if (strpos($response,'manifest') !== false) {
      whisper_il("{{COLOR-DANGER}} $response ");
    }else if (strpos($response,'entity') !== false) {
      whisper_il("{{COLOR-INFO}} $response ");
    }else {
      whisper_il("{{COLOR-DEFAULT}} $response ");
    }
    
    whisper_nl("");
  }
}
