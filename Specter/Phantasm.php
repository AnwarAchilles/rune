<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Specter;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = 0.1;
  
  public $main = 'Specter';

  public $mark = 'BETA';

  public $need = [];

  public $list = [
    [
      'type' => 'manifest',
      'call' => 'observer( $x )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'open( $x )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'seer( $x )',
      'note'=> '',
    ],
  ];

  public function awakening() {}
  
}