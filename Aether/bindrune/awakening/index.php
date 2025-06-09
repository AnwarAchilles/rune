<?php

// RUNE:INITIALIZE
use Rune\Aether\Manifest as Aether;
use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Keeper\Manifest as Keeper;
use Rune\Minister\Manifest as Minister;
use Rune\Forger\Manifest as Forger;
use Rune\Crafter\Manifest as Crafter;
use Rune\Cipher\Manifest as Cipher;
use Rune\Specter\Manifest as Specter;

// RUNE:INSTANCE
Aether::arise();
Chanter::arise();
Weaver::arise();
Whisper::arise();
Forger::arise();
Crafter::arise();
Keeper::arise();
Minister::arise();
Cipher::arise();
Specter::arise();


// RUNE:AWAKENING
Aether::origin();

Chanter::set('awakening', function() {
  whisper_clear();
  whisper_nl('');
  whisper_nl('{{COLOR-DANGER}} RUNE AWAKENING');
  whisper_nl('{{COLOR-SECONDARY}} awaken the rune from the void...');

  $rune = (object) [
    'act'=> '',
    'main'=> '',
    'asset'=> [],
  ];
  $processing = function( $rune, $timing ) {
    $target = forger_file(AETHER_REPO.'/'.AETHER_FILE);

    $rune->act = weaver_bind($rune->act, 'file', AETHER_FILE);

    $target = weaver_bind_custom($target, 'use Rune\Aether\Manifest as Aether;', '');
    $target = weaver_bind_custom($target, PHP_EOL.PHP_EOL, PHP_EOL);
    $target = weaver_bind_custom($target, '<?php', $rune->act);
    $target = weaver_bind_custom($target, 'Aether::awakening();', $rune->main);

    forger_set( AETHER_REPO.'/'.AETHER_FILE, $target);
    foreach ($rune->asset as $asset) {
      forger_folder_clone($asset['location'], $asset['destination']);
    }

    whisper_loader(function($loader) {
      whisper_il("{{COLOR-DANGER}}[$loader] A W A K E N I N G ");
    },[
      'speed' => 100,
      'delay' => $timing,
    ]);
    whisper_clear();
    whisper_clear_force();
    whisper_il("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} A W A K E N I N G ");
  };

  // check minimum requirement
  if (!version_compare(PHP_VERSION, AETHER_PHP_VERSION, '>=')) {
    whisper_nl('{{COLOR-ERROR}}{{ICON-ERROR}} Need PHP version '.AETHER_PHP_VERSION.' or higher required.');
    exit;
  }
  
  // start stages
  sleep(1);

  // without kit
  whisper_clear();
  whisper_nl('');
  whisper_nl('{{COLOR-INFO}}Want tou choose another kit?');
  if (whisper_input('Enter your answer [y/n]: ') !== 'y') {
    $rune->act = forger_get( __DIR__ . '/kit/D-starter/rune.act.txt');
    $rune->main = forger_get( __DIR__ . '/kit/D-starter/rune.txt');
    $processing( $rune, 2000 );
    exit;
  }
  
  // choose kit
  whisper_clear();
  whisper_nl('{{COLOR-INFO}}Choose kit you want to use.');
  whisper_nl('');
  $kit_list = [];
  whisper_nl('{{COLOR-SECONDARY}}[ID] NAME');
  foreach (glob(__DIR__.'/kit/*') as $row) {
    if (is_dir($row)) {
      $data = explode('-', basename($row));
      $ID = strtoupper($data[0]);
      $name = $data[1];
      $default = ($ID=='D') ? ' <- current' : '';
      whisper_nl("[$ID] $name {{COLOR-SUCCESS}}$default{{COLOR-DEFAULT}}");
      $kit_list[$ID] = $row;
    }
  }
  whisper_nl('');
  
  // processing kit
  $kit_input = whisper_input('Enter kit ID: ');
  if ($kit_input) {
    $kit_input = (empty($kit_input)) ? 'D' : $kit_input;
    
    if (!isset($kit_list[strtoupper($kit_input)])) {
      whisper_nl('{{COLOR-ERROR}}{{ICON-ERROR}} Template not found');
      exit;
    }
    
    $kit_select = $kit_list[strtoupper($kit_input)];
    $rune->act = forger_get($kit_select . '/rune.act.txt');
    $rune->main = forger_get($kit_select . '/rune.txt');
    foreach (glob($kit_select . '/*') as $row) {
      if (is_dir($row)) {
        $rune->asset[] = [
          'location'=> $row,
          'destination'=> AETHER_REPO . '/' . basename($row),
        ];
      }
    }

    $processing( $rune, 5000 );
  }
  
  
  whisper_clear();



  
});

// Chanter::set('start', function() {
//   Specter::open("{{SELF}} deploy", [
//     'visible'=> false,
//   ]);

//   whisper_loader(function($loader) {
//     whisper_il("{{COLOR-DANGER}}[$loader] A W A K E N I N G ");
//   }, [
//     'speed' => 100,
//     'infinite' => function() {
//       return Specter::get("{{SELF}} deploy");
//     }
//   ]);
// });

// Chanter::set('deploy', function() {
//   for ($index = 1; $index <= 3; $index++) {
//     sleep(1);
//     if ($index == 3) {
//       Specter::close("{{SELF}} deploy");
//       exit;
//     }
//   }
// });

// Chanter::set('tester', function() {
//   file_put_contents(__DIR__.'/trash.txt', AETHER_FILE);
//   exit;
// });

// if (isset($CHANTER_ARGS[1])) {
// }else {}

Chanter::get('awakening')();