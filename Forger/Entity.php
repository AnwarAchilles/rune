<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */


function forger() {
  return true;
}

function forger_file( String $sourcepath, Int $permission = 0644 ) {
  if (file_exists($sourcepath)) {
    return file_get_contents($sourcepath);
  }else {
    touch($sourcepath);
    chmod($sourcepath, $permission);
  }
}

function forger_folder( String $sourcepath, Int $permission = 0755 ) {
  if (file_exists($sourcepath)) {
    return true;
  }else {
    mkdir($sourcepath, $permission, true);
  }
}

function forger_folder_move(string $from, string $to): bool {
  if (!is_dir($from)) return false;

  forger_folder($to); // 💖 auto-forge kalau belum ada

  $items = scandir($from);
  foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;

    $sourcePath = $from . DIRECTORY_SEPARATOR . $item;
    $destPath   = $to . DIRECTORY_SEPARATOR . $item;

    if (is_dir($sourcePath)) {
      forger_folder_move($sourcePath, $destPath);
      @rmdir($sourcePath); // hapus folder kosong
    } else {
      rename($sourcePath, $destPath);
    }
  }

  return true;
}

function forger_folder_clone(string $from, string $to): bool {
  if (!is_dir($from)) {
    echo "[x] Folder sumber tidak ditemukan: $from\n";
    return false;
  }

  $items = scandir($from);
  foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;

    $sourcePath = $from . DIRECTORY_SEPARATOR . $item;
    $destPath   = $to . DIRECTORY_SEPARATOR . $item;

    if (is_dir($sourcePath)) {
      // Buat struktur folder dulu
      if (!is_dir($destPath)) {
        mkdir($destPath, 0777, true);
      }
      // Rekursif ke dalam
      forger_folder_clone($sourcePath, $destPath);
    } else {
      // Buat folder tujuan kalau belum ada
      $destDir = dirname($destPath);
      if (!is_dir($destDir)) {
        mkdir($destDir, 0777, true);
      }
      // Copy file-nya
      if (!copy($sourcePath, $destPath)) {
        echo "[!] Gagal copy file: $sourcePath -> $destPath\n";
      }
    }
  }

  return true;
}







function forger_folder_all(string $sourcepath, int $permission = 0755): bool {
    $folders = explode('/', $sourcepath);
    $currentPath = '';

    foreach ($folders as $folder) {
      if ($folder === '') continue; // skip kalo ada folder kosong
      $currentPath .= $folder . '/';
      if (!file_exists($currentPath)) {
        if (!mkdir($currentPath, $permission)) {
          return false; // gagal bikin folder
        }
      }
    }

    return true; // sukses semua
}


function forger_set( String $sourcepath, $datas ) {
  if (!file_exists($sourcepath)) {
    forger_file($sourcepath);
  }
  return file_put_contents($sourcepath, $datas);
}

function forger_get( String $sourcepath ) {
  if (file_exists($sourcepath)) {
    return file_get_contents($sourcepath);
  }else {
    return false;
  }
}

function forger_scan( String $sourcepath, $callback ) {
  $files = scandir($sourcepath);
  $catch = [];
  foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
      $file = pathinfo($file);
      $file['target'] = $sourcepath . DIRECTORY_SEPARATOR . $file['basename'];
      $catch[] = $callback( (object) $file);
    }
  }
  return $catch;
}



function forger_route( String $sourcepath, Int $dirPermission = 0775, Int $filePermission = 0644 ) {
  // Pecah path jadi bagian-bagian
  $pathParts = explode(DIRECTORY_SEPARATOR, $sourcepath);
  $currentPath = '';

  foreach ($pathParts as $index => $part) {
    $currentPath .= $part;

    // Kalau ini bukan bagian terakhir dan belum ada, buat folder
    if ($index < count($pathParts) - 1) {
      if (!file_exists($currentPath)) {
        mkdir($currentPath, $dirPermission);
      }
    } else {
      // Bagian terakhir, cek apakah file atau folder
      if (pathinfo($part, PATHINFO_EXTENSION)) {
        if (!file_exists($currentPath)) {
          touch($currentPath); // file
          chmod($currentPath, $filePermission);
        }
      } else {
        if (!file_exists($currentPath)) {
          mkdir($currentPath, $dirPermission); // folder
        }
      }
    }
    // Tambahin separator untuk path selanjutnya
    $currentPath .= DIRECTORY_SEPARATOR;
  }
}
