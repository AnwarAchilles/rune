<?php

// RUNE:INITIALIZE
use Rune\Aether\Manifest as Aether;
use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Forger\Manifest as Forger;
use Rune\Cipher\Manifest as Cipher;

// RUNE:INSTANCE
Aether::arise();
Chanter::arise();
Weaver::arise();
Whisper::arise();
Forger::arise();
Cipher::arise();


// RUNE:AWAKENING
Aether::origin();

Chanter::cast('awakening', function() {
  Whisper::clear();
  Whisper::emit('{{COLOR-DANGER}} RUNE AWAKENING {{nl}}');
  Whisper::emit('{{COLOR-SECONDARY}} awaken the rune from the void... {{nl}}');

  

  $rune = '';
  $processing = function( $rune, $timing ) {
    $target = Forger::item(AETHER_REPO.'/'.AETHER_FILE);
    $runefile = Forger::item(AETHER_REPO.'/'.AETHER_FILE.'.rune', $rune);
    
    aether_dd($target);

    $target = str_replace('use Rune\Aether\Manifest as Aether;', '', $target);
    $target = str_replace(PHP_EOL.PHP_EOL, PHP_EOL, $target);
    $target = str_replace('<?php', $rune->act, $target);
    $target = str_replace($act_void, '', $target);
    $target = str_replace('Aether::awakening();', $rune->main, $target);

    Forger::item( AETHER_REPO.'/'.AETHER_FILE, $target);
    foreach ($rune->asset as $asset) {
      Forger::clone($asset['location'], $asset['destination']);
    }

    Whisper::drain(function($loader) {
      Whisper::emit("{{COLOR-DANGER}}[$loader] A W A K E N I N G ");
    },[
      'speed' => 100,
      'delay' => $timing,
    ]);
    Whisper::clear();
    Whisper::clear_force();
    Whisper::emit("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} A W A K E N I N G ");
  };
  $processing_revoke = function( $from, $to ) {
    $prefix_newPage = PHP_EOL.'- - - - -'.PHP_EOL;
    $prefix_item = PHP_EOL;

    $target = $to;
    $file = Forger::item($from);
    $part = explode($prefix_newPage, $file);
    
    if (isset($part[1])) {
      $base = cipher_base64(cipher_decode($part[1]), true);
      Forger::item($target, $base);
    }
    
    $code = (!empty($part[2])) ? explode(PHP_EOL, $part[2]) : [];
    foreach ($code as $row) {
      $row = json_decode(cipher_base64(cipher_decode($row), true));

      foreach ($row->items as $item) {
        $source = cipher_base64($item->source, true);
        Forger::fix(Forger::trace((AETHER_REPO . $item->dirname)));
        Forger::item(AETHER_REPO . $item->target, $source);
      }
    }

    Whisper::clear()::emit("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Artefact successfully revoked. {{nl}}");
  };

  // check minimum requirement
  if (!version_compare(PHP_VERSION, AETHER_PHP_VERSION, '>=')) {
    Whisper::emit('{{COLOR-ERROR}}{{ICON-ERROR}} Need PHP version '.AETHER_PHP_VERSION.' or higher required.');
    exit;
  }

  
  // start stages
  sleep(1);
  
  // without kit
  Whisper::clear();
  Whisper::emit('you will choose rank D as default, {{nl}}Did you want to choose another rank?{{nl}}');
  if (Whisper::reap('Enter your answer [y/n]: ') !== 'y') {
    $rune = Forger::item( __DIR__ . '/kit/D/rune.php.rune');
    // $processing( $rune, 2000 );
    $processing_revoke(
      __DIR__ . '/kit/D/rune.php.rune',
      AETHER_REPO.'/'.AETHER_FILE
    );
    exit;
  }
  
  
  // choose kit
  Whisper::clear();
  Whisper::emit('{{COLOR-INFO}}Choose kit you want to use.');
  Whisper::emit('');
  $kit_list = [];
  Whisper::emit('{{COLOR-SECONDARY}}[ID] NAME');
  foreach (glob(__DIR__.'/kit/*') as $row) {
    if (is_dir($row)) {
      $data = explode('-', basename($row));
      $ID = strtoupper($data[0]);
      $name = $data[1];
      $default = ($ID=='D') ? ' <- current' : '';
      Whisper::emit("[$ID] $name {{COLOR-SUCCESS}}$default{{COLOR-DEFAULT}}");
      $kit_list[$ID] = $row;
    }
  }
  Whisper::emit('');
  
  // processing kit
  $kit_input = Whisper::reap('Enter kit ID: ');
  if ($kit_input) {
    $kit_input = (empty($kit_input)) ? 'D' : $kit_input;
    
    if (!isset($kit_list[strtoupper($kit_input)])) {
      Whisper::emit('{{COLOR-ERROR}}{{ICON-ERROR}} Template not found');
      exit;
    }
    
    $kit_select = $kit_list[strtoupper($kit_input)];
    $rune->act = Forger::item($kit_select . '/rune.act.txt');
    $rune->main = Forger::item($kit_select . '/rune.txt');
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
  
  
  Whisper::clear();



  
});

// Chanter::cast('start', function() {
//   Specter::open("{{SELF}} deploy", [
//     'visible'=> false,
//   ]);

//   Whisper::drain(function($loader) {
//     Whisper::emit("{{COLOR-DANGER}}[$loader] A W A K E N I N G ");
//   }, [
//     'speed' => 100,
//     'infinite' => function() {
//       return Specter::get("{{SELF}} deploy");
//     }
//   ]);
// });

// Chanter::cast('deploy', function() {
//   for ($index = 1; $index <= 3; $index++) {
//     sleep(1);
//     if ($index == 3) {
//       Specter::close("{{SELF}} deploy");
//       exit;
//     }
//   }
// });

// Chanter::cast('tester', function() {
//   file_put_contents(__DIR__.'/trash.txt', AETHER_FILE);
//   exit;
// });

// if (isset($CHANTER_ARGS[1])) {
// }else {}

Chanter::cast('awakening')();