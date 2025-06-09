<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Minister;

class Phantasm {

  public $version = 0.1;
  
  public $main = 'Minister';

  public $mark = 'TEMPLATE/EXAMPLE';

  public $need = [];

  public $list = [
    [
      'type' => 'manifest | entity | essence | ether',
      'call' => 'starter( $x )',
      'note'=> '',
    ]
  ];

  public function awakening() {}
  
}