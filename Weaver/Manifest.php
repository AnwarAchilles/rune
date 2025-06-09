<?php

namespace Rune\Weaver;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  public static function _arise() {
    self::phantasm();

  }
  

  public static function load( $source ) {
    return file_get_contents($source);
  }

  public static function bind( $template, $search, $data ) {
    $search = strtoupper($search);
    $parse = str_replace("{{ ".$search." }}", $data, $template);
    $parse = str_replace("{{".$search."}}", $data, $template);
    return $parse;
  }

  public static function bindAll( $template, $list) {
    foreach ($list as $key => $value) {
      $template = self::bind($template, $key, $value);
    }
    return $template;
  }

}