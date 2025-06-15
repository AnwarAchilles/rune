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
      chanter_cast_get('base')();
    }else {
      chanter_cast_get($cast)();
    }

    aether_arcane("Chanter.manifest.awaken");
  }

  // cast
  public static function cast( String $args, Callable $callable = NULL ) {
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

  
  /* CONTROLS METHOD */
  // public static function get( String $arg ) {
  //   global $CHANTER_LIST;

  //   chanter_arg($arg);
  //   $arg = chanter_option_clean($arg);
  //   if (isset($CHANTER_LIST[$arg])) {
  //     $return = $CHANTER_LIST[$arg];
  //   }else {
  //     $return = function() use ($arg) {
  //       global $AETHER_FAMILIAR;
  //       global $CHANTER_REGISTERED;
  //       global $CHANTER_NOTE;

  //       (!aether_has_entity('whisper')) ?: 
  //         whisper_nl('{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}Unknown chanter "' . $arg . '"');

  //       if ($AETHER_FAMILIAR) {
  //         $catch = [];
  //         foreach ($CHANTER_REGISTERED as $registered) {
  //           if (soundex($arg) == soundex($registered)) {
  //             $catch[] = $registered;
  //           }
  //         }
  //         (!aether_has_entity('whisper')) ?: 
  //           whisper_nl("{{COLOR-SECONDARY}}{{ICON-INFO}}Did you mean?: ".implode(", ", $catch));
  //       }
  //     };
  //   };

  //   aether_arcane("Chanter.manifest.get");
  //   return $return;
  // }
  
  // public static function set( String $arg, Callable $callable ) {
  //   global $CHANTER_LIST;
  //   global $CHANTER_REGISTERED;
    
  //   if ($arg !== 'base') {
  //     $CHANTER_REGISTERED[] = $arg;
  //   }
  //   $arg = chanter_option_clean($arg);
  //   $CHANTER_LIST[$arg] = $callable;

  //   aether_arcane("Chanter.manifest.set");
  // }

  // public static function run( String $arg ) {
  //   global $AETHER_FAMILIAR;
    
  //   $arg = chanter_option_clean($arg);
  //   self::get($arg)();
  // }


  // public static function note( String $arg, String $text ) {
  //   global $CHANTER_NOTE;
    
  //   $CHANTER_NOTE[$arg] = $text;
  // }

  // // public static function cast( String $args ) {
  // //   return chanter_cast($args);
  // // }

  // public static function option( String $text ) {
  //   return chanter_option($text);
  // }

}