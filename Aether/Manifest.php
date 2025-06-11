<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Aether;

use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  // create next static method
  // public static function _rite() {
  //   self::ether();
  // }

  public static function _arise() {
    self::phantasm();
    
  }

  public static function origin( $vendor=null ) {
    aether_arcane("Aether.manifest.origin", true);
    
    global $AETHER_PHANTASM;
    
    // vendor
    if (!empty($vendor)) {
      aether_arcane("Aether.manifest.origin: binding runes");
      if (!file_exists(AETHER_REPO . '/.bindrune/')) {
        mkdir(AETHER_REPO . '/.bindrune/', 0777, true);
      }
      $vendor->addPsr4('Rune\\', AETHER_REPO . '/.bindrune/');
    }
    
    // phantasm
    aether_arcane("Aether.manifest.origin: create phantasm");
    foreach ($AETHER_PHANTASM as $phantasm) {
      if (aether_has_entity('whisper')) {
        whisper_nl("{{COLOR-SECONDARY}}{{ICON-WARNING}}$phantasm");
      }else if (aether_has_entity('forger')) {
        aether_arcane($phantasm);
      }else {
        print($phantasm);
      }
    }
    
    // familiar
    aether_arcane("Aether.manifest.origin: setup familiar");
    if (aether_has_entity('keeper')) {
      if (keeper_has('familiar.json')) {
        global $AETHER_FAMILIAR;
        $AETHER_FAMILIAR = true;
      }else {
        aether_arcane("Aether.manifest.origin: familiar status not ready");
      }
    }
    
    // do binding rune
    foreach (glob(__DIR__.'/bindrune/*.php') as $enchant) {
      require_once $enchant;
    }
    aether_arcane("Aether.manifest.origin: binding runes");

    // end
    aether_arcane("Aether.manifest.origin", false);
  }

  public static function awaken()
  {
    aether_arcane("Aether.manifest.awaken", true);

    global $AETHER_ARISED;
    global $AETHER_FAMILIAR;
    global $AETHER_PHANTASM;
    global $CHANTER_REGISTERED;
    global $CHANTER_NOTE;
    Chanter::awaken();
    
    if (aether_has_entity('keeper')) {
      // keeper_set('information.json', json_encode([
      //   'FILE'=> AETHER_FILE,
      //   'REPO'=> AETHER_REPO,
      //   'VERSION'=> AETHER_VERSION,
      //   'FAMILIAR'=> $AETHER_FAMILIAR,
      //   'CHANTER_REGISTERED'=> $CHANTER_REGISTERED,
      //   'CHANTER_NOTE'=> $CHANTER_NOTE,
      //   'ARISED'=> $AETHER_ARISED
      // ]));
    }

    
    aether_arcane("Aether.manifest.awaken", false);

    $memory = aether_memoryusage();
    aether_whisper_nl(
      '{{COLOR-SECONDARY}}{{ICON-INFO}}{{LABEL-INFO}}Rune memory usage is ' .
       aether_formatFileSize($memory[0]) . ', with peak is ' . aether_formatFileSize($memory[1])
    );

    $stopwatch = aether_stopwatch();
    aether_whisper_nl(
      '{{COLOR-SECONDARY}}{{ICON-INFO}}{{LABEL-INFO}}Rune process end in ' . 
      number_format($stopwatch, 4) . ' seconds'
    );

    // development mode
    aether_arcane_pretty_print();
  }

  public static function awakening()
  {
    require_once __DIR__ . '/bindrune/awakening/index.php';
  }

  public static function localhost($configure = [])
  {
    $config = (object) $configure;

    // Default value
    $config->host ??= '127.0.0.1';
    $config->port ??= '8000';

    // Jika mode public, ubah host ke 0.0.0.0
    if ($config->mode == 'public') {
        $config->host = '0.0.0.0';
    }

    // Path ke direktori
    $path = !empty($config->path) ? ' -t ' . escapeshellarg($config->path) : '';

    // Jika ada file router (kayak router.php)
    $file = !empty($config->file) ? ' ' . escapeshellarg($config->file) : '';

    // Gabungkan semua
    $command = 'php -S ' . $config->host . ':' . $config->port . $path . $file;

    // Jalankan server
    shell_exec($command);
  }

}