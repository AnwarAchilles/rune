<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Aether;

class Phantasm {

  public $version = 0.1;
  
  public $main = 'Aether';

  public $user = 'Anwar Achilles';

  public $note = 'to perform the initial infrastructure setup required to build other infrastructures.';

  public $need = [
    ['Chanter', 'ether:essence:entity', 0.1],
    ['Whisper', 'ether:essence:entity', 0.1],
    ['Weaver', 'ether:essence:entity', 0.1],
    ['Forger', 'entity', 0.1],
  ];

  public $list = [
    [
      'type' => 'manifest',
      'call' => 'run()',
      'note'=> 'Todo call the built in rune.',
    ]
  ];

  public function awakening() {}
  
}