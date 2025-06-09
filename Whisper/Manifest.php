<?php

namespace Rune\Whisper;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;
  

  public static function _arise() {
    self::phantasm();

  }

  public static function form( Array $list ) {
    $result = [];
    foreach ($list as $title) {
      $result[] = whisper_input($title);
    }
    return $result;
  }


  public static function set( String $text, Array $option = [] ) {
    if (!in_array(WHISPER_SILENCE, $option)) {
      if (in_array(WHISPER_INLINE, $option)) {
        whisper_il($text);
      }else {
        whisper_nl($text);
      }
    }
    if (in_array(WHISPER_STAGING, $option)) {
      global $WHISPER_ITEMS;
      $WHISPER_ITEMS[] = $text;
    }
    return $text;
  }




  
  
  






}