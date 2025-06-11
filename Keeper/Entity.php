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
  forger_folder(AETHER_ECHOES_RUNE);
  forger_file(AETHER_ECHOES_RUNE . $source);
  forger_set(AETHER_ECHOES_RUNE . $source, $datas);
}

function keeper_get( String $source ) {
  return forger_file(AETHER_ECHOES_RUNE . $source);
}

function keeper_has( String $source ) {
  return file_exists(AETHER_ECHOES_RUNE . $source);
}



function keeper_raw_set( String $source, $datas ) {
  forger_folder(AETHER_ECHOES);
  forger_file(AETHER_ECHOES . $source);
  return forger_set(AETHER_ECHOES . $source, $datas);
}

function keeper_raw_get( String $source ) {
  return forger_file(AETHER_ECHOES . $source);
}

function keeper_raw_has( String $source ) {
  return file_exists(AETHER_ECHOES . $source);
}

function keeper_data_set( String $source, $datas ) {
  $data = json_encode($datas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  $data = str_replace('    ', '  ', $data);
  keeper_raw_set($source, $data);
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
