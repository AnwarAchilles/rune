<?php

namespace Rune\Whisper;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;
  
  public static function _arise() {}

  public static function _aether_awaken() {}

  public static function awaken() {}

  public static function emit( String $message, Bool $asString = false ) {
    if ($asString) {
      $return = whisper_emit_get($message . '{{color-end}}');
    }else {
      whisper_emit_set($message . '{{color-end}}');
      $return = self::class;
    }

    aether_arcane('Whisper.manifest.emit');
    return $return;
  }

  public static function reap( String $text ) {
    $result = whisper_reap( $text );
    
    aether_arcane('Whisper.manifest.drain');
    return $result;
  }

  public static function latch( Mixed $state_or_process, Bool $asString = false ) {
    if (is_callable($state_or_process)) {
      whisper_latch_start();
      $state_or_process();
      $return = whisper_latch_get();
      whisper_latch_end();
    }
    if (is_bool($state_or_process)) {
      if ($state_or_process==true) {
        whisper_latch_start();
        $return = true;
      }
      if ($state_or_process==false) {
        $return = whisper_latch_get();
        whisper_latch_end();
      }
    }
    
    if (!$asString) {
      self::emit($return);
    }
    
    aether_arcane('Whisper.manifest.latch');
    return $return;
  }

  public static function drain( Callable $callable, Array $option = [] ) {
    whisper_drain($callable, $option);
    
    aether_arcane('Whisper.manifest.drain');
    return self::class;
  }

  public static function clear( Bool $force = false ) {
    if ($force) {
      whisper_clear_force();
      whisper_clear();
    }else {
      whisper_clear();
    }

    aether_arcane('Whisper.manifest.clear');
    return self::class;
  }






  

  // public static function form( Array $list ) {
  //   $result = [];
  //   foreach ($list as $title) {
  //     $result[] = whisper_input($title);
  //   }
  //   return $result;
  // }


  // public static function set( String $text, Array $option = [] ) {
  //   if (!in_array(WHISPER_SILENCE, $option)) {
  //     if (in_array(WHISPER_INLINE, $option)) {
  //       whisper_il($text);
  //     }else {
  //       whisper_nl($text);
  //     }
  //   }
  //   if (in_array(WHISPER_STAGING, $option)) {
  //     global $WHISPER_ITEMS;
  //     $WHISPER_ITEMS[] = $text;
  //   }
  //   return $text;
  // }

}