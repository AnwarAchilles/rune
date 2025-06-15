<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Starter;

class Phantasm extends \Rune\Phantasm {

  public $version = 0.1;
  
  public $main = 'Starter';

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