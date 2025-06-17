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
      $rune = Forger::item(AETHER_REPO . '/'. AETHER_FILE);
      
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

      Forger::item(AETHER_REPO . '/'. AETHER_FILE, $rune);
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
      $rune = Forger::item(AETHER_REPO . '/'. AETHER_FILE);
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
      
      Forger::item(AETHER_REPO . '/'. AETHER_FILE, $rune);
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
  




  /* 
   * FOR RUNE BUILDER (PRIVATE)
   * 
   *  */

  /* CREATE RUNE */
  $processing__create_rune = function($name, $repo) {
    // importing
    $phantasm = Weaver::item( __DIR__ . '/weaver/sentinel-rune--phantasm.txt');
    $manifest = Weaver::item( __DIR__ . '/weaver/sentinel-rune--manifest.txt');
    $ether = Weaver::item( __DIR__ . '/weaver/sentinel-rune--ether.txt');
    $essence = Weaver::item( __DIR__ . '/weaver/sentinel-rune--essence.txt');
    $entity = Weaver::item( __DIR__ . '/weaver/sentinel-rune--entity.txt');
  
    // weaving
    $vars = [
      'name-capital'=> ucfirst($name),
      'name-lower'=> strtolower($name),
      'name-upper'=> strtoupper($name),
    ];
    $phantasm = Weaver::bind($phantasm, $vars);
    $manifest = Weaver::bind($manifest, $vars);
    $ether = Weaver::bind($ether, $vars);
    $essence = Weaver::bind($essence, $vars);
    $entity = Weaver::bind($entity, $vars);
    
    // fixing
    forger_fix([
      ['type'=>'item', 'target'=> $repo . '/Phantasm.php'],
      ['type'=>'item', 'target'=> $repo . '/Manifest.php'],
      ['type'=>'item', 'target'=> $repo . '/Ether.php'],
      ['type'=>'item', 'target'=> $repo . '/Essence.php'],
      ['type'=>'item', 'target'=> $repo . '/Entity.php'],
    ]);
  
    // insert
    forger_item($repo . '/Phantasm.php', $phantasm);
    forger_item($repo . '/Manifest.php', $manifest);
    forger_item($repo . '/Ether.php', $ether);
    forger_item($repo . '/Essence.php', $essence);
    forger_item($repo . '/Entity.php', $entity);
  };
  if (Chanter::spell('create-rune')) {
    if (Chanter::spell('create-rune') !== '1') {
      $name = Chanter::spell('create-rune');
    }else {
      $name = Whisper::reap('Give us the rune name: ');
    }
    $name = ucfirst(strtolower($name));

    if (!file_exists(AETHER_REPO . '/.bindrune/')) {
      Whisper::emit("{{COLOR-DANGER}}{{ICON-WARNING}} You are not eligible!! {{nl}}");
      die();
    }

    $repo = AETHER_REPO . '/.bindrune/' . $name . '/';

    $processing__create_rune($name, $repo);

    Whisper::emit("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Success create rune: $name {{nl}}");
  }


  /* PHANTASM FIX */
  $processing__phantasm_fix_list = function($name) {
    $phantasm_class = 'Rune\\' . $name . '\\Phantasm';
    $phantasm = new $phantasm_class();

    $selected = '';
    foreach (aether_arised() as $manifest) {
      $basename = str_replace('Rune\\', '', $manifest);
      $basename = str_replace('\\Manifest', '', $basename);
      
      if ($basename == $name) {
        $selected = str_replace('Manifest', 'Phantasm', $manifest);
      }
    }

    $phantasm = new $selected();

    if (isset($phantasm->origin)) {
      
      $read_ether = Forger::item($phantasm->origin . '/Ether.php');
      preg_match_all("/define\(\s*['\"]([^'\"]+)['\"]\s*,/", $read_ether, $matches);
      $find_ether = $matches[1];

      $read_essence = Forger::item($phantasm->origin . '/Essence.php');
      preg_match_all("/\\\$GLOBALS\\[['\"]([^'\"]+)['\"]\\]/", $read_essence, $matches);
      $find_essence = $matches[1];

      $name_entity = strtoupper($name);
      $read_entity = Forger::item($phantasm->origin . '/Entity.php');
      preg_match_all('/function\s+(.*'.$name_entity.'.*)\s*\([^\)]*\)/i', $read_entity, $matches);
      $find_entity = $matches[1];

      $read_manifest = Forger::item($phantasm->origin . '/Manifest.php');
      preg_match_all('/\bstatic\s+function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(([^)]*)\)/', $read_manifest, $matches);
      // Gabungkan nama function + isi paramnya
      $find_manifest = array_map(function ($name, $params) {
          return $name . '(' . $params . ')';
      }, $matches[1], $matches[2]);


      $read_phantasm = Forger::item($phantasm->origin . '/Phantasm.php');
      $templates_phantasm = preg_replace('/(public\s\$list\s*=\s*)(\[[\s\S]*?\]);/', '{{list}}', $read_phantasm);
      
      $old_list = $phantasm->list;

      $list = [
        'ether' => $find_ether,
        'essence' => $find_essence,
        'entity' => $find_entity,
        'manifest' => $find_manifest,
      ];
      $maps = [];
      foreach ($list as $key => $row) {
        foreach ($row as $value) {
          $maps[] = [
            'type' => $key,
            'call' => $value,
            'note' => '',
          ];
        }
      }

      $combined = array_merge($maps, $old_list);
      $final = [];
      foreach ($combined as $item) {
        $final[$item['call']] = $item; // key = 'call', auto timpa kalau udah ada
      }
      $maps = array_values($final); // Reset index biar rapi


      
      $templates = 'public $list = [' . PHP_EOL;
      foreach ($maps as $row) {
        $templates .= "    [" . PHP_EOL;
        $templates .= "      'type' => '$row[type]'," . PHP_EOL;
        $templates .= "      'call' => '$row[call]'," . PHP_EOL;
        $templates .= "      'note' => '$row[note]'," . PHP_EOL;
        $templates .= "    ]," . PHP_EOL;
      }
      $templates .= '  ];' . PHP_EOL;
      
      $template = weaver_bind($templates_phantasm, 'list', $templates);
      
      Forger::item($phantasm->origin . '/Phantasm.php', $template);

      Whisper::emit("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Success update phantasm: $name {{nl}}");
    }else {
      Whisper::emit("{{COLOR-DANGER}}{{ICON-WARNING}} Phantasm not have origin!! {{nl}}");
    }
  };
  if (Chanter::spell('phantasm-fix-list')) {
    if (Chanter::spell('phantasm-fix-list') !== '1') {
      $name = Chanter::spell('phantasm-fix-list');
    }else {
      $name = Whisper::reap('Give us the rune name: ');
    }
    $name = ucfirst(strtolower($name));
    
    $processing__phantasm_fix_list($name);
  }




  


});