<?php

Nirvana::environment([
  'data'=> [
    'ECHOES_ROOT'=> realpath(__DIR__),
    'ECHOES_LOGS'=> realpath(__DIR__ . '/.echoes/logs/'.date('Y-m-d').'.txt'),
    'ECHOES_INFORMATION'=> realpath(__DIR__ . '/.echoes/information.json'),
    'ECHOES_ARTEFACT'=> realpath(__DIR__ . '/.echoes/artefacts/'),
    'ECHOES_SHARDS'=> realpath(__DIR__ . '/.echoes/shards.json'),
    'ECHOES_RUNES'=> realpath(__DIR__ . '/.echoes/runes.json'),
  ],
  "configure"=> [
    "development"=> true,
    "baseurl" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/'
  ]
]);


function foldersize($dir) {
  $size = 0;
  foreach (scandir($dir) as $file) {
    if ($file == '.' || $file == '..') continue;
    $path = $dir . DIRECTORY_SEPARATOR . $file;
    if (is_file($path)) {
      $size += filesize($path);
    } elseif (is_dir($path)) {
      $size += foldersize($path); // rekursif ke folder dalam
    }
  }
  return $size;
}

function getFolderSize($folder) {
  $size = 0;
  foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder)) as $file) {
    if ($file->isFile()) {
      $size += $file->getSize();
    }
  }
  return $size; // dalam byte
}
function formatSize($bytes, $decimal = 2) {
  $sizeUnits = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimal}f", $bytes / pow(1024, $factor)) . ' ' . $sizeUnits[$factor];
}










Nirvana::rest('GET', 'api/synchronize', function() {
  $headers = getallheaders();
  $interval = ((float) $headers['Rune-interval']/1000) + 0.5;

  header('Content-Type: text/plain');
  // header("Cache-Control: no-cache, no-store, must-revalidate");
  // header("Pragma: no-cache");
  // header("Expires: 0");

  if (time() - filemtime(Nirvana::data('ECHOES_INFORMATION')) <= $interval) {
    exit('true');
  }else {
    exit('false');
  }
});

Nirvana::rest('GET', 'api/logs', function () {
  $source = file_get_contents(Nirvana::data('ECHOES_LOGS'));
  return [
    'source'=> $source
  ];
});

Nirvana::rest('GET', 'api/information', function() {
  $information = json_decode(file_get_contents(Nirvana::data('ECHOES_INFORMATION')), true);
  $information['DISK_USAGE'] = formatSize(foldersize(Nirvana::data('ECHOES_ROOT')));
  $information['LOGS_SIZE'] = formatSize(filesize(Nirvana::data('ECHOES_LOGS')));
  return $information;
});

Nirvana::rest('POST', 'api/run_command', function() {
  $headers = getallheaders();
  $file = $headers['Rune-file'];
  $command = Nirvana::method('command');
  proc_open("start cmd /k $command", [], $pipes);
  return [ true ];
});

Nirvana::rest('POST', 'api/rune_list', function() {
  $headers = getallheaders();
  $file = $headers['Rune-file'];
  
  shell_exec("php {$file} grimoire --all");
  $data = file_get_contents(Nirvana::data('ECHOES_RUNES'));
  
  return [ 'source'=> $data ];
});

Nirvana::rest('POST', 'api/shard_list', function() {
  $headers = getallheaders();
  $file = $headers['Rune-file'];
  
  shell_exec("php {$file} artefact --result");
  $data = file_get_contents(Nirvana::data('ECHOES_SHARDS'));
  
  return [ 'source'=> $data ];
});

Nirvana::rest('POST', 'api/shard_remove', function() {
  unlink(Nirvana::data('ECHOES_ARTEFACT') . '/' . Nirvana::method('target'));
  return [true];
});

/* GRIMOIRE */
Nirvana::rest('POST', 'api/grimoire/all', function() {
  $headers = getallheaders();
  $file = $headers['Rune-file'];

  $data = shell_exec("php {$file} grimoire --all");

  return [ 'source'=> $data ];
});

Nirvana::rest('POST', 'api/grimoire/one', function() {
  $headers = getallheaders();
  $file = $headers['Rune-file'];

  $selected = Nirvana::method('selected');

  $data = shell_exec("php {$file} grimoire --select=$selected");

  return [ 'source'=> $data ];
});
Nirvana::rest('POST', 'api/grimoire/log_clear', function() {
  $headers = getallheaders();
  $file = $headers['Rune-file'];
  
  shell_exec("php {$file} grimoire --log_clear");
  
  return [ true ];
});


/* SENTINEL */
Nirvana::rest('POST', 'api/sentinel/altar', function() {
  $headers = getallheaders();
  $file = $headers['Rune-file'];
  
  shell_exec("php {$file} sentinel --rise_altar");
  
  return [ true ];
});
Nirvana::rest('POST', 'api/sentinel/invoke', function() {
  $headers = getallheaders();
  $file = $headers['Rune-file'];

  $selected = Nirvana::method('selected');

  shell_exec("php {$file} sentinel --invoke_option=$selected");

  return [ true ];
});
Nirvana::rest('POST', 'api/sentinel/revoke', function() {
  $headers = getallheaders();
  $file = $headers['Rune-file'];

  $selected = Nirvana::method('selected');

  shell_exec("php {$file} sentinel --revoke_option=$selected");

  return [ true ];
});



/* ARTEFACT */
Nirvana::rest('POST', 'api/artefact/invoke', function() {
  $headers = getallheaders();
  $file = $headers['Rune-file'];

  shell_exec("php {$file} artefact --invoke");

  return [ true ];
});
Nirvana::rest('POST', 'api/artefact/revoke/internal', function() {
  $headers = getallheaders();
  $file = $headers['Rune-file'];

  shell_exec("php {$file} artefact --revoke_option={$file}.rune");

  return [ true ];
});
Nirvana::rest('POST', 'api/artefact/revoke/external', function() {
  $headers = getallheaders();
  $file = $headers['Rune-file'];

  $fileRune = $_FILES['file']['tmp_name'];
  $fileRuneName = __DIR__ . '/' . $file . '.rune';

  if (file_exists($fileRuneName)) {
    unlink($fileRuneName);
  }

  move_uploaded_file($fileRune, $fileRuneName);
  
  shell_exec("php {$file} artefact --revoke_option={$file}.rune");
  
  return [true];
});