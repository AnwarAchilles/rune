<?php

namespace Rune\Chanter;

class Phantasm {

  public $version = 0.1;
  
  public $main = 'Chanter';

  public $user = 'Anwar Achilles';

  public $note = 'to encapsulate and manage command-line interactions for invoking application logic via CLI.';

  public $need = [
    ['Aether', 'ether:essence:entity', 0.1],
    ['Whisper', 'entity', 0.1],
  ];

  public $list = [
    // manifest
    [
      'type'=> 'manifest',
      'call'=> 'run()',
      'note'=> '',
    ],
    [
      'type'=> 'manifest',
      'call'=> 'get( String $arg )',
      'note'=> '',
    ],
    [
      'type'=> 'manifest',
      'call'=> 'set( String $arg, Callable $callable )',
      'note'=> '',
    ],
    // ether
    [
      'type'=> 'ether',
      'call'=> 'CHANTER',
      'note'=> '',
    ],
    [
      'type'=> 'ether',
      'call'=> 'CHANTER_STRICT_1',
      'note'=> '',
    ],
    [
      'type'=> 'ether',
      'call'=> 'CHANTER_STRICT_2',
      'note'=> '',
    ],
    // essence
    [
      'type'=> 'essence',
      'call'=> '$CHANTER',
      'note'=> '',
    ],
    [
      'type'=> 'essence',
      'call'=> '$CHANTER_LIST',
      'note'=> '',
    ],
    [
      'type'=> 'essence',
      'call'=> '$CHANTER_ARGS',
      'note'=> '',
    ],
    [
      'type'=> 'essence',
      'call'=> '$CHANTER_ARG',
      'note'=> '',
    ],
    // entity
    [
      'type'=> 'entity',
      'call'=> 'chanter( String $newArg = "" )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_arg( String $newArg = "" )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_args()',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_option( string $name )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_option_clean( String $str )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_match( String $withArgs, String $strict = "true" )',
      'note'=> '',
    ],
  ];

  public function awakening() {}
  
}