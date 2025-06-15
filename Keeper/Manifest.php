<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Keeper;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  public static function _arise() {
    forger_fix([
      [
        'target'=> KEEPER_ECHOES,
        'type'=> 'repo'
      ],
      [
        'target'=> KEEPER_ECHOES_ARCANES,
        'type'=> 'repo'
      ],
      [
        'target'=> KEEPER_ECHOES_ARCANE,
        'type'=> 'item'
      ],
    ]);
  
    keeper_glitch_boot();
  }
  
  public static function _aether_awaken_after() {
    self::awaken();
  }
  
  public static function awaken() {
    
    $memory = aether_memoryusage();
    keeper_item('aether', [
      'FILE'=> AETHER_FILE,
      'REPO'=> AETHER_REPO,
      'VERSION'=> AETHER_VERSION,
      'SIZE'=> filesize(AETHER_FILE),
      'MEMORY'=> [$memory[0], $memory[1]],
      'RUNE'=> aether_arised(),
    ]);
    
    aether_arcane('Keeper.manifest.awaken');
    keeper_arcane_process();
  }


  public static function echo( String $repo, String $name, $value = '' ) {
  if (empty($value)) {
    $return = keeper_echo_get($repo, $name);
  }else {
    $return = keeper_echo_set($repo, $name, $value);
  }

  aether_arcane('Keeper.manifest.echo');
  return $return;
}
  
  public static function item( String $name, $value = false ) {
    if ($value === false) {
      $return = keeper_item_get($name);
    }else {
      $return = keeper_item_set($name, $value);
    }

    aether_arcane('Keeper.manifest.item');
    return $return;
  }



  // public static function artefact( Array $rune_crafter )
  // {
  //   $items = [];
  //   foreach ($rune_crafter['items'] as $row) {
  //     $row['source'] = cipher_base64($row['source']);
  //     $row['dirname'] = str_replace(AETHER_REPO, '', $row['dirname']);
  //     $row['target'] = str_replace(AETHER_REPO, '', $row['target']);
  //     $items[] = $row;
  //   }
  //   $rune_crafter['items'] = $items;
  //   $rune_crafter['maps']['repo'] = '/' . $rune_crafter['maps']['repo'];
    
  //   $name = str_replace('/', '-', $rune_crafter['maps']['repo']);
  //   $name = str_replace('\\', '-', $name);
  //   if (strpos($name, '-') === 0) {
  //     $name = substr($name, 1);
  //   }

  //   $target = AETHER_ECHOES_ARTEFACT . $name . '.rune';
  //   forger_folder(AETHER_ECHOES_ARTEFACT);
  //   forger_file($target);
  //   unset($rune_crafter['bases']['REPO']);

  //   $source = cipher_encode(cipher_base64(json_encode($rune_crafter)));
  //   forger_set($target, $source);
  //   whisper_nl("{{COLOR-INFO}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Keeper Rune '$name.rune' to '.echoes/artefacts/'");
  // }




}