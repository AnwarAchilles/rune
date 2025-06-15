<?php

use Rune\Aether\Manifest as Aether;
use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Keeper\Manifest as Keeper;

use Rune\Crafter\Manifest as Crafter;
use Rune\Forger\Manifest as Forger;
use Rune\Specter\Manifest as Specter;

// sentinel
Chanter::cast('sentinel', function() {
  global $AETHER_ARISED;

  $header = Weaver::item(__DIR__ . '/weaver/sentinel-header.txt');
  $header = Weaver::bind($header, [
    'AETHER-FILE'=> AETHER_FILE,
  ]);
  

  if (aether_has_entity('whisper')) {
    whisper_clear();
    Whisper::emit($header);
  }else {
    aether_whisper($header);
  }



  /* FOR RUNE BINDER */
  $avalaible_rune = function() {
    $result = [];
    foreach (glob(AETHER_RUNE_LOCATION . '/*') as $rune) {
      if (is_dir($rune)) {
        $result[] = basename($rune);
      }
    }
    return $result;
  };

  /* ALTAR
   *
   *  */
  $processing_altar = function() {
    Crafter::set(AETHER_REPO.'/altar.php', function() {
      Crafter::base('REPO', __DIR__.'/');
      Crafter::base('TYPE', 'plain');
      Crafter::base('LANGUAGE', ['html','css','js','php']);
      
      Crafter::item('altar/nirvana.css');
      Crafter::item('altar/nirvana.js');
      Crafter::item('altar/nirvana.php');
  
      Crafter::item('altar/index.head.html');
      Crafter::item('altar/index.html');
      Crafter::item('altar/index.css');
      Crafter::item('altar/index.js');
      Crafter::item('altar/index.php');
    });
    Crafter::run(AETHER_REPO.'/altar.php');
  };
  if (Chanter::spell('rise_altar')) {
    (aether_has_entity('crafter')) ?: die(PHP_EOL.'[!]WARNING: Required Crafter:entity'.PHP_EOL);
    (aether_has_entity('forger')) ?: die(PHP_EOL.'[!]WARNING: Required Forger:entity'.PHP_EOL);
    $processing_altar();
  }
  if (Chanter::spell('altar')) {
    $processing_altar();

    whisper_clear();
    Whisper::emit('');
    Whisper::emit('{{COLOR-DANGER}} RUNE ALTAR IS RUNNING ON PORT 8100');
    Whisper::emit('{{COLOR-SECONDARY}} stop terminal with CTL+C');
    Whisper::emit('');

    Aether::localhost([
      'host'=> 'localhost',
      'port'=> 8100,
      'file'=> 'altar.php',
      'mode'=> 'private',
    ]);
  }
  if (Chanter::spell('dev_altar_watch')) {
    whisper_clear();
    Specter::observer(__DIR__.'/altar/', function() use ($processing_altar) {
      $processing_altar();
    });
  }
  if (Chanter::spell('dev_altar')) {
    Specter::open("{{SELF}} sentinel --dev_altar_watch");
    Specter::open("{{SELF}} sentinel --altar");
  }
  




  /* INSPECT
   *
   *  */
  if (Chanter::spell('inspect')) {  

    Whisper::clear()::emit('{{COLOR-DANGER}} Inspect is deprecated {{nl}}');
  }

  


  /* INVOKE
   * todo invoke manifest & arise to rune
   *  */
  $processing_invoke = function($target) {
    (aether_has_entity('forger')) ?: die(PHP_EOL.'[!]WARNING: Required Forger:entity'.PHP_EOL);

    $target = ucfirst(strtolower($target));
    if ($target) {
      if (strpos($target, '.') !== false) {
        $target = explode('.', $target);
        $manifest = $target[0];
        unset($target[0]);
        $arise = $target;
        $state_arise = 'multi';
      }else {
        $manifest = $target;
        $state_arise = 'single';
      }
      $rune = forger_file(AETHER_REPO . '/'. AETHER_FILE);
      $prefix_manifest = '#sentinel-manifest';
      $prefix_arise = '#sentinel-arise';
      $codex_manifest = "use Rune\\{$manifest}\\Manifest as {$manifest};";
      // check codex manifest
      if (strpos($rune, $codex_manifest) !== false) {
        $rune = str_replace($codex_manifest.PHP_EOL, '', $rune);
      }
      $rune = str_replace($prefix_manifest, $codex_manifest.PHP_EOL.$prefix_manifest, $rune);
      
      // arising
      if ($state_arise=='single') {
        $codex_arise = "{$manifest}::arise();";
        // check codex arise
        if (strpos($rune, $codex_arise) !== false) {
          $rune = str_replace($codex_arise.PHP_EOL, '', $rune);
        }
        $rune = str_replace($prefix_arise, $codex_arise.PHP_EOL.$prefix_arise, $rune);
      }else {
        if (strpos($rune, "{$manifest}::arise();") !== false) {
          $rune = str_replace("{$manifest}::arise();".PHP_EOL, '', $rune);
        }
        $codex_arise_list = [
          'ether'=> "{$manifest}::ether();",
          'essence'=> "{$manifest}::essence();",
          'entity'=> "{$manifest}::entity();",
        ];
        $codex_arise_select = [];
        foreach ($arise as $ID) {
          $codex_arise_select[] = $codex_arise_list[$ID];
        }
        $codex_arise = '';
        foreach ($arise as $ID) {
          $codex_arise .= $codex_arise_list[$ID].PHP_EOL;
        }
        $rune = str_replace($prefix_arise, trim($codex_arise.PHP_EOL).PHP_EOL.$prefix_arise, $rune);
      }

      forger_set(AETHER_REPO . '/'. AETHER_FILE, $rune);
      Whisper::emit("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Sentinel do invoke with '$manifest' {{nl}}");
    }
  };
  if (Chanter::spell('invoke')) {
    Whisper::clear();
    if (Chanter::spell('invoke') !== '1') {
      $input = Chanter::spell('invoke');
    }else {
      Whisper::emit("{{COLOR-SECONDARY}}{{ICON-INFO}} Avaliable rune: " . implode(', ', $avalaible_rune()) . "{{nl}}");
      $input = Whisper::reap('Give us the rune name: ');
    }
    if ($input) {
      $processing_invoke($input);
    }
  }
  // if (Chanter::spell('invoke_option')) {
  //   $input = Chanter::spell('invoke_option');
  //   if ($input) {
  //     $processing_invoke($input);
  //   }
  // }


  /* REVOKE
   * todo revoke manifest & arise in rune
   *  */
  $processing_revoke = function($target) {
    $target = ucfirst(strtolower($target));
    if ($target) {
      if (strpos($target, '.') !== false) {
        $target = explode('.', $target);
        $manifest = $target[0];
        unset($target[0]);
        $arise = $target;
        $state_arise = 'multi';
      }else {
        $manifest = $target;
        $state_arise = 'single';
      }
      $rune = forger_file(AETHER_REPO . '/'. AETHER_FILE);
      $prefix_manifest = '#sentinel-manifest';
      $prefix_arise = '#sentinel-arise';
      $codex_manifest = "use Rune\\{$manifest}\\Manifest as {$manifest};";
      $codex_arise = "{$manifest}::arise();";

      // delete arise
      if (strpos($rune, $codex_arise) !== false) {
        $rune = str_replace($codex_arise.PHP_EOL, '', $rune);
      }

      // arising
      if ($state_arise=='multi') {
        $codex_arise_list = [
          'ether'=> "{$manifest}::ether();",
          'essence'=> "{$manifest}::essence();",
          'entity'=> "{$manifest}::entity();",
        ];
        $codex_arise_select = [];
        foreach ($arise as $ID) {
          $codex_arise_select[] = $codex_arise_list[$ID];
        }
        foreach ($codex_arise_select as $codex_arise) {
          if (strpos($rune, $codex_arise) !== false) {
            $rune = str_replace($codex_arise.PHP_EOL, '', $rune);
          }
        }
      }


      // arise check
      $check_arise_list = [
        'ether'=> "{$manifest}::ether();",
        'essence'=> "{$manifest}::essence();",
        'entity'=> "{$manifest}::entity();",
      ];
      $check_arise_list_state = 3;
      foreach ($check_arise_list as $ID => $codex_arise) {
        if (!strpos($rune, $codex_arise) !== false) {
          $check_arise_list_state -= 1;
        }
      }
      if ($check_arise_list_state == 0) {
        // delete manifest
        if (strpos($rune, $codex_manifest) !== false) {
          $rune = str_replace($codex_manifest.PHP_EOL, '', $rune);
        }
      }
      
      forger_set(AETHER_REPO . '/'. AETHER_FILE, $rune);
      Whisper::emit("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Sentinel do revoke with '$manifest' {{nl}}");
    }
  };
  if (Chanter::spell('revoke')) {
    (aether_has_entity('forger')) ?: die(PHP_EOL.'[!]WARNING: Required Forger:entity'.PHP_EOL);

    Whisper::clear();
    if (Chanter::spell('revoke') !== '1') {
      $input = Chanter::spell('revoke');
    }else {
      Whisper::emit("{{COLOR-SECONDARY}}{{ICON-INFO}} Avaliable rune: " . implode(', ', $avalaible_rune())."{{nl}}");
      $input = Whisper::reap('Give us the rune name: ');
    }
    if ($input) {
      $processing_revoke($input);
    }
  }
  // if (Chanter::spell('revoke_option')) {
  //   $input = Chanter::spell('revoke_option');
  //   if ($input) {
  //     $processing_revoke($input);
  //   }
  // }


  /* CODEX
   * todo sentinel generate codex
   *  */
  if (Chanter::spell('codex')) {
    Whisper::emit("{{COLOR-DANGER}}{{ICON-WARNING}} Under Development, feature will be added soon.. {{nl}}");
  }


  /* CHRONICLE
   * todo sentinel give you back to selected chronicle
   *  */
  if (Chanter::spell('cronicle')) {
    Whisper::emit("{{COLOR-DANGER}}{{ICON-WARNING}} Under Development, feature will be added soon.. {{nl}}");
  }
  




  /* FOR RUNE BUILDER */

  if (Chanter::spell('create-phantasm')) {
    Whisper::emit("{{COLOR-DANGER}}{{ICON-WARNING}} Under Development, feature will be added soon.. {{nl}}");
  }




  


});