<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Aether;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  public static function _arise() {}

  public static function origin( $vendor=null ) {
    global $AETHER_PHANTASM;

    gc_collect_cycles();
    aether_arcane_reset();
    
    // vendor
    if (!empty($vendor)) {
      aether_arcane("Aether.manifest.origin: binding runes");
      if (!file_exists(AETHER_REPO . '/.bindrune/')) {
        mkdir(AETHER_REPO . '/.bindrune/', 0777, true);
      }
      $vendor->addPsr4('Rune\\', AETHER_REPO . '/.bindrune/');
    }
    
    // phantasm
    // foreach ($AETHER_PHANTASM as $phantasm) {
    //   if (aether_has_entity('whisper')) {
    //     whisper_nl("{{COLOR-SECONDARY}}{{ICON-WARNING}}$phantasm");
    //   }else if (aether_has_entity('forger')) {
    //     aether_arcane($phantasm);
    //   }else {
    //     print($phantasm);
    //   }
    // }
    
    // if (aether_has_entity('keeper')) {
    //   if (keeper_has('familiar.json')) {
    //     global $AETHER_FAMILIAR;
    //     $AETHER_FAMILIAR = true;
    //   }
    // }
    
    // do binding rune
    // foreach (glob(__DIR__.'/bindrune/*.php') as $enchant) {
    //   require_once $enchant;
    // }
    require_once __DIR__ . '/bindrune/rune.php';
    require_once __DIR__ . '/bindrune/grimoire.php';
    require_once __DIR__ . '/bindrune/sentinel.php';
    require_once __DIR__ . '/bindrune/artefact.php';

    // end
    aether_arcane("Aether.manifest.origin");
  }

  public static function awaken()
  {
    // global $AETHER_FAMILIAR;
    // global $AETHER_PHANTASM;
    // global $CHANTER_REGISTERED;
    // global $CHANTER_NOTE;

    // auto awaken
    $arised = aether_arised();
    foreach ($arised as $manifest) {
      if (method_exists($manifest, '_aether_awaken_before')) {
        $manifest::_aether_awaken_before();
      }
    }
    foreach ($arised as $manifest) {
      if (method_exists($manifest, '_aether_awaken')) {
        $manifest::_aether_awaken();
      }
    }
    foreach ($arised as $manifest) {
      if (method_exists($manifest, '_aether_awaken_after')) {
        $manifest::_aether_awaken_after();
      }
    }
    
    // if (aether_has_entity('keeper')) {
    //   keeper_item('aether', [
    //     'FILE'=> AETHER_FILE,
    //     'REPO'=> AETHER_REPO,
    //     'VERSION'=> AETHER_VERSION,
    //     'SIZE'=> filesize(AETHER_FILE),
    //     'MEMORY'=> [$memory[0], $memory[1]],
    //     'RUNE'=> aether_arised(),
    //   ]);
    //   keeper_arcane_process();
    // }

    // end
    aether_arcane("Aether.manifest.awaken");
    // development mode
    // aether_arcane_pretty_print();
    aether_exit(true);
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