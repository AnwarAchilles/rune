<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Forger;

class Phantasm {

  public $version = 0.1;
  
  public $main = 'Forger';

  public $mark = 'BETA';

  public $need = [];

  public $list = [
    [
      'type' => 'entity',
      'call' => 'forger_file( String $sourcepath, Int $permission = 0644 )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_folder( String $sourcepath, Int $permission = 0755 )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_route( String $sourcepath, Int $dirPermission = 0775, Int $filePermission = 0644 )',
      'note'=> '',
    ],
  ];

  public function awakening() {}
  
}