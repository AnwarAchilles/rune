<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;

// artefact
Chanter::set('artefact', function() {

  $header = Weaver::load(__DIR__ . '/weaver/artefact-header.txt');
  $header = Weaver::bindAll($header, [
    'AETHER-FILE'=> AETHER_FILE,
  ]);

  if (aether_has_entity('whisper')) {
    whisper_clear();
    whisper_nl($header);
  }else {
    aether_whisper($header);
  }

  if (chanter_option('invoke')) {

    $runefile = AETHER_FILE . '.rune';
    $template = '';
    $template .= 'RUNE ARTEFACT - ' . AETHER_VERSION . PHP_EOL;
    $template .= 'created at ' . date('Y-m-d H:i:s') . PHP_EOL;
    $template .= PHP_EOL.PHP_EOL;

    $template .= cipher_encode(cipher_base64(forger_get(AETHER_FILE))) . PHP_EOL.PHP_EOL;
    
    forger_file($runefile);
    $items = forger_scan(AETHER_ECHOES_ARTEFACT, function($item) use ($runefile) {
      return forger_get($item->target);
    });
    $template .= implode(PHP_EOL, $items);
    
    forger_set($runefile, $template);

    whisper_nl("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Artefact successfully invoked.");
  }

  if (chanter_option('revoke')) {
    whisper_nl("{{COLOR-INFO}}{{ICON-INFO}}{{LABEL-INFO}}You will revoke the artefact, Where the artefact?");
    $link = whisper_input('File location: ');
    if ($link) {
      chanter_cast('{{SELF}} artefact --revoke_option=' . $link);
      whisper_nl("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Artefact successfully revoked.");
    }
  }

  if (chanter_option('revoke_option')) {
    $link = chanter_option('revoke_option');
    if ($link) {
      $target = str_replace('.rune', '', $link);
      $file = forger_file($link);
      $part = explode(PHP_EOL.PHP_EOL, $file);

      $base = cipher_base64(cipher_decode($part[1]), true);
      forger_file($target);
      forger_set($target, $base);

      $code = explode(PHP_EOL, $part[2]);
      foreach ($code as $row) {
        $row = json_decode(cipher_base64(cipher_decode($row), true));

        foreach ($row->items as $item) {
          $source = cipher_base64($item->source, true);
          forger_folder_all(AETHER_REPO . $item->dirname);
          forger_file(AETHER_REPO . $item->target);
          forger_set(AETHER_REPO . $item->target, $source);
        }
      }      
    }
  }

  if (chanter_option('result')) {
    $result = [];
    $no = 1;
    whisper_nl('{{COLOR-INFO}} Your artefact shard is:');
    foreach (glob(AETHER_ECHOES_ARTEFACT . '/*.rune') as $file) {
      $time = filemtime($file);
      $file = pathinfo($file);
      $file['timestamp'] = date('Y-m-d H:i:s', $time);
      whisper_nl('['.$no.'] ' . $file['basename'] . ' - ' . date('Y-m-d H:i:s', $time));
      $result[] = $file;
      $no++;
    }
    whisper_nl('');
    keeper_set('shards.json', json_encode($result));
  }

  if (chanter_option('remove')) {
    $target = AETHER_ECHOES_ARTEFACT . '/' . chanter_option('remove_process');
    if (file_exists($target)) {
      unlink($target);
      whisper_nl("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Artefact successfully removed.");
    }
  }



});