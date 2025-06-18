<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Whisper\Manifest as Whisper;
use Rune\Weaver\Manifest as Weaver;

// base/default
Chanter::cast('rune', function() {
  global $CHANTER_ARGS;
  global $CHANTER_ECHO;
  
  foreach ($CHANTER_ECHO as $key => $echo) {
    $list_chanter[] = 'php ' . AETHER_FILE . ' ' . $echo[1];
  }

  $base_cast = [];
  for ($i=0; $i<4; $i++) {
    $base_cast[] = $list_chanter[$i];
    unset($list_chanter[$i]);
  }
  $base_cast = implode(PHP_EOL, $base_cast);
  $registered_cast = implode(PHP_EOL, $list_chanter);
  
  $base_cast = str_replace('php '.AETHER_FILE, '{{color-secondary}}php '.AETHER_FILE.'{{color-end}}', $base_cast);
  $registered_cast = str_replace('php '.AETHER_FILE, '{{color-secondary}}php '.AETHER_FILE.'{{color-end}}', $registered_cast);

  $header = Weaver::item(__DIR__ . '/weaver/rune-header.txt');
  $header = Weaver::bind($header, [
    'FILE'=> AETHER_FILE,
    'REPO'=> AETHER_REPO,
    'VERSION'=> AETHER_VERSION,
    'BASE-CAST'=> $base_cast,
    'REGISTERED-CAST'=> $registered_cast,
    // 'FAMILIAR'=> $checkFamiliar,
    'FAMILIAR'=> 'NONE',
    'TOTAL-RUNE'=> count(aether_arised()),
  ]);
  
  if (aether_has_entity('whisper')) {
    Whisper::clear()::emit($header);
  }else {
    aether_whisper($header);
  }
});