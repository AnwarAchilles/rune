<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Aether;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = 1.6;
  
  public $main = 'Aether';

  public $user = 'Anwar Achilles';

  public $note = 'to perform the initial infrastructure setup required to build other infrastructures.';

  public $need = [
    ['Chanter', 'ether:essence:entity', 1.0],
    ['Whisper', 'ether:essence:entity', 0.1],
    ['Weaver', 'ether:essence:entity', 0.1],
    ['Forger', 'entity', 0.1],
  ];

  public $list = [
    [
      'type' => 'manifest',
      'call' => 'origin()',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'awaken()',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'awakening()',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'localhost()',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_FILE',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_REPO',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_VERSION',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_COPYRIGHT',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_RUNE_LOCATION',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_ECHOES',
      'note'=> '',
    ],
    // essence
    [
      'type' => 'essence',
      'call' => '$AETHER_STOPWATCH',
      'note'=> '',
    ],
    // entity
    [
      'type' => 'entity',
      'call' => 'aether_stopwatch()',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_memoryusage()',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_has_ether()',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_has_essence()',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_has_entity()',
      'note'=> '',
    ],
  ];

  public function awakening() {}
  
}