<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Keeper;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  // create next static method

  public static function _arise() {
    self::phantasm();

  }
  
  public static function run() {}


  public static function artefact( Array $rune_crafter )
  {
    $items = [];
    foreach ($rune_crafter['items'] as $row) {
      $row['source'] = cipher_base64($row['source']);
      $row['dirname'] = str_replace(AETHER_REPO, '', $row['dirname']);
      $row['target'] = str_replace(AETHER_REPO, '', $row['target']);
      $items[] = $row;
    }
    $rune_crafter['items'] = $items;
    $rune_crafter['maps']['repo'] = '/' . $rune_crafter['maps']['repo'];
    
    $name = str_replace('/', '-', $rune_crafter['maps']['repo']);
    $name = str_replace('\\', '-', $name);
    if (strpos($name, '-') === 0) {
      $name = substr($name, 1);
    }

    $target = AETHER_ECHOES_ARTEFACT . $name . '.rune';
    forger_folder(AETHER_ECHOES_ARTEFACT);
    forger_file($target);
    unset($rune_crafter['bases']['REPO']);

    $source = cipher_encode(cipher_base64(json_encode($rune_crafter)));
    forger_set($target, $source);
    whisper_nl("{{COLOR-INFO}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Keeper Rune '$name.rune' to '.echoes/artefacts/'");
  }




}