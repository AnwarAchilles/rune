<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Cipher;

class Phantasm extends \Rune\Phantasm {

  public $version = 0.1;
  
  public $main = 'Cipher';

  public $mark = 'BETA';

  public $need = [];

  public $list = [
    [
      'type' => 'ether',
      'call' => 'CIPHER_VARIANT',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_id( String $prefix = "", $entropy = false )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_hash( String $text )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_encode( String $text, String $variant = "default" )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'decode( String $text, String $variant = "default")',
      'note'=> '',
    ],
  ];

  public function awakening() {}
  
}