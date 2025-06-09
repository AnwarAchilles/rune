<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;

// base/default
Chanter::set('base', function() {
  global $CHANTER_ARGS;
  global $CHANTER_REGISTERED;
  
  foreach ($CHANTER_REGISTERED as $key => $value) {
    $CHANTER_REGISTERED[$key] = 'php ' . $CHANTER_ARGS[0] . ' ' . $value;
  }
  
  if (aether_has_entity('keeper')) {
    $checkFamiliar = keeper_has('familiar.json');
    if ($checkFamiliar) {
      $checkFamiliar = '{{COLOR-SUCCESS}}{{ICON-SUCCESS}}ACTIVE';
    }else {
      $checkFamiliar = '{{COLOR-SECONDARY}}Not have spirit to interact, enchant the "familiar --spirit"';
    }
  }else {
    $checkFamiliar = '{{COLOR-SECONDARY}}Not have Keeper try "Rune\Keeper\Manifest::arise()"';
  }

  $header = Weaver::load(__DIR__ . '/weaver/base-header.txt');
  $header = Weaver::bindAll($header, [
    'FILE'=> AETHER_FILE,
    'REPO'=> AETHER_REPO,
    'VERSION'=> AETHER_VERSION,
    'REGISTERED-CLI'=> implode(PHP_EOL, $CHANTER_REGISTERED),
    'FAMILIAR'=> $checkFamiliar,
  ]);
  
  if (aether_has_entity('whisper')) {
    whisper_clear();
    whisper_nl($header);
  }else {
    aether_whisper($header);
  }
});