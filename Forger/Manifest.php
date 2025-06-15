<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Forger;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  public static function _arise() {}

  public static function _aether_awaken() {}

  public static function awaken() {}
  
  public static function trace( String $source_path ) {
    forger_trace( $source_path );

    aether_arcane('Forger.Manifest.trace');
    return $source_path;
  }
  
  public static function scan( String $source_path, $callback ) {
    forger_scan($source_path, $callback );

    aether_arcane('Forger.Manifest.scan');
    return $source_path;
  }
  
  public static function fix( Array $source_path ) {
    forger_fix( $source_path );

    aether_arcane('Forger.Manifest.fix');
    return $source_path;
  }
  
  public static function repo( String $source_path, Callable $callback ) {
    forger_repo( $source_path, $callback );

    aether_arcane('Forger.Manifest.repo');
    return $source_path;
  }
  
  public static function item( String $source_path, String $content = '' ) {
    forger_item( $source_path, $content );

    aether_arcane('Forger.Manifest.item');
    return $source_path;
  }

  public static function clone( string $from, string $to ) {
    forger_clone( $from, $to );

    aether_arcane('Forger.Manifest.clone');
    return $from;
  }

}