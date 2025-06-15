<?php



// function whisper( String $message, Bool $inLine=false) {
//   if ($inLine ) {
//     whisper_il($message);
//   }else {
//     whisper_nl($message);
//   }
// }

// function whisper_nl( String $message ) {
//   $message = whisper_var_search($message . '{{COLOR-DEFAULT}}');
//   print($message . PHP_EOL);
// }

// function whisper_il( String $message ) {
//   $message = whisper_var_search($message . '{{COLOR-DEFAULT}}');
//   print($message);
// }

// function whisper_loader(callable $callback, array $option) {
//   $frames = ['-', '\\', '|', '/'];
//   $speed = $option['speed'] ?? 100;
//   $delay = $option['delay'] ?? null;
//   $infinite = $option['infinite'] ?? null;
//   $i = 0;

//   if (is_callable($infinite)) {
//     while ($infinite()) {
//       whisper_clear();
//       $callback($frames[$i % count($frames)]);
//       usleep($speed * 1000);
//       $i++;
//     }
//   } else {
//     $steps = $delay ? (int)($delay / $speed) : 10;
//     for ($i = 0; $i < $steps; $i++) {
//       whisper_clear();
//       $callback($frames[$i % count($frames)]);
//       usleep($speed * 1000);
//     }
//   }
// }

// function whisper_input( String $prompt ) {
//   echo $prompt;
//   return trim(fgets(STDIN));
// }

// function whisper_var_search( String $value ) {
//   global $WHISPER;
//   $matches = [];
//   preg_match_all('/{{(.*?)}}/', $value, $matches);
//   $match = array_values(array_unique($matches[1]));
//   foreach ($match as $m) {
//     $key = explode('-', strtoupper($m));
//     // $value = str_replace("{{" . $m . "}}", $WHISPER[$key[0]][$key[1]], $value);
//     $value = weaver_bind($value, $m, $WHISPER[$key[0]][$key[1]]);
//   }
//   return $value;
// }

// function whisper_http(string $url, string $method = 'GET', array $headers = [], $body = null) {
//   $ch = curl_init();

//   curl_setopt($ch, CURLOPT_URL, $url);
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

//   // Header
//   if (!empty($headers)) {
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//   }

//   // Body (untuk POST, PUT, dll)
//   if (in_array(strtoupper($method), ['POST', 'PUT', 'PATCH']) && $body !== null) {
//     curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($body) ? http_build_query($body) : $body);
//   }

//   // Eksekusi curl
//   $response = curl_exec($ch);
//   $error = curl_error($ch);
//   $info = curl_getinfo($ch);

//   curl_close($ch);

//   return [
//     'body' => $response,
//     'error' => $error,
//     'info' => $info,
//   ];
// }




function whisper() {
  return true;
}

/* HELPERS
 *  */
function whisper_clear() {
  echo "\033[2J\033[0;0H";
}
function whisper_clear_force() {
  system('clear');
  system('cls');
  whisper_clear();
}
function whisper_delay( Int $ms ) {
  usleep($ms * 1000);
}


/* EMIT
 * todo set whisper to user 
 * */
function whisper_emit( String $message, Bool $asString = false ) {
  $return = true;

  if ($asString) {
    $return = whisper_emit_get($message);
  }else {
    whisper_emit_set($message);
  }

  aether_arcane('Whisper.entity.whisper_emit');
  return $return;
}

function whisper_emit_get( String $message ) {
  $return = whisper_emit_imbue($message . '{{color-end}}');

  aether_arcane('Whisper.entity.whisper_emit_get');
  return $return;
}

function whisper_emit_set( String $message ) {
  global $WHISPER_STAKE_STATE;

  $return = whisper_emit_imbue($message . '{{color-end}}');
  
  if ($WHISPER_STAKE_STATE) {
    whisper_stake_set($return);
  }else {
    print($return);
  }

  aether_arcane('Whisper.entity.whisper_emit_set');
  return true;
}

function whisper_emit_imbue( String $text ) {
  global $WHISPER_VARS;
  global $WHISPER_COLORS;
  global $WHISPER_ICONS;
  global $WHISPER_LABELS;

  // remap
  $maps = $WHISPER_VARS;
  $maps_threedimension = [
    'color'=> $WHISPER_COLORS,
    'icon'=> $WHISPER_ICONS,
    'label'=> $WHISPER_LABELS,
  ];
  foreach ($maps_threedimension as $key=>$row) {
    foreach ($row as $key2 => $value) {
      $maps[ $key.'-'.$key2 ] = $value;
    }
  }

  // set all vars
  // foreach ($maps as $key=>$value) {
  //   $text = str_replace("      ", '{{tab}}', $text);
  // }
  // foreach ($maps as $key=>$value) {
  //   $text = str_replace( strtolower("{{ ".$key." }}"), $value, $text);
  //   $text = str_replace( strtolower("{{".$key."}}"), $value, $text);
  //   $text = str_replace( strtoupper("{{ ".$key." }}"), $value, $text);
  //   $text = str_replace( strtoupper("{{".$key."}}"), $value, $text);
  // }
  $text = weaver_bind_multiple($text, $maps);

  aether_arcane('Whisper.entity.whisper_emit_imbue');
  return $text;
}



/* REAP
 * todo get user whisper
 *  */
function whisper_reap( String $prompt ) {
  whisper_emit("{{COLOR-INFO}}{{ICON-INFO}} $prompt{{nl}}");
  $response = trim(fgets(STDIN));

  aether_arcane('Whisper.entity.whisper_reap'); 
  return $response;
}


/* STAKE
 *  */
function whisper_stake() {}

function whisper_stake_start() {
  global $WHISPER_STAKE_STATE;
  $WHISPER_STAKE_STATE = true;
}

function whisper_stake_set( String $message ) {
  global $WHISPER_STAKE;
  $WHISPER_STAKE[] = $message;
}

function whisper_stake_get() {
  global $WHISPER_STAKE;
  return implode('', $WHISPER_STAKE);
}

function whisper_stake_end() {
  global $WHISPER_STAKE_STATE;
  $WHISPER_STAKE_STATE = false;
}


/* DRAIN
 * todo draining the callback
 *  */
function whisper_drain( callable $callback, array $option) {
  $frames = ['-', '\\', '|', '/'];
  $speed = $option['speed'] ?? 100;
  $delay = $option['delay'] ?? null;
  $infinite = $option['infinite'] ?? null;
  $i = 0;

  if (is_callable($infinite)) {
    while ($infinite()) {
      whisper_clear();
      $callback($frames[$i % count($frames)]);
      usleep($speed * 1000);
      $i++;
    }
  } else {
    $steps = $delay ? (int)($delay / $speed) : 10;
    for ($i = 0; $i < $steps; $i++) {
      whisper_clear();
      $callback($frames[$i % count($frames)]);
      usleep($speed * 1000);
    }
  }
}


/* IMBUE
 * todo set default variable to text */
