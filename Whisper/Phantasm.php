<?php

namespace Rune\Whisper;

class Phantasm {

  public $version = 0.1;
  
  public $main = 'Whisper';

  public $user = 'Anwar Achilles';

  public $note = 'to provide output to the command line interface (CLI) along with creator text color, icon, and label.';

  public $need = [
    ['Weaver', 'entity', 0.1],
  ];
  
  public $list = [
    // manifest
    // ether
    [
      'type'=> 'ether',
      'call'=> 'WHISPER',
      'note'=> '',
    ],
    // essence
    [
      'type'=> 'essence',
      'call'=> '$WHISPER',
      'note'=> '',
    ],
    // entity
    [
      'type'=> 'entity',
      'call'=> 'whisper( String $message, $inLine=false )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'whisper_nl( String $message )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'whisper_il( String $message )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'whisper_delay( Int $ms )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'whisper_clear()',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'whisper_input( String $prompt )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'whisper_var_search( String $value )',
      'note'=> '',
    ],
  ];

  public function awakening() {}
  
}