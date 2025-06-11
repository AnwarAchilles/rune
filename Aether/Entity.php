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
  return sprintf("%.{$precision}f %s", $scaledSize, $units[$base]);
}

function aether_stopwatch() {
  aether_arcane('Aether.entity.aether_stopwatch', true);
  global $AETHER_STOPWATCH;
  $output = microtime(true) - $AETHER_STOPWATCH;
  aether_arcane('Aether.entity.aether_stopwatch: ' . $output, false);
  return $output;
}


function aether_memoryusage() {
  aether_arcane("Aether.entity.aether_memoryusage", true);
  // $text = aether_formatFileSize(memory_get_usage()) . ', with peak is ' . aether_formatFileSize(memory_get_peak_usage());
  $output = [memory_get_usage(), memory_get_peak_usage()];
  aether_arcane("Aether.entity.aether_memoryusage: [$output[0], $output[1]]", false);
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
  aether_arcane("Aether.entity.aether_whisper: " . $text);
}
function aether_whisper_nl( $text ) {
  aether_arcane("Aether.entity.aether_whisper_nl: " . $text, true);
  if (aether_has_entity('whisper')) {
    whisper_nl($text);
  }else {
    print(preg_replace('/\{\{.*?\}\}/', '', $text).PHP_EOL);
  }
  aether_arcane("Aether.entity.aether_whisper_nl: " . $text, false);
}



/* ARCANE
 * need: Forger, Keeper
 *  */
function aether_arcane(String $text, $state = NULL) {
  if (is_bool($state)) {
    if ($state) {
      aether_arcane_input($text);
    } else {
      aether_arcane_output($text);
    }
  } else {
    aether_arcane_process($text);
  }
}

function aether_arcane_process(String $text = NULL) {
  global $AETHER_ARCANE_ITEMS;
  global $AETHER_ARCANE_STOPWATCH;
  global $AETHER_ARCANE_STOPWATCH_STEP;

  $now = microtime(true);
  $datetime = date('Y-m-d H:i:s');

  $global_stopwatch = $now - $AETHER_ARCANE_STOPWATCH;
  $step_stopwatch = $now - $AETHER_ARCANE_STOPWATCH_STEP;

  $AETHER_ARCANE_ITEMS[] = [
    $datetime,
    number_format($global_stopwatch, 4),
    number_format($step_stopwatch, 5),
    $text,
    'S'
  ];
}

function aether_arcane_input(String $text) {
  global $AETHER_ARCANE_ITEMS;
  global $AETHER_ARCANE_STOPWATCH;
  global $AETHER_ARCANE_STOPWATCH_STEP;

  $now = microtime(true);
  $datetime = date('Y-m-d H:i:s');

  // Set stopwatch awal hanya jika belum pernah ada
  if (!isset($AETHER_ARCANE_STOPWATCH)) {
    $AETHER_ARCANE_STOPWATCH = $now;
    $AETHER_ARCANE_STOPWATCH_STEP = $now;
  }

  $global_stopwatch = $now - $AETHER_ARCANE_STOPWATCH;

  $AETHER_ARCANE_ITEMS[] = [
    $datetime,
    number_format($global_stopwatch, 4),
    '0.00000',
    $text,
    'I'
  ];

  // Tidak update STEP di sini, biarkan output yang update step terakhir
}

function aether_arcane_output(String $text) {
  global $AETHER_ARCANE_ITEMS;
  global $AETHER_ARCANE_STATE;
  global $AETHER_ARCANE_STOPWATCH;
  global $AETHER_ARCANE_STOPWATCH_STEP;

  $now = microtime(true);
  $datetime = date('Y-m-d H:i:s');

  $global_stopwatch = $now - $AETHER_ARCANE_STOPWATCH;
  $step_stopwatch = $now - $AETHER_ARCANE_STOPWATCH_STEP;

  $state = 'UNKNOWN';
  if (isset($AETHER_ARCANE_STATE) && is_array($AETHER_ARCANE_STATE)) {
    foreach ($AETHER_ARCANE_STATE as $row) {
      if ($step_stopwatch <= $row[0]) {
        $state = $row[1];
        break;
      }
    }
  }

  $AETHER_ARCANE_ITEMS[] = [
    $datetime,
    number_format($global_stopwatch, 4),
    number_format($step_stopwatch, 5),
    $text,
    'O',
    $state
  ];

  // STEP baru diset di output, biar step selalu dari output sebelumnya
  $AETHER_ARCANE_STOPWATCH_STEP = $now;
}

function aether_arcane_end() {
  global $AETHER_ARCANE_ITEMS;

  if (aether_has_entity('forger')) {
    forger_folder_all(AETHER_ECHOES_RUNE);
    forger_folder(AETHER_ECHOES_ARCANE);
    $datas = '';
    foreach ($AETHER_ARCANE_ITEMS as $item) {
      $datas .= $item . PHP_EOL;
    }
    forger_set(AETHER_ECHOES_ARCANE, $datas);
  }
}

function aether_arcane_pretty_print() {
  global $AETHER_ARCANE_ITEMS;

  whisper_nl("");
  whisper_nl("{{COLOR-DANGER}} AETHER ARCANE");
  whisper_nl("{{COLOR-PRIMARY}} [DATETIME] [TOTAL] [STEP] [STATE] INFO");

  $datas = '';
  foreach ($AETHER_ARCANE_ITEMS as $item) {
    whisper_il("{{COLOR-SECONDARY}} [$item[0]] ");
    whisper_il("{{COLOR-DEFAULT}} [$item[1]] ");

    if ($item[4]=='I') {
      whisper_il("{{COLOR-DANGER}} [$item[2]] ");
    }else if ($item[4]=='O') {
      whisper_il("{{COLOR-SUCCESS}} [$item[2]] ");
    }else {
      whisper_il("{{COLOR-SECONDARY}} [$item[2]] ");
    }

    switch ($item[4]) {
      case 'I':
        $state = '{{COLOR-DANGER}}>> ';
        break;
      case 'O':
        $state = '{{COLOR-SUCCESS}}<< ';
        break;
      default:
        $state = '{{COLOR-SECONDARY}}-- ';
        break;
    }
    whisper_il($state);

    $text = $item[3];
    $text = str_replace(': ', ': {{COLOR-INFO}}', $text);
    whisper_il($text);
    
    $state = (isset($item[5])) ? whisper_il("{{COLOR-DANGER}} //$item[5] ") : '';
    
    whisper_nl("");
  }
}

function aether_arcane_clear() {
  global $AETHER_ARCANE_ITEMS;
  $stored = $AETHER_ARCANE_ITEMS;
  $AETHER_ARCANE_ITEMS = [];

  if (aether_has_entity('keeper'));
    // do keeper arcane

  // if (aether_has_entity('forger')) {
  //   forger_folder_all(AETHER_ECHOES_RUNE);
  //   forger_set(AETHER_ECHOES_ARCANE, '');
  // }
}

function aether_arcane_cache() {
  if (aether_has_entity('forger')) {
    $last = (forger_get(AETHER_ECHOES_ARCANES)) ?: '';
    $text = (forger_get(AETHER_ECHOES_ARCANE)) ?: '';
    $data = $last . PHP_EOL . $text;
    $data = str_replace(PHP_EOL.PHP_EOL, PHP_EOL, trim($data));
    forger_set(AETHER_ECHOES_ARCANES, $data . PHP_EOL);
  }
}
