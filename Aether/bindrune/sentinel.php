<?php

use Rune\Aether\Manifest as Aether;
use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Crafter\Manifest as Crafter;
use Rune\Forger\Manifest as Forger;

// sentinel
Chanter::set('sentinel', function() {
  global $AETHER_ARISED;

  $tab = "  ";

  $header = Weaver::load(__DIR__ . '/weaver/sentinel-header.txt');
  $header = Weaver::bindAll($header, [
    'AETHER-FILE'=> AETHER_FILE,
  ]);

  if (aether_has_entity('whisper')) {
    whisper_clear();
    whisper_nl($header);
  }else {
    aether_whisper($header);
  }
  
  
  (aether_has_entity('whisper')) ?: die(PHP_EOL.'[!]WARNING: Required Whisper:entity'.PHP_EOL);


  /* ALTAR
   *
   *  */
  if (chanter_option('rise_altar')) {
    (aether_has_entity('crafter')) ?: die(PHP_EOL.'[!]WARNING: Required Crafter:entity'.PHP_EOL);
    (aether_has_entity('forger')) ?: die(PHP_EOL.'[!]WARNING: Required Forger:entity'.PHP_EOL);

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
  }
  if (chanter_option('altar')) {
    Chanter::get('sentinel rise_altar')();

    whisper_clear();
    whisper_nl('');
    whisper_nl('{{COLOR-DANGER}} RUNE ALTAR IS RUNNING ON PORT 8100');
    whisper_nl('{{COLOR-SECONDARY}} stop terminal with CTL+C');
    whisper_nl('');

    Aether::localhost([
      'host'=> 'localhost',
      'port'=> 8100,
      'file'=> 'altar.php',
      'mode'=> 'private',
    ]);
  }

  /* INSPECT
   *
   *  */
  if (chanter_option('inspect')) {  
    $default_list = [
      [
        'type'=>'manifest',
        'call'=>'arise()',
        'note'=>'Inherited from Rune\Manifest & used for initialization Entity, Essence & Ether.',
      ],
      [
        'type'=>'manifest',
        'call'=>'entity()',
        'note'=>'Inherited from Rune\Manifest & used for initialization Entity.',
      ],
      [
        'type'=>'manifest',
        'call'=>'essence()',
        'note'=>'Inherited from Rune\Manifest & used for initialization Essence.',
      ],
      [
        'type'=>'manifest',
        'call'=>'ether()',
        'note'=>'Inherited from Rune\Manifest & used for initialization Ether.',
      ]
    ];
    
    foreach ($AETHER_ARISED as $manifest => $sub) {
      $phantasm = str_replace('Manifest', 'Phantasm', $manifest);
      $phantasm = new $phantasm();
      $x = (object) [
        'version'=> (isset($phantasm->version)) ? $phantasm->version : '',
        'need'=> (isset($phantasm->need)) ? $phantasm->need : '',
      ];

      whisper_nl("$tab{{COLOR-PRIMARY}}{{ICON-PRIMARY}} $manifest");
      if (count($x->need)==0) {
        whisper_nl("$tab$tab{{COLOR-SECONDARY}} (THIS RUNE IS STANDALONE)");
      }

      foreach ($x->need as $need) {
        $needManifest = 'Rune\\' . $need[0] . '\\Manifest';

        whisper_nl("$tab$tab{{COLOR-DEFAULT}}$needManifest - {{COLOR-SECONDARY}}v{$need[2]}^");
        if ($x->version < $need[2]) {
          whisper_nl("$tab$tab{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}Need rune version up to v{$need[2]}");
        }

        foreach (explode(':', $need[1]) as $submanifest) {
          if (isset($AETHER_ARISED[$needManifest][$submanifest])) {
            whisper_il("$tab$tab$tab{{COLOR-SUCCESS}}{{ICON-SUCCESS}}");
          }else {
            whisper_il("$tab$tab$tab{{COLOR-DANGER}}{{ICON-DANGER}}");
          }
          whisper_il("$tab{{COLOR-DEFAULT}} $submanifest");
          whisper_nl("");
        }

        whisper_nl("");
      }
      whisper_nl("");
    }
  }


  $avalaible_rune = function() {
    $result = [];
    foreach (glob(AETHER_RUNE_LOCATION . '/*') as $rune) {
      if (is_dir($rune)) {
        $result[] = basename($rune);
      }
    }
    return $result;
  };

  (aether_has_entity('forger')) or die(whisper_nl('{{COLOR-DANGER}}{{ICON-DANGER}} Forger:entity required'));


  /* INVOKE
   * todo invoke manifest & arise to rune
   *  */
  if (chanter_option('invoke')) {
    whisper_nl("{{COLOR-INFO}}{{ICON-INFO}} Avaliable rune: " . implode(', ', $avalaible_rune()));
    $input = whisper_input('Give us the rune name: ');
    if ($input) {
      Chanter::get('sentinel --invoke_option=' . $input)();
    }
  }
  if (chanter_option('invoke_option')) {
    $target = ucfirst(strtolower(chanter_option('invoke_option')));
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
        $rune = weaver_bind_custom($rune, $codex_manifest.PHP_EOL, '');
      }
      $rune = weaver_bind_custom($rune, $prefix_manifest, $codex_manifest.PHP_EOL.$prefix_manifest);
      
      // arising
      if ($state_arise=='single') {
        $codex_arise = "{$manifest}::arise();";
        // check codex arise
        if (strpos($rune, $codex_arise) !== false) {
          $rune = weaver_bind_custom($rune, $codex_arise.PHP_EOL, '');
        }
        $rune = weaver_bind_custom($rune, $prefix_arise, $codex_arise.PHP_EOL.$prefix_arise);
      }else {
        if (strpos($rune, "{$manifest}::arise();") !== false) {
          $rune = weaver_bind_custom($rune, "{$manifest}::arise();".PHP_EOL, '');
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
        $rune = weaver_bind_custom($rune, $prefix_arise, trim($codex_arise.PHP_EOL).PHP_EOL.$prefix_arise);
      }

      forger_set(AETHER_REPO . '/'. AETHER_FILE, $rune);
      whisper_nl("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Sentinel do invoke with '$manifest'");
    }
  }


  /* REVOKE
   * todo revoke manifest & arise in rune
   *  */
  if (chanter_option('revoke')) {
    whisper_nl("{{COLOR-INFO}}{{ICON-INFO}} Avaliable rune: " . implode(', ', $avalaible_rune()));
    $input = whisper_input('Give us the rune name: ');
    if ($input) {
      Chanter::get('sentinel --revoke_option=' . $input)();
    }
  }
  if (chanter_option('revoke_option')) {
    $target = ucfirst(strtolower(chanter_option('revoke_option')));
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

      // arising
      if ($state_arise=='multi') {
        // delete arise
        if (strpos($rune, $codex_arise) !== false) {
          $rune = weaver_bind_custom($rune, $codex_arise.PHP_EOL, '');
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
        foreach ($codex_arise_select as $codex_arise) {
          if (strpos($rune, $codex_arise) !== false) {
            $rune = weaver_bind_custom($rune, $codex_arise.PHP_EOL, '');
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
          $rune = weaver_bind_custom($rune, $codex_manifest.PHP_EOL, '');
        }
      }
      
      forger_set(AETHER_REPO . '/'. AETHER_FILE, $rune);
      whisper_nl("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Sentinel do revoke with '$manifest'");
    }
  }


  /* CODEX
   * todo sentinel generate codex
   *  */
  if (chanter_option('codex')) {
    whisper_nl("{{COLOR-DANGER}}{{ICON-WARNING}} Under Development, feature will be added soon..");
    whisper_nl('');
  }


  /* CHRONICLE
   * todo sentinel give you back to selected chronicle
   *  */
  if (chanter_option('cronicle')) {
    whisper_nl("{{COLOR-DANGER}}{{ICON-WARNING}} Under Development, feature will be added soon..");
    whisper_nl('');
  }
  





  


});