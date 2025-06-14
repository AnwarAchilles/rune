<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */

function forger() {
  return true;
}


/* TRACE
 * */
function forger_trace( String $source_path ) {
  $source_path = str_replace('/', DIRECTORY_SEPARATOR, $source_path);
  $pathParts = explode(DIRECTORY_SEPARATOR, $source_path);
  
  $targets = [];
  $current = '';

  foreach ($pathParts as $part) {
    $current .= ($current === '' ? '' : DIRECTORY_SEPARATOR) . $part;
    if (!empty($current)) {
      $stack['target'] = $current;
      $stack['ready'] = (file_exists($current));

      $ext = pathinfo($current, PATHINFO_EXTENSION);
      $basename = pathinfo($current, PATHINFO_BASENAME);
      $isHidden = substr($basename, 0, 1) === '.';
      $stack['type'] = (!$ext || $isHidden) ? 'repo' : 'item';

      $targets[] = $stack;
    }
  }
  
  aether_arcane('Forger.entity.forger_trace');
  return $targets;
}


/* SCAN
 * */
function forger_scan( String $source_path, $callback ) {
  $return = [];
  foreach (glob( $source_path . '/*', GLOB_NOSORT ) as $item) {
    $return[] = $callback($item);
  }

  aether_arcane('Forger.entity.forger_scan');
  return $return;
}


/* FIX
 * */
function forger_fix( Array $source_path ) {
  foreach ($source_path as $source) {
    $state = (isset($source['ready'])) ? $source['ready'] : file_exists($source['target']);
    if ($state === false) {
      if ($source['type'] === 'repo') {
        mkdir($source['target'], 0755, true);
      }
      if ($source['type'] === 'item') {
        touch($source['target']);
        chmod($source['target'], 0644);
      }
    }
  }

  aether_arcane('Forger.entity.forger_fix');
}


/* REPO
 * todo working with folder */
function forger_repo( String $source_path, Callable $callback = null ) {
  $trace = forger_trace( $source_path );
  forger_fix( $trace );
  
  $return = true;

  if (!empty($callback)) {
    $return = forger_scan( $source_path, $callback );
  }

  aether_arcane('Forger.entity.forger_repo');
  return $return;
}


/* ITEM
 * todo working with file
 *  */
function forger_item( String $source_path, $content = false, $flags = 0 ) {
  $trace = forger_trace( $source_path );
  forger_fix( $trace );
  
  if ($content!==false) {
    file_put_contents( $source_path, $content, $flags );
  }

  aether_arcane('Forger.entity.forger_item');
  return file_get_contents( $source_path );
}


/* CLONE
 * */
function forger_clone( string $from, string $to ): bool {
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
      forger_clone($sourcePath, $destPath);
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

  aether_arcane('Forger.entity.forger_clone');
  return true;
}








// function forger() {
//   return true;
// }

// function forger_file( String $source_path, Int $permission = 0644 ) {
//   if (file_exists($source_path)) {
//     return file_get_contents($source_path);
//   }else {
//     touch($source_path);
//     chmod($source_path, $permission);
//     return true;
//   }
// }

// function forger_folder( String $source_path, Int $permission = 0755 ) {
//   if (file_exists($source_path)) {
//     return true;
//   }else {
//     mkdir($source_path, $permission, true);
//     return true;
//   }
// }

// function forger_folder_move(string $from, string $to): bool {
//   if (!is_dir($from)) return false;

//   forger_folder($to); // 💖 auto-forge kalau belum ada

//   $items = scandir($from);
//   foreach ($items as $item) {
//     if ($item === '.' || $item === '..') continue;

//     $sourcePath = $from . DIRECTORY_SEPARATOR . $item;
//     $destPath   = $to . DIRECTORY_SEPARATOR . $item;

//     if (is_dir($sourcePath)) {
//       forger_folder_move($sourcePath, $destPath);
//       @rmdir($sourcePath); // hapus folder kosong
//     } else {
//       rename($sourcePath, $destPath);
//     }
//   }

//   return true;
// }

// function forger_folder_clone(string $from, string $to): bool {
//   if (!is_dir($from)) {
//     echo "[x] Folder sumber tidak ditemukan: $from\n";
//     return false;
//   }

//   $items = scandir($from);
//   foreach ($items as $item) {
//     if ($item === '.' || $item === '..') continue;

//     $sourcePath = $from . DIRECTORY_SEPARATOR . $item;
//     $destPath   = $to . DIRECTORY_SEPARATOR . $item;

//     if (is_dir($sourcePath)) {
//       // Buat struktur folder dulu
//       if (!is_dir($destPath)) {
//         mkdir($destPath, 0777, true);
//       }
//       // Rekursif ke dalam
//       forger_folder_clone($sourcePath, $destPath);
//     } else {
//       // Buat folder tujuan kalau belum ada
//       $destDir = dirname($destPath);
//       if (!is_dir($destDir)) {
//         mkdir($destDir, 0777, true);
//       }
//       // Copy file-nya
//       if (!copy($sourcePath, $destPath)) {
//         echo "[!] Gagal copy file: $sourcePath -> $destPath\n";
//       }
//     }
//   }

//   return true;
// }


// function forger_folder_all(string $source_path, int $permission = 0755): bool {
//     $folders = explode('/', $source_path);
//     $currentPath = '';

//     foreach ($folders as $folder) {
//       if ($folder === '') continue; // skip kalo ada folder kosong
//       $currentPath .= $folder . '/';
//       if (!file_exists($currentPath)) {
//         if (!mkdir($currentPath, $permission)) {
//           return false; // gagal bikin folder
//         }
//       }
//     }

//     return true; // sukses semua
// }


// function forger_set( String $source_path, $datas ) {
//   if (!file_exists($source_path)) {
//     forger_file($source_path);
//   }
//   return file_put_contents($source_path, $datas);
// }

// function forger_get( String $source_path ) {
//   if (file_exists($source_path)) {
//     return file_get_contents($source_path);
//   }else {
//     return false;
//   }
// }

// function forger_scan( String $source_path, $callback ) {
//   $files = scandir($source_path);
//   $catch = [];
//   foreach ($files as $file) {
//     if ($file !== '.' && $file !== '..') {
//       $file = pathinfo($file);
//       $file['target'] = $source_path . DIRECTORY_SEPARATOR . $file['basename'];
//       $catch[] = $callback( (object) $file);
//     }
//   }
//   return $catch;
// }



// function forger_route( String $source_path, Int $dirPermission = 0775, Int $filePermission = 0644 ) {
//   // Pecah path jadi bagian-bagian
//   $pathParts = explode(DIRECTORY_SEPARATOR, $source_path);
//   $currentPath = '';

//   foreach ($pathParts as $index => $part) {
//     $currentPath .= $part;

//     // Kalau ini bukan bagian terakhir dan belum ada, buat folder
//     if ($index < count($pathParts) - 1) {
//       if (!file_exists($currentPath)) {
//         mkdir($currentPath, $dirPermission);
//       }
//     } else {
//       // Bagian terakhir, cek apakah file atau folder
//       if (pathinfo($part, PATHINFO_EXTENSION)) {
//         if (!file_exists($currentPath)) {
//           touch($currentPath); // file
//           chmod($currentPath, $filePermission);
//         }
//       } else {
//         if (!file_exists($currentPath)) {
//           mkdir($currentPath, $dirPermission); // folder
//         }
//       }
//     }
//     // Tambahin separator untuk path selanjutnya
//     $currentPath .= DIRECTORY_SEPARATOR;
//   }
// }
