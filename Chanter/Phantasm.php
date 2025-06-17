<?php

namespace Rune\Chanter;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = 1.1;
  
  public $main = 'Chanter';

  public $user = 'Anwar Achilles';

  public $note = 'to encapsulate and manage command-line interactions for invoking application logic via CLI.';

  public $need = [
    ['Aether', 'ether:essence:entity', 1.4],
    ['Whisper', 'entity', 0.1],
  ];

  public $list = [
    
    // manifest
    [
      'type'=> 'manifest',
      'call'=> 'awaken()',
      'note'=> 'Prepares and executes a spell casting based on provided arguments or from file.',
    ],
    [
      'type'=> 'manifest',
      'call'=> 'cast()',
      'note'=> 'Gets or sets a spell cast definition based on input and returns the result.',
    ],
    [
      'type'=> 'manifest',
      'call'=> 'spell()',
      'note'=> 'Gets or sets a spell definition by name and returns the result.',
    ],
    [
      'type'=> 'manifest',
      'call'=> 'echo()',
      'note'=> 'Outputs the given text and returns it.',
    ],
    // ether
    [
      'type'=> 'ether',
      'call'=> 'CHANTER',
      'note'=> 'Main ether',
    ],
    // essence
    [
      'type'=> 'essence',
      'call'=> '$CHANTER',
      'note'=> 'Main essence',
    ],
    [
      'type'=> 'essence',
      'call'=> '$CHANTER_ARG',
      'note'=> 'Stores full CLI argument string as a single line',
    ],
    [
      'type'=> 'essence',
      'call'=> '$CHANTER_ARG_CAST',
      'note'=> 'Holds parsed cast-related arguments from CLI',
    ],
    [
      'type'=> 'essence',
      'call'=> '$CHANTER_ARG_SPELL',
      'note'=> 'Contains full list of separated CLI arguments',
    ],
    [
      'type'=> 'essence',
      'call'=> '$CHANTER_CAST',
      'note'=> 'Stores all available cast definitions',
    ],
    [
      'type'=> 'essence',
      'call'=> '$CHANTER_CAST_LIST',
      'note'=> 'List of registered cast names or keys',
    ],
    [
      'type'=> 'essence',
      'call'=> '$CHANTER_SPELL',
      'note'=> 'Stores all defined spells with their values',
    ],
    [
      'type'=> 'essence',
      'call'=> '$CHANTER_ECHO',
      'note'=> 'Holds text or data to be echoed after cast execution',
    ],
    // entity
    [
      'type'=> 'entity',
      'call'=> 'chanter()',
      'note'=> 'Main entity',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_arg()',
      'note'=> 'Sets or returns the current CLI argument string.',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_arg_extract()',
      'note'=> 'Parses CLI arguments into cast parts and spell options.',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_cast()',
      'note'=> 'Gets or registers a cast function based on the given arguments.',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_cast_set()',
      'note'=> 'Registers a new cast if it doesn’t already exist in the cast list.',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_cast_get()',
      'note'=> 'Returns a registered cast function or a fallback if not found.',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_cast_has()',
      'note'=> 'Checks if a cast function exists for the given arguments.',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_spell()',
      'note'=> 'Gets or sets a spell by name depending on whether values are provided.',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_spell_set()',
      'note'=> 'Registers or updates a spell with the given name and value.',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_spell_get()',
      'note'=> 'Retrieves the value of a spell argument if it exists.',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_spell_chain()',
      'note'=> 'Builds a full CLI-style spell string from all current spell arguments.',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_echo()',
      'note'=> 'Saves an echo message for the current cast if it\'s valid.',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_echo_set()',
      'note'=> 'Adds notes to the echo data if the entry doesn\'t exist yet.',
    ],
    [
      'type'=> 'entity',
      'call'=> 'chanter_echo_get()',
      'note'=> 'Retrieves the echo data for a given cast argument.',
    ],
  ];

  public function awakening() {}
  
}