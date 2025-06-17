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
  
  // if (aether_has_entity('keeper')) {
  //   $checkFamiliar = keeper_has('familiar.json');
  //   if ($checkFamiliar) {
  //     $checkFamiliar = '{{COLOR-SUCCESS}}{{ICON-SUCCESS}}ACTIVE';
  //   }else {
  //     $checkFamiliar = '{{COLOR-SECONDARY}}Not have spirit to interact, enchant the "familiar --spirit"';
  //   }
  // }else {
  //   $checkFamiliar = '{{COLOR-SECONDARY}}Not have Keeper try "Rune\Keeper\Manifest::arise()"';
  // }

  $header = Weaver::item(__DIR__ . '/weaver/rune-header.txt');
  $header = Weaver::bind($header, [
    'FILE'=> AETHER_FILE,
    'REPO'=> AETHER_REPO,
    'VERSION'=> AETHER_VERSION,
    'CAST'=> implode(PHP_EOL, $list_chanter),
    // 'FAMILIAR'=> $checkFamiliar,
    'FAMILIAR'=> 'NONE',
  ]);
  
  if (aether_has_entity('whisper')) {
    Whisper::clear()::emit($header);
  }else {
    aether_whisper($header);
  }
});