<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Forger;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  // create next static method

  public static function _arise() {
    self::phantasm();

  }

  public static function scan( String $sourcepath, $callback ) {
    forger_scan($sourcepath, $callback );
  }

}