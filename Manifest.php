<?php

namespace Rune;

class Manifest {

  protected static $origin = __DIR__;
  protected static $mana = 0;

  public static $manifest = [];
  public static $phantasm = [];

  public static $hasEther = false;
  public static $hasEntity = false;
  public static $hasEssence = false;
  public static $hasPhantasm = false;
  

  // intialize
  public static function arise() {
    global $AETHER_ARISED;
    global $AETHER_PHANTASM;

    
    self::ether();
    self::essence();
    self::entity();

    $AETHER_ARISED[static::class] = [
      'ether'=> true,
      'entity'=> true,
      'essence'=> true
    ];
    
    if (method_exists(static::class, '_arise')) {
      static::_arise();
    }

    unset($AETHER_PHANTASM[static::class]);
    
    aether_arcane('Manifest.arise: ' . static::class, false);
  }

  // load entity
  public static function entity() {
    global $AETHER_ARISED;

    if (!self::$hasEntity) {
      self::$hasEntity = true;
    }
    require_once static::$origin . "/Entity.php";

    $AETHER_ARISED[static::class]['entity'] = true;
  }

  // load essence
  public static function essence() {
    global $AETHER_ARISED;

    if (!self::$hasEssence) {
      self::$hasEssence = true;
    }
    require_once static::$origin . "/Essence.php";

    $AETHER_ARISED[static::class]['essence'] = true;
  }

  // load ether
  public static function ether() {
    global $AETHER_ARISED;

    if (!self::$hasEther) {
      self::$hasEther = true;
    }
    require_once static::$origin . "/Ether.php";

    $AETHER_ARISED[static::class]['ether'] = true;
  }

  // load phantasm
  public static function phantasm() {
    // global $AETHER_ARISED;
    // global $AETHER_PHANTASM;

    // if (!self::$hasEssence) {
    //   self::$hasEssence = true;
    // }
    // require_once static::$origin . "/Phantasm.php";
    // $phantasm = str_replace('Manifest', 'Phantasm', static::class);
    // $phantasm = new $phantasm();

    // $self = static::class;
    // $needs = (isset($phantasm->need)) ? $phantasm->need : [];
    // foreach ($needs as $row) {
    //   $manifest = 'Rune\\' . $row[0] . '\\Manifest';
    //   if (!isset($AETHER_ARISED[$manifest])) {
    //     $AETHER_PHANTASM[$manifest] = "WARNING PHANTASM: The {$self} needs {$manifest}".PHP_EOL;
    //   }
    // }
  }

  public static function mana() {
    static $bit = 0; // ← ini penting!
    self::$mana = $bit < PHP_INT_SIZE * 8 ? (1 << $bit++) : null;
    return self::$mana;
  }


}