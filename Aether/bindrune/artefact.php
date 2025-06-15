<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;

// artefact
Chanter::cast('artefact', function() {

  $header = Weaver::item(__DIR__ . '/weaver/artefact-header.txt');
  $header = Weaver::bind($header, [
    'AETHER-FILE'=> AETHER_FILE,
  ]);

  if (aether_has_entity('whisper')) {
    Whisper::clear();
    Whisper::emit($header);
  }else {
    aether_whisper($header);
  }


  /* INVOKE
   *  */
  $processing_invoke = function() {
    $runefile = AETHER_FILE . '.rune';
    $template = '';
    $template .= 'RUNE ARTEFACT - ' . AETHER_VERSION . PHP_EOL;
    $template .= 'created at ' . date('Y-m-d H:i:s') . PHP_EOL;
    $template .= PHP_EOL.PHP_EOL;

    $template .= cipher_encode(cipher_base64(forger_get(AETHER_FILE))) . PHP_EOL.PHP_EOL;
    
    forger_file($runefile);
    
    forger_folder_all(AETHER_ECHOES_ARTEFACT);
    $items = forger_scan(AETHER_ECHOES_ARTEFACT, function($item) use ($runefile) {
      return forger_get($item->target);
    });
    $template .= implode(PHP_EOL, $items);
    
    forger_set($runefile, $template);

    Whisper::clear()::emit("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Artefact successfully invoked.{{nl}}");
  };
  if (Chanter::spell('invoke')) {
    $processing_invoke();
  }


  /* REVOKE
   *  */
  $processing_revoke = function( $link ) {
    $target = str_replace('.rune', '', $link);
    $file = forger_get($link);
    $part = explode(PHP_EOL.PHP_EOL, $file);

    
    $base = cipher_base64(cipher_decode($part[1]), true);
    forger_file($target);
    forger_set($target, $base);
    
    $code = (!empty($part[2])) ? explode(PHP_EOL, $part[2]) : [];
    foreach ($code as $row) {
      $row = json_decode(cipher_base64(cipher_decode($row), true));

      foreach ($row->items as $item) {
        $source = cipher_base64($item->source, true);
        forger_folder_all(AETHER_REPO . $item->dirname);
        forger_file(AETHER_REPO . $item->target);
        forger_set(AETHER_REPO . $item->target, $source);
      }
    }

    Whisper::clear()::emit("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Artefact successfully revoked. {{nl}}");
  };
  if (Chanter::spell('revoke')) {
    Whisper::clear();
    if (Chanter::spell('revoke') !== '1') {
      $link = Chanter::spell('revoke');
    }else {
      Whisper::emit("{{COLOR-SECONDARY}}{{ICON-INFO}}{{LABEL-INFO}}You will revoke the artefact, Where the artefact?{{nl}}");
      $link = Whisper::reap('File location: ');
    }
    if ($link) {
      $processing_revoke($link);
    }
  }
  // if (Chanter::spell('revoke_option')) {
  //   $link = Chanter::spell('revoke_option');
  //   if ($link) {
  //     $target = str_replace('.rune', '', $link);
  //     $file = forger_file($link);
  //     $part = explode(PHP_EOL.PHP_EOL, $file);

  //     $base = cipher_base64(cipher_decode($part[1]), true);
  //     forger_file($target);
  //     forger_set($target, $base);

  //     $code = explode(PHP_EOL, $part[2]);
  //     foreach ($code as $row) {
  //       $row = json_decode(cipher_base64(cipher_decode($row), true));

  //       foreach ($row->items as $item) {
  //         $source = cipher_base64($item->source, true);
  //         forger_folder_all(AETHER_REPO . $item->dirname);
  //         forger_file(AETHER_REPO . $item->target);
  //         forger_set(AETHER_REPO . $item->target, $source);
  //       }
  //     }      
  //   }
  // }




  if (Chanter::spell('shards')) {
    $result = [];
    $no = 1;
    Whisper::emit('{{COLOR-INFO}} Your artefact shard is: {{nl}}');
    foreach (glob(AETHER_ECHOES_ARTEFACT . '/*.rune') as $file) {
      $time = filemtime($file);
      $file = pathinfo($file);
      $file['timestamp'] = date('Y-m-d H:i:s', $time);
      Whisper::emit('['.$no.'] ' . $file['basename'] . ' - ' . date('Y-m-d H:i:s', $time) . ' {{nl}}');
      $result[] = $file;
      $no++;
    }
    keeper_set('shards.json', json_encode($result));
  }

  if (Chanter::spell('remove')) {
    $target = AETHER_ECHOES_ARTEFACT . '/' . Chanter::spell('remove_process');
    if (file_exists($target)) {
      unlink($target);
      Whisper::emit("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Artefact successfully removed.");
    }
  }



});