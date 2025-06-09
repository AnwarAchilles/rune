<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Crafter;

class Phantasm {

  public $version = 0.1;
  
  public $main = 'Crafter';

  public $need = [
    ['Whisper', 'ether:essence:entity', 0.1],
    ['Weaver', 'ether:essence:entity', 0.1],
    ['Forger', 'ether:essence:entity', 0.1],
  ];

  public $list = [
    [
      'type' => 'manifest',
      'call' => 'set( String $source, Callable $callable )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'item( String $source )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'base( String $ID, $value )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'run( String $source, Callable $injection = null )',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_BASE',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_WEAVER',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_VARIABLE',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_CLEANING',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_BASE',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_APPS',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_MAPS',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_ITEMS',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_CLUSTERS',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_DIST',
      'note'=> '',
    ],
  ];

  public function awakening() {}
  
}