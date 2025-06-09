<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Crafter;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  public static function _arise() {
    self::phantasm();

  }

  // create next static method
  public static function set( String $source, Callable $callable ) {
    global $CRAFTER_BASE;
    global $CRAFTER_APPS;
    global $CRAFTER_MAPS;

    $CRAFTER_BASE = CRAFTER_BASE;

    $CRAFTER_MAPS[$source] = [
      'name'=> basename($source),
      'repo'=> $source,
    ];
    
    $CRAFTER_APPS[$source] = $callable;
  }

  public static function get( String $source ) {
    global $CRAFTER_BASE;
    global $CRAFTER_APPS;
    global $CRAFTER_MAPS;
    global $CRAFTER_ITEMS;
    
    if (array_key_exists($source, $CRAFTER_APPS)) {
      $CRAFTER_APPS[$source]();
      return [
        'bases'=> $CRAFTER_BASE,
        'maps'=> $CRAFTER_MAPS[$source],
        'items'=> $CRAFTER_ITEMS
      ];
    }else {
      return false;
    }
  }

  public static function item( String $source, Callable $injection = null ) {
    global $CRAFTER_BASE;
    global $CRAFTER_ITEMS;

    if (file_exists($CRAFTER_BASE['REPO'] . $source)) {
      $target = $CRAFTER_BASE['REPO'] . $source;
      $pathinfo = pathinfo($target);
      $sourcecode = file_get_contents($target);

      if (str_contains($pathinfo['filename'], '.head')) {
        $pathinfo['extension'] = 'head.html';
      }

      $CRAFTER_ITEMS[$source] = $pathinfo;
      $CRAFTER_ITEMS[$source]['filesize'] = filesize($target);
      $CRAFTER_ITEMS[$source]['target'] = $pathinfo['dirname'] . '/' . $pathinfo['basename'];
      $CRAFTER_ITEMS[$source]['targetfile'] = str_replace(AETHER_REPO, '', $CRAFTER_ITEMS[$source]['target']);
      // $CRAFTER_ITEMS[$source]['source'] = file_get_contents($target);
      if ($injection) {
        $CRAFTER_ITEMS[$source]['source'] = $injection( $CRAFTER_ITEMS[$source]['source'] );
      }else {
        $CRAFTER_ITEMS[$source]['source'] = $sourcecode;
      }
    }
  }

  public static function base( String $ID, $value ) {
    global $CRAFTER_BASE;
    $CRAFTER_BASE[$ID] = $value;
  }

  public static function run( String $source, Callable $injection = null ) {
    global $CRAFTER_APPS;
    global $CRAFTER_MAPS;
    global $CRAFTER_DIST;

    if ( array_key_exists($source, $CRAFTER_APPS) ) {
      $CRAFTER_APPS[$source]();
      self::_cleaning();
      self::_clutering();
      self::_bundling();
      self::_predistribute();
      if ($injection) {
        $CRAFTER_DIST = $injection( $CRAFTER_DIST );
      }
      self::_distributing( $source );
    }else {
      return function () {
        whisper_nl("{{COLOR-WARNING}}{{ICON-WARNING}} {$source} not found");
      };
    }
  }

  







  private static function _cleaning() {
    global $CRAFTER_BASE;
    global $CRAFTER_ITEMS;
    $cleans = constant('CRAFTER_CLEANING');
    $lint_error = false;

    foreach ($CRAFTER_ITEMS as $ID => $row) {
      if (isset($cleans[$row['extension']])) {
        foreach ($cleans[$row['extension']] as $clean) {
          $cleaned = str_replace($clean, '', $CRAFTER_ITEMS[$ID]['source']);
          if (in_array($row['extension'], $CRAFTER_BASE['MINIFIED'])) {
            $cleaned = weaver_min($cleaned, $row['extension']);
          }
          $CRAFTER_ITEMS[$ID]['source'] = $cleaned;
        }
      }

      if ($CRAFTER_BASE['LINT']) {
        if ($row['extension'] == 'php') {
          $target = $CRAFTER_ITEMS[$ID]['target'];
          $lint = shell_exec("php -l \"$target\" 2>&1");
          if ($lint && str_contains($lint, 'Parse error')) {
            whisper_nl("{{COLOR-ERROR}}{{ICON-ERROR}}{{LABEL-ERROR}} $lint");
            $lint_error = true;
          }
        }
      }
    }

    if ($lint_error) {
      whisper_nl('{{COLOR-SECONDARY}}{{ICON-INFO}}LINT ERROR: Stop Execution, Check your code before running crafting');
      exit();
    }
  }

  private static function _clutering() {
    global $CRAFTER_ITEMS;
    global $CRAFTER_CLUSTERS;
    
    foreach ($CRAFTER_ITEMS as $row) {
      $CRAFTER_CLUSTERS[$row['extension']][] = $row['source'];
    }
  }

  private static function _bundling() {
    global $CRAFTER_BASE;
    global $CRAFTER_ITEMS;
    global $CRAFTER_CLUSTERS;
    global $CRAFTER_DIST;

    $CRAFTER_ITEMS = [];

    $templates = '';
    $weaver_selected = 0;

    foreach (CRAFTER_WEAVER as $CWID => $weaver) {
      if ($weaver[0] == $CRAFTER_BASE['LANGUAGE'] && $weaver[1] == $CRAFTER_BASE['TYPE']) {
        $templates = weaver_load($weaver[3]);
        $weaver_selected = $CWID;
      }
    }

    if (CRAFTER_WEAVER[$weaver_selected][2] == 2) {
      $encryption = $CRAFTER_BASE['ENCRYPTION'];
      $templates = weaver_bind($templates, 'ENCRYPTION', $encryption);
      
      $plain = weaver_load(CRAFTER_WEAVER[0][3]);
      $encryption_encode = $CRAFTER_BASE['ENCRYPTION'] . '_encode';
      $templates = weaver_bind($templates, 'CONSTRUCT', $encryption_encode($plain));
    }
    
    foreach ($CRAFTER_CLUSTERS as $type => $cluster) {
      $search = CRAFTER_VARIABLE[$type];
      $source = implode("\n", $cluster);
      if (CRAFTER_WEAVER[$weaver_selected][2] == 2) {
        $encryption = $CRAFTER_BASE['ENCRYPTION'] . '_encode';
        $source = $encryption($source);
      }
      $templates = weaver_bind($templates, $search, $source);
    }

    $CRAFTER_DIST = $templates;
  }

  private static function _predistribute() {
    global $CRAFTER_DIST;

    $CRAFTER_DIST = weaver_bind($CRAFTER_DIST, 'COPYRIGHT', AETHER_COPYRIGHT);
    $CRAFTER_DIST = weaver_bind($CRAFTER_DIST, 'COPYRIGHT-VERSION', AETHER_VERSION);
    $CRAFTER_DIST = weaver_bind($CRAFTER_DIST, 'HASH-APP', md5(uniqid()));
  }

  private static function _distributing( $source ) {
    global $CRAFTER_CLUSTERS;
    global $CRAFTER_MAPS;
    global $CRAFTER_DIST;
    global $CRAFTER_LAST_BUILD;

    $CRAFTER_CLUSTERS = [];

    
    $name = $CRAFTER_MAPS[$source]['name'];
    $repo = $CRAFTER_MAPS[$source]['repo'];
    $repo_route = str_replace(basename($repo), '', $repo);
    
    forger_route($repo_route);
    if ( file_put_contents($repo, $CRAFTER_DIST)) {
      $size = aether_formatFileSize(filesize($repo));
      whisper_nl("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Crafting '{$name}' completed {$size}");
    }else {
      whisper_nl("{{COLOR-ERROR}}{{ICON-ERROR}}{{LABEL-ERROR}}Crafting {$name} cause file weaver not found");
    }
  } 

}