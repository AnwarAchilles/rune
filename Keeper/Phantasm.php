<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Keeper;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = 1.5;
  
  public $main = 'Keeper';

  public $user = 'Anwar Achilles';

  public $note = '?';

  public $need = [
    ['Aether', 'ether', 1.0], 
    ['Forger', 'entity', 1.0]
  ];

  public $list = [
    // manifest
    [
      'type' => 'manifest',
      'call' => '_arise()',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => '_aether_awaken_after()',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'awaken()',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'item( String $name, $value = "" )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'echo( String $repo, String $name, $value = "" )',
      'note'=> '',
    ],
    // ether
    [
      'type' => 'ether',
      'call' => 'KEEPER',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'KEEPER_ECHOES',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'KEEPER_ECHOES_KEEPER',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'KEEPER_ECHOES_STATS',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'KEEPER_ECHOES_ARCANE',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'KEEPER_ECHOES_GLITCH',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'KEEPER_ECHOES_ARCANES',
      'note'=> '',
    ],
    // essence
    [
      'type' => 'ether',
      'call' => '$KEEPER',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => '$KEEPER_ARCANE',
      'note'=> '',
    ],
    // entity
    [
      'type' => 'entity',
      'call' => 'keeper()',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'keeper_arcane_process()',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'keeper_arcane_process_store( $datas )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'keeper_item( String $name, $value = "" )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'keeper_item_set( String $name, $value )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'keeper_item_get( String $name )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'keeper_echo( String $repo, String $name, $value = "" )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'keeper_echo_set( String $repo, String $name, $value )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'keeper_echo_get( String $repo, String $name )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'keeper_glitch_boot()',
      'note'=> '',
    ],
  ];

  public function awakening() {}
  
}