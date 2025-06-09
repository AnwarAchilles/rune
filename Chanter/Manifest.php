<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Chanter;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  public static function _arise() {
    self::phantasm();
    
  }


  public static function awaken() {
    global $AETHER_FAMILIAR;

    $args = chanter_args();
    $arg = chanter_arg();
    $arg = str_replace(AETHER_FILE.' ', '', $arg);
    $arg = str_replace(AETHER_FILE, '', $arg);
    if (count($args) > 1) {
      self::get($arg)();
    }else {
      self::get("base")();
    }
    
    (!aether_has_entity('whisper')) ?: 
      whisper_nl('{{COLOR-SECONDARY}}{{ICON-INFO}}{{LABEL-INFO}}Rune process end in ' . aether_stopwatch());
      whisper_nl('{{COLOR-SECONDARY}}{{ICON-INFO}}{{LABEL-INFO}}Rune memory usage is ' . aether_memoryusage());
  }

  
  public static function get( String $arg ) {
    global $CHANTER_LIST;

    aether_log("Chanter.manifest.get: " . $arg);
    chanter_arg($arg);
    $arg = chanter_option_clean($arg);
    if (isset($CHANTER_LIST[$arg])) {
      return $CHANTER_LIST[$arg];
    }else {
      aether_log("Chanter.manifest.get: Unknown chanter with last argument not found");
      return function() use ($arg) {
        global $AETHER_FAMILIAR;
        global $CHANTER_REGISTERED;
        global $CHANTER_NOTE;

        (!aether_has_entity('whisper')) ?: 
          whisper_nl('{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}Unknown chanter "' . $arg . '"');

        if ($AETHER_FAMILIAR) {
          $catch = [];
          foreach ($CHANTER_REGISTERED as $registered) {
            if (soundex($arg) == soundex($registered)) {
              $catch[] = $registered;
            }
          }
          (!aether_has_entity('whisper')) ?: 
            whisper_nl("{{COLOR-SECONDARY}}{{ICON-INFO}}Did you mean?: ".implode(", ", $catch));
        }
      };
    };
  }
  
  public static function set( String $arg, Callable $callable ) {
    global $CHANTER_LIST;
    global $CHANTER_REGISTERED;
    
    if ($arg !== 'base') {
      $CHANTER_REGISTERED[] = $arg;
    }
    $arg = chanter_option_clean($arg);
    $CHANTER_LIST[$arg] = $callable;
  }

  public static function run( String $arg ) {
    global $AETHER_FAMILIAR;
    
    aether_log("Chanter.manifest.run: " . $arg);
    $arg = chanter_option_clean($arg);
    self::get($arg)();
  }


  public static function note( String $arg, String $text ) {
    global $CHANTER_NOTE;
    
    $CHANTER_NOTE[$arg] = $text;
  }

  public static function cast( String $args ) {
    return chanter_cast($args);
  }

  public static function option( String $text ) {
    return chanter_option($text);
  }

}