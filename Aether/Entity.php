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
  print(PHP_EOL.'AETHER DUBGGING :: START'.PHP_EOL);
  var_dump($data); 
  print(PHP_EOL.'AETHER DUBGGING :: END in '.number_format(aether_stopwatch(), 4).'ms'.PHP_EOL);
  die;
}







/* PHANTASM OF WHISPER */
function aether_whisper( $text ) {
  // if (aether_has_entity('whisper')) {
  //   whisper_emit($text);
  // }else {
  // }
  print(preg_replace('/\{\{.*?\}\}/', '', $text).PHP_EOL);
}
function aether_whisper_emit( $text ) {
  if (aether_has_entity('whisper')) {
    whisper_emit($text);
  }else {
    print(preg_replace('/\{\{.*?\}\}/', '', $text).PHP_EOL);
  }

  aether_arcane("Aether.entity.aether_whisper_emit");
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
  global $AETHER_ARCANE;
  global $AETHER_STOPWATCH;

  $process = function() use (&$AETHER_ARCANE, &$AETHER_STOPWATCH, &$text, &$value) {
    $now = microtime(true);
    
    $global_stopwatch = $now - $AETHER_STOPWATCH;
  
    $AETHER_ARCANE[] = [
      time(),
      $global_stopwatch,
      $text,
      $value
    ];
  };

  if (isset($AETHER_ARCANE[2])) {
    if ($text !== end($AETHER_ARCANE)[2]) {
      $lastkey = array_key_last($AETHER_ARCANE);
      $process();
    }
  }else {
    $process();
  }
}

function aether_arcane_pretty_print() {
  global $AETHER_ARCANE;

  print(PHP_EOL."ARCANE".PHP_EOL);
  $datas = '';
  foreach ($AETHER_ARCANE as $item) {
    $datetime = date('Y-m-d H:i:s', $item[0]);
    $stopwatch = number_format($item[1], 4);
    $response = $item[2];
    $value = (isset($item[3])) ? $item[3] : '';
    
    if (aether_has_entity('whisper')) {
      if (strpos($item[2], 'manifest') !== false) {
        $state = '{{color-danger}}';
      }else if (strpos($item[2], 'entity') !== false) {
        $state = '{{color-info}}';
      }else {
        $state = '{{color-default}}';
      }
      // whisper_emit("{{color-secondary}}[$datetime] [$stopwatch] {$state}{$response} {{nl}}");
      $datas .= "{{color-secondary}}[$datetime] [$stopwatch] {$state}{$response}: $value {{nl}}";
    }else {
      // print("[$datetime] [$stopwatch] $response" . PHP_EOL);
      $datas .= "[$datetime] [$stopwatch] {$response}: $value " . PHP_EOL;
    }
  }
  if (aether_has_entity('whisper')) {
    whisper_emit($datas);
  }else {
    print($datas);
  }
}
