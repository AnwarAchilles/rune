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
  if (chanter_option('altar')) {
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
  if (chanter_option('altar_run')) {
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

  /* ARISE
   *
   *  */
  if (chanter_option('arise')) {
    $runes = [];
    foreach (glob(AETHER_RUNE_LOCATION . '/*') as $rune) {
      if (is_dir($rune)) {
        $runes[] = basename($rune);
      }
    }

    whisper_nl("HINTS: Runes Available for you to arise");
    whisper_nl(implode(', ', $runes));
    $select = whisper_input('Select rune: ');
    if ($select) {
      echo chanter_cast("{{SELF}} arise_select=$select");
    }
  }
  if (chanter_option('arise_select')) {
    $select = chanter_option('arise_select');

    function inject_module($source, $module_name) {
      $use_line   = "use Rune\\{$module_name}\\Manifest as {$module_name};";
      $arise_line = "{$module_name}::arise();";

      $lines = explode("\n", $source);
      $new_lines = [];
      $has_use = false;
      $has_arise = false;

      // Cek apakah modul sudah dipakai sebelumnya
      foreach ($lines as $line) {
          if (trim($line) === $use_line) {
              $has_use = true;
          }
          if (trim($line) === $arise_line) {
              $has_arise = true;
          }
      }

      // Kalo udah ada semua, balikin original aja
      if ($has_use && $has_arise) {
          return $source;
      }

      $init_inserted = false;
      $inst_inserted = false;
      $in_initialize = false;
      $in_instance = false;

      foreach ($lines as $line) {
          // Deteksi akhir RUNE:INITIALIZE
          if (trim($line) === '// RUNE:INITIALIZE') {
              $in_initialize = true;
          } elseif ($in_initialize && trim($line) === '') {
              if (!$has_use && !$init_inserted) {
                  $new_lines[] = $use_line;
                  $init_inserted = true;
              }
              $in_initialize = false;
          }

          // Deteksi akhir RUNE:INSTANCE
          if (trim($line) === '// RUNE:INSTANCE') {
              $in_instance = true;
          } elseif ($in_instance && (trim($line) === '' || strpos(trim($line), '//') === 0)) {
              if (!$has_arise && !$inst_inserted) {
                  $new_lines[] = $arise_line;
                  $inst_inserted = true;
              }
              $in_instance = false;
          }

          $new_lines[] = $line;
      }

      // Fallback: jika belum tersisip karena akhir block tak terdeteksi
      if (!$init_inserted && !$has_use) {
          $new_lines[] = $use_line;
      }
      if (!$inst_inserted && !$has_arise) {
          $new_lines[] = $arise_line;
      }

      return implode("\n", $new_lines);
    }

    $aether_file = forger_file(AETHER_FILE);
    $aether_file = inject_module($aether_file, ucfirst($select));
    forger_set(AETHER_FILE, $aether_file);
    whisper_nl("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Rune $select installed");
  }





  


});