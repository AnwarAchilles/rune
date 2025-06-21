<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Chanter;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  // middleware arise
  public static function _arise() {}

  // middleware aether awaken
  public static function _aether_awaken_before() {
    self::awaken();
  }

  // self awaken
  public static function awaken() {
    global $CHANTER_ARG;
    global $CHANTER_ARG_CAST;
    global $CHANTER_ARG_SPELL;
    
    chanter_arg_extract();
    $spell = chanter_spell_chain();
    $cast = $CHANTER_ARG_CAST . ' ' . $spell;

    if ($CHANTER_ARG == AETHER_FILE) {
      $run = chanter_cast_get('rune');
    }else {
      $run = chanter_cast_get($cast);
    }

    if (chanter_spell_get('zero-trust')) {
      aether_arcane_disable();
      chanter_whisper_drain( $run );
      
      if (aether_has_entity('specter')) {
        specter_exit('php '.chanter_arg());
      }

      aether_exit(true);
    }else {
      $run();
      
      if (aether_has_entity('specter')) {
        specter_exit('php '.chanter_arg());
      }
    }
    

    aether_arcane("Chanter.manifest.awaken");
  }

  // cast
  public static function cast( String $args, ?Callable $callable = NULL ) {
    if (empty($callable)) {
      $return = chanter_cast_get($args);
    }else {
      chanter_cast_set($args, $callable);
      $return = self::class;
    }

    aether_arcane("Chanter.manifest.cast");
    return $return;
  }
  
  // spell
  public static function spell( String $name, $values = NULL ) {
    if (empty($values)) {
      $return = chanter_spell_get($name);
    }else {
      chanter_spell_set($name, $values);
      $return = true;
    }

    aether_arcane("Chanter.manifest.cast");
    return $return;
  }

  // echo
  public static function echo( String $text ) {
    chanter_echo($text);
    
    aether_arcane("Chanter.manifest.echo");
    return $text;
  }

}