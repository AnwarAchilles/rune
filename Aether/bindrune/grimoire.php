<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Keeper\Manifest as Keeper;

// grimoire
Chanter::cast('grimoire', function() {
  global $AETHER_ARISED;

  (aether_has_entity('whisper')) ?: die(PHP_EOL.'[!]WARNING: Required Whisper:entity'.PHP_EOL);

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

  $header = Weaver::item(__DIR__ . '/weaver/grimoire-header.txt');
  $footer = Weaver::item(__DIR__ . '/weaver/grimoire-footer.txt');
  $header = Weaver::bind($header, [
    'AETHER-FILE'=> AETHER_FILE
  ]);

  // Whisper::clear();
  // Whisper::emit($header);
  // Whisper::emit("");

  Whisper::clear()::emit($header);



  /* Rune
   * todo get rune
   *  */
  $processing_get_all_rune = function() use ($default_list) {
    $manifests = [];
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
    return $manifests;
  };
  $processing_get_rune = function( $rune ) use ($default_list) {
    $manifest = 'Rune\\' . $rune . '\\Manifest';
    $phantasm = 'Rune\\' . $rune . '\\Phantasm';
    $phantasm = new $phantasm();
    $phantasm->list = array_merge($default_list, $phantasm->list);

    Whisper::emit("{{tab}}{{COLOR-PRIMARY}}{{ICON-PRIMARY}} $phantasm->main {{nl}}");
    Whisper::emit("{{tab}}-{{tab}}{{COLOR-INFO}}v$phantasm->version {{nl}}");
    if ($phantasm->mark !== 'VOID') {
      Whisper::emit("{{tab}}{{tab}}{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}This rune is marked as $phantasm->mark.{{nl}}");
    }
    Whisper::emit("{{tab}}{{tab}}{{COLOR-SECONDARY}}[M] $manifest {{nl}}");
    Whisper::emit("{{tab}}{{tab}}{{COLOR-SECONDARY}}[U] $phantasm->user {{nl}}");
    Whisper::emit("{{tab}}{{tab}}{{COLOR-SECONDARY}}[N] $phantasm->note {{nl}}");

    // check if rune is tandalone or need
    Whisper::emit("{{tab}}{{tab}}{{COLOR-INFO}}Need of Rune: {{nl}}");
    if (count($phantasm->need) == 0) {
      Whisper::emit("{{tab}}{{tab}}{{COLOR-SECONDARY}} (THIS RUNE IS STANDALONE) {{nl}}");
    }
    foreach ($phantasm->need as $need) {
      Whisper::emit("{{tab}}{{tab}}");
      Whisper::emit("{{tab}}{{COLOR-DEFAULT}}$need[0]");
      Whisper::emit("{{tab}}{{COLOR-DANGER}}$need[1]");
      Whisper::emit("{{tab}}{{COLOR-SECONDARY}}v$need[2]^");
      Whisper::emit("{{nl}}");
    }
    
    // list of phantasm
    Whisper::emit("{{tab}}{{tab}}{{COLOR-INFO}}List of Phantasm: {{nl}}");
    foreach ($phantasm->list as $list) {
      $list = (object) $list;
      Whisper::emit("{{tab}}{{tab}}");
      Whisper::emit("{{tab}}{{COLOR-DANGER}}$list->type");
      Whisper::emit("{{tab}}{{COLOR-DEFAULT}}$list->call");
      Whisper::emit("{{tab}}{{COLOR-SECONDARY}}$list->note");
      Whisper::emit("{{nl}}");
    }

    return $phantasm;
  };
  if (Chanter::spell('rune')) {
    if (Chanter::spell('rune') !== '1') {
      $input = Chanter::spell('rune');
    }else {
      $input = Whisper::reap('Give us the rune name: ');
    }
    if ($input) {
      Whisper::clear();
      $processing_get_rune( $input );
    }
    Whisper::emit($footer);
  }


  /* Runes
   * todo result all rune
   *  */
  if (Chanter::spell('runes')) {
    Whisper::clear();
    $list = $processing_get_all_rune();
    $keeper_runes = [];
    foreach ($list as $rune) {
      $rune = str_replace('Rune\\', '', $rune[0]);
      $rune = str_replace('\\Manifest', '', $rune);
      $keeper_runes[] = $processing_get_rune( $rune );

      Whisper::emit("{{nl}}");
    }
    
    if (aether_has_entity('keeper')) {
      Keeper::item('runes', $keeper_runes);
    }

    Whisper::emit($footer);
  }


  /* ARCANE
   * todo get current logs/arcane
   *  */
  if (Chanter::spell('arcane')) {
    Whisper::clear();
    aether_arcane_pretty_print();
  }



  // bottom is deprecated

  
  /* BASE
   *
   *  */
  if (Chanter::spell('all')) {
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
      
      Whisper::emit("{{tab}}");
      Whisper::emit("{{COLOR-PRIMARY}}{{ICON-PRIMARY}} $x->main");
      Whisper::emit("{{tab}}-{{tab}}{{COLOR-INFO}}v$x->version");
      Whisper::emit("");
      if ($x->mark) {
        Whisper::emit("{{tab}}{{tab}}{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}This rune is marked as $x->mark.");
      }
      Whisper::emit("{{tab}}{{tab}}{{COLOR-SECONDARY}}[M] $manifest");
      Whisper::emit("{{tab}}{{tab}}{{COLOR-SECONDARY}}[O] $x->origin");
      Whisper::emit("{{tab}}{{tab}}{{COLOR-SECONDARY}}[U] $x->user");
      Whisper::emit("{{tab}}{{tab}}{{COLOR-SECONDARY}}[N] $x->note");
      
      Whisper::emit("{{tab}}{{tab}}{{COLOR-INFO}}Need of Rune:");
      if (count($x->need) == 0) {
        Whisper::emit("{{tab}}{{tab}}{{tab}}{{COLOR-SECONDARY}} (THIS RUNE IS STANDALONE)");
      }
      foreach ($x->need as $need) {
        Whisper::emit("{{tab}}{{tab}}{{tab}}{{COLOR-DEFAULT}} $need[0] {{COLOR-DANGER}}$need[1] {{COLOR-SECONDARY}}v$need[2]^");
      }
      
      Whisper::emit("{{tab}}{{tab}}{{COLOR-INFO}}List of Phantasm:");
      foreach ($phantasm->list as $list) {
        $list = (object) $list;
        Whisper::emit("{{tab}}{{tab}}");
        Whisper::emit("{{tab}}{{COLOR-DANGER}}$list->type");
        Whisper::emit("{{tab}}{{COLOR-DEFAULT}}$list->call");
        Whisper::emit("{{tab}}{{COLOR-SECONDARY}}$list->note");
        Whisper::emit("{{tab}}");
      }
  
      $keeper_runes[] = $x;
      Whisper::emit("");
    }
    
    keeper_set('runes.json', json_encode($keeper_runes));
  }
  
  if (Chanter::spell('one')) {
    $input = Whisper::reap('Give us the rune name: ');
    if ($input) {
      echo shell_exec("php " . AETHER_FILE . " grimoire --select=" . $input);
    }
  }

  if (Chanter::spell('rune_select')) {
    $rune = Chanter::spell('select');
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
    
    Whisper::emit("{{tab}}");
    Whisper::emit("{{COLOR-PRIMARY}}{{ICON-PRIMARY}} $x->main");
    Whisper::emit("{{tab}}-{{tab}}{{COLOR-INFO}}v$x->version");
    Whisper::emit("");
    if ($x->mark) {
      Whisper::emit("{{tab}}{{tab}}{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}This rune is marked as $x->mark.");
    }
    Whisper::emit("{{tab}}{{tab}}{{COLOR-SECONDARY}}[M] $manifest");
    Whisper::emit("{{tab}}{{tab}}{{COLOR-SECONDARY}}[U] $x->user");
    Whisper::emit("{{tab}}{{tab}}{{COLOR-SECONDARY}}[N] $x->note");

    Whisper::emit("{{tab}}{{tab}}{{COLOR-INFO}}Need of Rune:");
    if (count($x->need) == 0) {
      Whisper::emit("{{tab}}{{tab}}{{tab}}{{COLOR-SECONDARY}} (THIS RUNE IS STANDALONE)");
    }
    foreach ($x->need as $need) {
      Whisper::emit("{{tab}}{{tab}}{{tab}}{{COLOR-DEFAULT}} $need[0] {{COLOR-DANGER}}$need[1] {{COLOR-SECONDARY}}v$need[2]^");
    }
      
    Whisper::emit("{{tab}}{{tab}}{{COLOR-INFO}}List of Phantasm:");
    foreach ($phantasm->list as $list) {
      $list = (object) $list;
      Whisper::emit("{{tab}}{{tab}}");
      Whisper::emit("{{tab}}{{COLOR-DANGER}}$list->type");
      Whisper::emit("{{tab}}{{COLOR-DEFAULT}}$list->call");
      Whisper::emit("{{tab}}{{COLOR-SECONDARY}}$list->note");
      Whisper::emit("{{tab}}");
    }

    Whisper::emit("");
  }


  /* LOGS
   * todo list & show logs user interact to
   *  */
  if (Chanter::spell('log')) {
    Whisper::clear();
    Whisper::emit("{{COLOR-PRIMARY}}Choose a rune to log");

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
        Whisper::emit("[$id]: $list[name] (current)");
      }else {
        Whisper::emit("[$id]: $list[name]");
      }
    }

    $select = Whisper::reap('Select number: ');
    if ($select) {
      $name = $lists[$select-1]['name'];
      $path = $lists[$select-1]['path'];
  
      $result = file_get_contents($path);
      $result = str_replace('[', '{{COLOR-SECONDARY}}[', $result);
      $result = str_replace(']', ']{{COLOR-DEFAULT}}', $result);
      $result = str_replace('START RUNE', '{{COLOR-DANGER}}START RUNE{{COLOR-DEFAULT}}', $result);
      Whisper::emit($result);
      Whisper::emit("");
      
      // logs
      aether_log("grimoire --log: $name");
    }
  }

  if (Chanter::spell('log_current')) {
    $name = date('Y-m-d');
    echo chanter_cast("{{SELF}} grimoire --log_select=$name");
    
    // logs
    aether_log("grimoire --log_current: $name");
  }

  if (Chanter::spell('log_select')) {
    $select = Chanter::spell('log_select');
    if ($select) {
      $name = $select;
      $path = AETHER_LOGS . '/' . $select . '.txt';
  
      $result = file_get_contents($path);
      $result = str_replace('[', '{{COLOR-SECONDARY}}[', $result);
      $result = str_replace(']', ']{{COLOR-DEFAULT}}', $result);
      $result = str_replace('START RUNE', '{{COLOR-DANGER}}START RUNE{{COLOR-DEFAULT}}', $result);
      Whisper::emit($result);
      Whisper::emit("");
      
      // logs
      aether_log("grimoire --log_select: $name");
    }
  }

  if (Chanter::spell('log_clear')) {
    foreach (glob(AETHER_LOGS.'/*') as $log) {
      unlink($log);
    }
    Whisper::emit("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Logs cleared.");
    // logs
    aether_log("grimoire --log_clear");
  }


  
});