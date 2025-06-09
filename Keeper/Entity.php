<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 *
 * Example:
 * function starter() {}
 */


function keeper() {
  // do nothing just for check
}

function keeper_set( String $source, $datas ) {
  forger_folder(AETHER_REPO . '/.echoes/');
  forger_file(AETHER_REPO . '/.echoes/' . $source);
  forger_set(AETHER_REPO . '/.echoes/' . $source, $datas);
}

function keeper_get( String $source ) {
  return forger_file(AETHER_REPO . '/.echoes/' . $source);
}

function keeper_has( String $source ) {
  return file_exists(AETHER_REPO . '/.echoes/' . $source);
}

function keeper_log( String $text ) {
  $text = '[' . date('Y-m-d H:i:s') . '] ' . $text;
  if (keeper_has('log.txt')) {
    $last = keeper_get('log.txt');
    $data = $last . PHP_EOL . $text;
    keeper_set('log.txt', $data);
  }else {
    keeper_set('log.txt', $text);
  }
}

function keeper_log_clear() {
  keeper_set('log.txt', '');
}









function keeper_rune_write( Array $rune_crafter )
  {
    $items = [];
    foreach ($rune_crafter['items'] as $row) {
      $row['source'] = cipher_base64($row['source']);
      $row['dirname'] = str_replace(AETHER_REPO, '', $row['dirname']);
      $row['target'] = str_replace(AETHER_REPO, '', $row['target']);
      $items[] = $row;
    }
    $rune_crafter['items'] = $items;
    $rune_crafter['maps']['repo'] = '/' . $rune_crafter['maps']['repo'];
    
    $name = $rune_crafter['maps']['name'];
    $target = AETHER_ECHOES_RUNES . $name . '.rune';
    forger_folder(AETHER_ECHOES_RUNES);
    forger_file($target);
    unset($rune_crafter['bases']['REPO']);

    $source = cipher_encode(cipher_base64(json_encode($rune_crafter)));
    forger_set($target, $source);
    whisper_nl("{{COLOR-INFO}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Keeper Rune '$name.rune' to '.echoes/runes/'");
  }


function keeper_create_zip( String $location, String $destination ) {
  if (empty($location) || !is_dir($location)) {
    throw new \Exception("Source folder tidak ditemukan atau kosong: $location");
  }

  forger_folder($location);
  $target = $location . '/artefact.zip';
  forger_file($target);

  if (empty($target)) {
    throw new \Exception("Path file ZIP tidak valid. Cek fungsi forger_file().");
  }

  $zip = new \ZipArchive();

  if ($zip->open($target, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
    $source = realpath($location);
    $files = new \RecursiveIteratorIterator(
      new \RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS),
      \RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $file) {
      if (!$file->isDir()) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($source) + 1);
        $zip->addFile($filePath, $relativePath);
      }
    }

    $zip->close();
  } else {
    throw new \Exception("Gagal membuat arsip ZIP di lokasi: $target");
  }

  return $target;
}
