<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;

// grimoire
Chanter::set('grimoire', function() {
  global $AETHER_ARISED;

  (aether_has_entity('whisper')) ?: die(PHP_EOL.'[!]WARNING: Required Whisper:entity'.PHP_EOL);

  $tab = "  ";
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

  $header = Weaver::load(__DIR__ . '/weaver/grimoire-header.txt');
  $header = Weaver::bindAll($header, [
    'AETHER-FILE'=> AETHER_FILE
  ]);

  whisper_clear();
  whisper_nl($header);
  whisper_nl("");
  
  /* BASE
   *
   *  */
  if (chanter_option('all')) {
    $manifests = [];
    $keeper_runes = [];
    // internal rune
    foreach (glob(AETHER_RUNE_LOCATION . '/*') as $manifest) {
      if (is_dir($manifest)) {
        $pathinfo = pathinfo($manifest);
  
        $manifests[] = [
          'Rune\\' . $pathinfo['basename'] . '\\Manifest',
          AETHER_RUNE_LOCATION, 
          'internal'
        ];
      }
    }
    // external rune
    if (file_exists(AETHER_REPO . '/.bindrune')) {
      foreach (glob(AETHER_REPO . '/.bindrune/*') as $manifest) {
        if (is_dir($manifest)) {
          $pathinfo = pathinfo($manifest);
          $manifests[] = [
            'Rune\\' . $pathinfo['basename'] . '\\Manifest',
            realpath(AETHER_REPO . '/.bindrune'),
            'external'
          ];
        }
      }
    }
    // grimoire
    foreach ($manifests as $manifest_array) {
      $manifest = $manifest_array[0];
      $manifest = str_replace('\\Manifest', '\\Phantasm', $manifest);
      $phantasm = new $manifest();
      $phantasm->list = array_merge($default_list, $phantasm->list);
      $x = (object) [
        'main'=> (isset($phantasm->main)) ? $phantasm->main : '-',
        'origin'=> $manifest_array[1],
        'type'=> $manifest_array[2],
        'version'=> (isset($phantasm->version)) ? $phantasm->version : '-',
        'user'=> (isset($phantasm->user)) ? $phantasm->user : '-',
        'note'=> (isset($phantasm->note)) ? $phantasm->note : '-',
        'need'=> (isset($phantasm->need)) ? $phantasm->need : '-',
        'mark'=> (isset($phantasm->mark)) ? $phantasm->mark : false,
      ];
      
      whisper_il("$tab");
      whisper_il("{{COLOR-PRIMARY}}{{ICON-PRIMARY}} $x->main");
      whisper_il("$tab-$tab{{COLOR-INFO}}v$x->version");
      whisper_nl("");
      if ($x->mark) {
        whisper_nl("$tab$tab{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}This rune is marked as $x->mark.");
      }
      whisper_nl("$tab$tab{{COLOR-SECONDARY}}[M] $manifest");
      whisper_nl("$tab$tab{{COLOR-SECONDARY}}[O] $x->origin");
      whisper_nl("$tab$tab{{COLOR-SECONDARY}}[U] $x->user");
      whisper_nl("$tab$tab{{COLOR-SECONDARY}}[N] $x->note");
      
      whisper_nl("$tab$tab{{COLOR-INFO}}Need of Rune:");
      if (count($x->need) == 0) {
        whisper_nl("$tab$tab$tab{{COLOR-SECONDARY}} (THIS RUNE IS STANDALONE)");
      }
      foreach ($x->need as $need) {
        whisper_nl("$tab$tab$tab{{COLOR-DEFAULT}} $need[0] {{COLOR-DANGER}}$need[1] {{COLOR-SECONDARY}}v$need[2]^");
      }
      
      whisper_nl("$tab$tab{{COLOR-INFO}}List of Phantasm:");
      foreach ($phantasm->list as $list) {
        $list = (object) $list;
        whisper_il("$tab$tab");
        whisper_il("$tab{{COLOR-DANGER}}$list->type");
        whisper_il("$tab{{COLOR-DEFAULT}}$list->call");
        whisper_il("$tab{{COLOR-SECONDARY}}$list->note");
        whisper_nl("$tab");
      }
  
      $keeper_runes[] = $x;
      whisper_nl("");
    }
    
    keeper_set('runes.json', json_encode($keeper_runes));
  }
  
  if (chanter_option('one')) {
    $input = whisper_input('Give us the rune name: ');
    if ($input) {
      echo shell_exec("php " . AETHER_FILE . " grimoire --select=" . $input);
    }
  }

  if (chanter_option('rune_select')) {
    $rune = chanter_option('select');
    $manifest = 'Rune\\' . $rune . '\\Manifest';
    $phantasm = 'Rune\\' . $rune . '\\Phantasm';
    $phantasm = new $phantasm();
    $phantasm->list = array_merge($default_list, $phantasm->list);
    $x = (object) [
      'main'=> (isset($phantasm->main)) ? $phantasm->main : '-',
      'version'=> (isset($phantasm->version)) ? $phantasm->version : '-',
      'user'=> (isset($phantasm->user)) ? $phantasm->user : '-',
      'note'=> (isset($phantasm->note)) ? $phantasm->note : '-',
      'need'=> (isset($phantasm->need)) ? $phantasm->need : '-',
      'mark'=> (isset($phantasm->mark)) ? $phantasm->mark : false,
    ];
    
    whisper_il("$tab");
    whisper_il("{{COLOR-PRIMARY}}{{ICON-PRIMARY}} $x->main");
    whisper_il("$tab-$tab{{COLOR-INFO}}v$x->version");
    whisper_nl("");
    if ($x->mark) {
      whisper_nl("$tab$tab{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}This rune is marked as $x->mark.");
    }
    whisper_nl("$tab$tab{{COLOR-SECONDARY}}[M] $manifest");
    whisper_nl("$tab$tab{{COLOR-SECONDARY}}[U] $x->user");
    whisper_nl("$tab$tab{{COLOR-SECONDARY}}[N] $x->note");

    whisper_nl("$tab$tab{{COLOR-INFO}}Need of Rune:");
    if (count($x->need) == 0) {
      whisper_nl("$tab$tab$tab{{COLOR-SECONDARY}} (THIS RUNE IS STANDALONE)");
    }
    foreach ($x->need as $need) {
      whisper_nl("$tab$tab$tab{{COLOR-DEFAULT}} $need[0] {{COLOR-DANGER}}$need[1] {{COLOR-SECONDARY}}v$need[2]^");
    }
      
    whisper_nl("$tab$tab{{COLOR-INFO}}List of Phantasm:");
    foreach ($phantasm->list as $list) {
      $list = (object) $list;
      whisper_il("$tab$tab");
      whisper_il("$tab{{COLOR-DANGER}}$list->type");
      whisper_il("$tab{{COLOR-DEFAULT}}$list->call");
      whisper_il("$tab{{COLOR-SECONDARY}}$list->note");
      whisper_nl("$tab");
    }

    whisper_nl("");
  }


  /* LOGS
   * todo list & show logs user interact to
   *  */
  if (chanter_option('log')) {
    whisper_clear();
    whisper_nl("{{COLOR-PRIMARY}}Choose a rune to log");

    $lists = [];
    foreach (array_reverse(glob(AETHER_LOGS.'/*')) as $log) {
      $lists[] = [
        'name'=> basename($log),
        'path'=> $log
      ];
    }
    foreach ($lists as $id=>$list) {
      $id = $id + 1;
      if ($id == 1) {
        whisper_nl("[$id]: $list[name] (current)");
      }else {
        whisper_nl("[$id]: $list[name]");
      }
    }

    $select = whisper_input('Select number: ');
    if ($select) {
      $name = $lists[$select-1]['name'];
      $path = $lists[$select-1]['path'];
  
      $result = file_get_contents($path);
      $result = str_replace('[', '{{COLOR-SECONDARY}}[', $result);
      $result = str_replace(']', ']{{COLOR-DEFAULT}}', $result);
      $result = str_replace('START RUNE', '{{COLOR-DANGER}}START RUNE{{COLOR-DEFAULT}}', $result);
      whisper_nl($result);
      whisper_nl("");
      
      // logs
      aether_log("grimoire --log: $name");
    }
  }

  if (chanter_option('log_current')) {
    $name = date('Y-m-d');
    echo chanter_cast("{{SELF}} grimoire --log_select=$name");
    
    // logs
    aether_log("grimoire --log_current: $name");
  }

  if (chanter_option('log_select')) {
    $select = chanter_option('log_select');
    if ($select) {
      $name = $select;
      $path = AETHER_LOGS . '/' . $select . '.txt';
  
      $result = file_get_contents($path);
      $result = str_replace('[', '{{COLOR-SECONDARY}}[', $result);
      $result = str_replace(']', ']{{COLOR-DEFAULT}}', $result);
      $result = str_replace('START RUNE', '{{COLOR-DANGER}}START RUNE{{COLOR-DEFAULT}}', $result);
      whisper_nl($result);
      whisper_nl("");
      
      // logs
      aether_log("grimoire --log_select: $name");
    }
  }

  if (chanter_option('log_clear')) {
    foreach (glob(AETHER_LOGS.'/*') as $log) {
      unlink($log);
    }
    whisper_nl("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Logs cleared.");
    // logs
    aether_log("grimoire --log_clear");
  }


  /* ARCANE
   *
   *  */
  if (chanter_option('arcane')) {
    whisper_nl(
      Weaver::load(__DIR__ . '/weaver/grimoire-arcane-concept.txt')
    );
  }


});