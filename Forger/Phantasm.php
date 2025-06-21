<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Forger;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = 1.5;
  
  public $main = 'Forger';

  public $user = 'Anwar Achilles';

  public $note = '?';

  public $need = [];

  public $list = [
    // manifest
    [
      'type' => 'manifest',
      'call' => '_arise()',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => '_aether_awaken()',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'awaken()',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'trace( String $source_path )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'scan( String $source_path, $callback )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'fix( Array $source_path )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'clone( string $from, string $to )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'repo( String $source_path, ?Callable $callback )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'item( String $source_path, String $content = "" )',
      'note'=> '',
    ],
    // ether
    [
      'type' => 'ether',
      'call' => 'FORGER',
      'note'=> '',
    ],
    // essence
    [
      'type' => 'entity',
      'call' => '$FORGER',
      'note'=> '',
    ],
    // entity
    [
      'type' => 'entity',
      'call' => 'forger( String $source_path )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_trace( String $source_path )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_scan( String $source_path, $callback )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_fix( Array $source_path )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_clone( string $from, string $to )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_repo( String $source_path, ?Callable $callback )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_item( String $source_path, String $content = "" )',
      'note'=> '',
    ],
  ];

  public function awakening() {}
  
}