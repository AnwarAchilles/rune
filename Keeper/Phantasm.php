<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Keeper;

class Phantasm {

  public $version = 0.1;
  
  public $main = 'Keeper';

  public $mark = 'BETA';

  public $need = [
    ['Aether', 'ether', 0.1], 
    ['Forger', 'entity', 0.1]
  ];

  public $list = [
    [
      'type' => 'manifest | entity | essence | ether',
      'call' => 'starter( $x )',
      'note'=> '',
    ]
  ];

  public function awakening() {}
  
}