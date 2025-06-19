<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Crafter;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = 1.1;
  
  public $main = 'Crafter';

  public $need = [
    ['Aether', 'ether:essence:entity', 1.0],
    ['Whisper', 'ether:essence:entity', 0.1],
    ['Weaver', 'ether:essence:entity', 0.1],
    ['Forger', 'ether:essence:entity', 0.1],
  ];

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
      'call' => 'item( String $name, Callable $callable = NULL )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'seed( String $name, Mixed $value = NULL )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'shard( String $source, Callable $injection = NULL )',
      'note'=> '',
    ],
    [
      'type' => 'manifest',
      'call' => 'spark( Mixed $name_or_callable = NULL, Callable $injection = NULL )',
      'note'=> '',
    ],
    // ether
    [
      'type' => 'ether',
      'call' => 'CRAFTER',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_WEAVER',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_VARIABLE',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_CLEANING',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_RESET_SEED',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_RESET_SPARK',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_RESET_SPARK_STATE',
      'note'=> '',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_RESET_SPARK_CLUSTER',
      'note'=> '',
    ],
    // essence
    [
      'type' => 'essence',
      'call' => '$CRAFTER',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_ITEM',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_SEED',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_SHARD',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_SHARD_LIST',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_SHARD_INJECTION',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_SPARK_STATE',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_SPARK_CLUSTER',
      'note'=> '',
    ],
    [
      'type' => 'essence',
      'call' => '$CRAFTER_SPARK_DISTRIBUTE',
      'note'=> '',
    ],
    // entity
    [
      'type' => 'entity',
      'call' => 'crafter()',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_seed_set( String $name, Mixed $value )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_seed_get( String $name )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_item_set( String $name, Callable $callable )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_item_get( String $name )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_shard_set( String $file_path, Callable $injection = NULL )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_shard_get( String $file_path, Callable $injection = NULL )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark( String $name, Callable $injection = NULL )',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_reset()',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_clustering()',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_cleaning()',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_bundling()',
      'note'=> '',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_distributing()',
      'note'=> '',
    ],
  ];

  public function awakening() {}
  
}