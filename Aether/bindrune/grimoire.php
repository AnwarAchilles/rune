<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Forger\Manifest as Forger;
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
  // Whisper::echo($header);
  // Whisper::echo("");

  Whisper::clear()::echo($header);



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

    $runename = strtoupper(implode(' ', str_split($phantasm->main)));
    $note = weaver_wrap_echo($phantasm->note, 50, '{{tab}}');
    Whisper::echo("{{color-danger}}::{{color-end}} $runename — v$phantasm->version {{nl}}");
    Whisper::echo("{{color-secondary}}$note {{nl}}{{nl}}");
    if ($phantasm->mark !== 'VOID') {
      Whisper::echo("{{tab}}{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}This rune is marked as $phantasm->mark.{{nl}}");
    }
    Whisper::echo("{{tab}}{{color-default}}[M]{{color-secondary}} $manifest {{nl}}");
    Whisper::echo("{{tab}}{{color-default}}[O]{{color-secondary}} $phantasm->origin {{nl}}");
    Whisper::echo("{{tab}}{{color-default}}[U]{{color-secondary}} $phantasm->user {{nl}}");

    // check if rune is tandalone or need
    Whisper::echo("\n{{tab}}{{color-danger}}::{{color-end}}N E E D {{nl}}");
    if (count($phantasm->need) == 0) {
      Whisper::echo("{{tab}}{{tab}}{{color-info}} (THIS RUNE IS STANDALONE) {{nl}}");
    }
    foreach ($phantasm->need as $need) {
      Whisper::echo("{{tab}}{{tab}}{{COLOR-DEFAULT}}$need[0]");
      Whisper::echo("{{tab}}{{COLOR-DANGER}}$need[1]");
      Whisper::echo("{{tab}}{{COLOR-SECONDARY}}v$need[2]^");
      Whisper::echo("{{nl}}");
    }
    
    // list of phantasm
    Whisper::echo("\n{{tab}}{{color-danger}}::{{color-end}}L I S T {{nl}}");
    foreach ($phantasm->list as $list) {
      $list = (object) $list;
      $list_note = weaver_wrap_echo($list->note, 50, "{{tab}}{{tab}}{{tab}}");
      if (empty($list->note)) {
        $list_note = "{{tab}}{{tab}}{{tab}}no note";
      }
      $list_color = '{{color-default}}';
      if ($list->type=='manifest') {
        $list_color = '{{color-danger}}';
      }
      if ($list->type=='ether') {
        $list_color = '{{color-primary}}';
      }
      if ($list->type=='essence') {
        $list_color = '{{color-warning}}';
      }
      if ($list->type=='entity') {
        $list_color = '{{color-info}}';
      }
      Whisper::echo("{{tab}}{{tab}}{$list_color}{$list->type}");
      Whisper::echo("\n{{tab}}{{tab}}{{COLOR-DEFAULT}}$list->call");
      Whisper::echo("\n{{COLOR-SECONDARY}}$list_note");
      Whisper::echo("{{nl}}");
    }

    return $phantasm;
  };
  if (Chanter::spell('rune')) {
    if (Chanter::spell('rune') !== '1') {
      $input = Chanter::spell('rune');
    }else {
      $input = Whisper::call('Give us the rune name: ');
    }
    if ($input) {
      Whisper::clear(true);
      $processing_get_rune( $input );
    }
    Whisper::echo($footer);
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

      Whisper::echo("{{nl}}");
    }
    
    if (aether_has_entity('keeper')) {
      Keeper::item('grimoire', $keeper_runes);
    }

    Whisper::echo($footer);
  }


  /* ARCANE
   * todo get current logs/arcane
   *  */
  $processing__arcane = function() {
    $file = Forger::item(KEEPER_ECHOES_ARCANE);
    $file = explode(PHP_EOL, $file);

    global $KEEPER_ARCANE;
    $sv = $KEEPER_ARCANE;
    $sv[0][2] = "{{color-success}}";
    $sv[1][2] = "{{color-success}}";
    $sv[2][2] = "{{color-info}}";
    $sv[3][2] = "{{color-primary}}";
    $sv[4][2] = "{{color-warning}}";
    $sv[5][2] = "{{color-danger}}";
    $sv[6][2] = "{{color-danger}}";
    $resv = [];
    foreach ($sv as $row) {
      $resv[$row[1]] = $row[2];
    }
    $sv = $resv;

    $no = 0;
    $list_state = [];
    $list_step = [];
    $list_manifest = [];
    $list_entity = [];
    foreach ($file as $row) {
      if (!empty($row)) {
        $arcane = explode(' - ', $row);

        $datetime = str_replace(']', '', str_replace('[', '', $arcane[0]));
        $stopwatch = str_replace(']', '', str_replace('[', '', $arcane[1]));
        $stepwatch = str_replace(']', '', str_replace('[', '', $arcane[2]));
        $title = $arcane[3];
        $state = str_replace('//', '', $arcane[5]);
        
        $list_state[] = $state;
        $list_step[] = $stepwatch;

        if (strpos($title,'manifest')!==false) {
          $title_prefix = "{{color-danger}}ϻ| ";
          $title = str_replace('manifest', '{{color-danger}}manifest', $title);
          $list_manifest[] = explode(':', $title)[0];
        }else if (strpos($title,'entity')!==false) {
          $title_prefix = "{{color-info}}ͱ| ";
          $title = str_replace('entity', '{{color-info}}entity', $title);
          $list_entity[] = explode(':', $title)[0];
        }

        $datetime_end = "{{color-primary}}λ{{color-secondary}}$datetime";
        $stopwatch_end = "{{color-warning}}ϟ{{color-secondary}}{$stopwatch}s";
        $stepwatch_end = "{{color-danger}}ϟ{{color-secondary}}{$stepwatch}s";
        $title_end = "$title_prefix{{color-default}}$title";
        $state_end = "{$sv[$state]}.::{$state}::.";
        
        Whisper::echo("{{color-secondary}}________________________________________________________________________ _____ ___ __ __ _ _{{nl}}");
        Whisper::echo("{{color-default}} $no | $datetime_end $stopwatch_end $stepwatch_end   $state_end   $title_end $arcane[4] {{nl}}");
      }
      $no++;
    }

    $total_state = array_count_values($list_state);
    $total_manifest = count(array_count_values($list_manifest));
    $total_entity = count(array_count_values($list_entity));
    $average_step = number_format(array_sum($list_step) / count($list_step), 5);
    $peak_step = max($list_step);
    
    Whisper::echo("\n{{color-secondary}}CURRENT ARCANE STATS:");
    Whisper::echo("\n{{color-secondary}}{{icon-info}}labels states up to: ");
    foreach ($KEEPER_ARCANE as $data) {
      Whisper::echo("{{color-secondary}}$data[0]s=$data[1], ");
    }
    
    Whisper::echo("\n{{tab}}Execute: \tProcess = {{color-danger}}{$no}{{color-end}}, End in = {{color-danger}}{$stopwatch}{{color-end}}s");
    Whisper::echo("\n{{tab}}Module: \tManifest = {{color-danger}}{$total_manifest}{{color-end}}, Entity = {{color-danger}}{$total_entity}{{color-end}}");
    Whisper::echo("\n{{tab}}Stepwatch: \tAverage = {{color-danger}}{$average_step}{{color-end}}s, Up to = {{color-danger}}{$peak_step}{{color-end}}s");
    Whisper::echo("\n{{tab}}State: \t");
    foreach ($total_state as $ts_key=>$ts_val) {
      Whisper::echo("$ts_key = {{color-danger}}$ts_val{{color-end}}, ");
    }
    
    Whisper::echo("\n\n");
  };
  if (Chanter::spell('arcane')) {
    Whisper::clear();

    aether_arcane_disable();
    
    $processing__arcane();
  }

  if (Chanter::spell('arcane-clean')) {
    forger_clean(KEEPER_ECHOES_ARCANES, true);
    Whisper::echo("{{color-success}}{{icon-success}}{{label-success}}Cleaned arcane echoes");
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
      
      Whisper::echo("{{tab}}");
      Whisper::echo("{{COLOR-PRIMARY}}{{ICON-PRIMARY}} $x->main");
      Whisper::echo("{{tab}}-{{tab}}{{COLOR-INFO}}v$x->version");
      Whisper::echo("");
      if ($x->mark) {
        Whisper::echo("{{tab}}{{tab}}{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}This rune is marked as $x->mark.");
      }
      Whisper::echo("{{tab}}{{tab}}{{COLOR-SECONDARY}}[M] $manifest");
      Whisper::echo("{{tab}}{{tab}}{{COLOR-SECONDARY}}[O] $x->origin");
      Whisper::echo("{{tab}}{{tab}}{{COLOR-SECONDARY}}[U] $x->user");
      Whisper::echo("{{tab}}{{tab}}{{COLOR-SECONDARY}}[N] $x->note");
      
      Whisper::echo("{{tab}}{{tab}}{{COLOR-INFO}}Need of Rune:");
      if (count($x->need) == 0) {
        Whisper::echo("{{tab}}{{tab}}{{tab}}{{COLOR-SECONDARY}} (THIS RUNE IS STANDALONE)");
      }
      foreach ($x->need as $need) {
        Whisper::echo("{{tab}}{{tab}}{{tab}}{{COLOR-DEFAULT}} $need[0] {{COLOR-DANGER}}$need[1] {{COLOR-SECONDARY}}v$need[2]^");
      }
      
      Whisper::echo("{{tab}}{{tab}}{{COLOR-INFO}}List of Phantasm:");
      foreach ($phantasm->list as $list) {
        $list = (object) $list;
        Whisper::echo("{{tab}}{{tab}}");
        Whisper::echo("{{tab}}{{COLOR-DANGER}}$list->type");
        Whisper::echo("{{tab}}{{COLOR-DEFAULT}}$list->call");
        Whisper::echo("{{tab}}{{COLOR-SECONDARY}}$list->note");
        Whisper::echo("{{tab}}");
      }
  
      $keeper_runes[] = $x;
      Whisper::echo("");
    }
    
    keeper_set('runes.json', json_encode($keeper_runes));
  }
  
  if (Chanter::spell('one')) {
    $input = Whisper::call('Give us the rune name: ');
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
    
    Whisper::echo("{{tab}}");
    Whisper::echo("{{COLOR-PRIMARY}}{{ICON-PRIMARY}} $x->main");
    Whisper::echo("{{tab}}-{{tab}}{{COLOR-INFO}}v$x->version");
    Whisper::echo("");
    if ($x->mark) {
      Whisper::echo("{{tab}}{{tab}}{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}This rune is marked as $x->mark.");
    }
    Whisper::echo("{{tab}}{{tab}}{{COLOR-SECONDARY}}[M] $manifest");
    Whisper::echo("{{tab}}{{tab}}{{COLOR-SECONDARY}}[U] $x->user");
    Whisper::echo("{{tab}}{{tab}}{{COLOR-SECONDARY}}[N] $x->note");

    Whisper::echo("{{tab}}{{tab}}{{COLOR-INFO}}Need of Rune:");
    if (count($x->need) == 0) {
      Whisper::echo("{{tab}}{{tab}}{{tab}}{{COLOR-SECONDARY}} (THIS RUNE IS STANDALONE)");
    }
    foreach ($x->need as $need) {
      Whisper::echo("{{tab}}{{tab}}{{tab}}{{COLOR-DEFAULT}} $need[0] {{COLOR-DANGER}}$need[1] {{COLOR-SECONDARY}}v$need[2]^");
    }
      
    Whisper::echo("{{tab}}{{tab}}{{COLOR-INFO}}List of Phantasm:");
    foreach ($phantasm->list as $list) {
      $list = (object) $list;
      Whisper::echo("{{tab}}{{tab}}");
      Whisper::echo("{{tab}}{{COLOR-DANGER}}$list->type");
      Whisper::echo("{{tab}}{{COLOR-DEFAULT}}$list->call");
      Whisper::echo("{{tab}}{{COLOR-SECONDARY}}$list->note");
      Whisper::echo("{{tab}}");
    }

    Whisper::echo("");
  }


  /* LOGS
   * todo list & show logs user interact to
   *  */
  if (Chanter::spell('log')) {
    Whisper::clear();
    Whisper::echo("{{COLOR-PRIMARY}}Choose a rune to log");

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
        Whisper::echo("[$id]: $list[name] (current)");
      }else {
        Whisper::echo("[$id]: $list[name]");
      }
    }

    $select = Whisper::call('Select number: ');
    if ($select) {
      $name = $lists[$select-1]['name'];
      $path = $lists[$select-1]['path'];
  
      $result = file_get_contents($path);
      $result = str_replace('[', '{{COLOR-SECONDARY}}[', $result);
      $result = str_replace(']', ']{{COLOR-DEFAULT}}', $result);
      $result = str_replace('START RUNE', '{{COLOR-DANGER}}START RUNE{{COLOR-DEFAULT}}', $result);
      Whisper::echo($result);
      Whisper::echo("");
      
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
      Whisper::echo($result);
      Whisper::echo("");
      
      // logs
      aether_log("grimoire --log_select: $name");
    }
  }

  if (Chanter::spell('log_clear')) {
    foreach (glob(AETHER_LOGS.'/*') as $log) {
      unlink($log);
    }
    Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Logs cleared.");
    // logs
    aether_log("grimoire --log_clear");
  }


  
});