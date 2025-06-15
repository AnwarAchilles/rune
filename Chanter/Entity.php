<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */

function chanter() {
  return true;
}

// function chanter_arg( String $newArg = '' ) {
//   global $CHANTER_ARG;
//   if ($newArg !== '') {
//     $CHANTER_ARG = $newArg;
//   }
  
//   aether_arcane("Chanter.entity.chanter_arg");
//   return $CHANTER_ARG;
// }

// function chanter_args() {
//   global $CHANTER_ARGS;
  
//   aether_arcane("Chanter.entity.chanter_args");
//   return $CHANTER_ARGS;
// }

// function chanter_cast( String $arg ) {
//   $arg = weaver_bind($arg, 'self', 'php ' . AETHER_FILE);
  
//   aether_arcane("Chanter.entity.chanter_cast");
//   return shell_exec($arg);
// }

// function chanter_option(string $name) {
//   global $CHANTER_ARG_REAL;
//   $search = '--' . $name;
  
//   $pos = strpos($CHANTER_ARG_REAL, $search);
//   if ($pos === false) {
//     aether_arcane("Chanter.entity.chanter_option");
//     return false;
//   }
  
//   $nextCharPos = $pos + strlen($search);
//   $nextChar = $CHANTER_ARG_REAL[$nextCharPos] ?? '';
  
//   if ($nextChar !== '' && $nextChar !== '=' && $nextChar !== ' ') {
//     aether_arcane("Chanter.entity.chanter_option");
//     return false;
//   }
  
//   if ($nextChar === '=') {
//     $valueStart = $nextCharPos + 1;
//     $valueEnd = strpos($CHANTER_ARG_REAL, ' ', $valueStart);
//     $value = $valueEnd === false
//       ? substr($CHANTER_ARG_REAL, $valueStart)
//       : substr($CHANTER_ARG_REAL, $valueStart, $valueEnd - $valueStart);
//     if (
//       defined('AETHER_FILE') &&
//       substr($value, 0, strlen(AETHER_FILE)) === AETHER_FILE &&
//       strlen($value) === strlen(AETHER_FILE)
//     ) {
//       $value = '';
//     }
    
//     aether_arcane("Chanter.entity.chanter_option");
//     return $value;
//   }
  
//   aether_arcane("Chanter.entity.chanter_option");
//   return true;
// }

// function chanter_option_clean(string $str) {
//   $arg = preg_replace('/--\w+(=[^\s]+)?\s*/', '', $str);
  
//   aether_arcane("Chanter.entity.chanter_option_clean");
//   return trim($arg);
// }


/* ARG
 * todo get arguments */
function chanter_arg( String $newArg = '' ) {
  global $CHANTER_ARG;

  if ($newArg !== '') {
    $CHANTER_ARG = $newArg;
  }
  
  aether_arcane("Chanter.entity.chanter_arg");
  return $CHANTER_ARG;
}

function chanter_arg_extract( String $newArg = '' ) {
  global $CHANTER_ARG_CAST;
  global $CHANTER_ARG_SPELL;

  $CHANTER_ARG_CAST = [];
  $CHANTER_ARG_SPELL = array_merge([], $CHANTER_ARG_SPELL);

  if (!empty($newArg)) {
    $args = explode(' ', $newArg);
  }else {
    $args = explode(' ', chanter_arg());
    unset($args[0]);
  }
  
  foreach ($args as $arg) {
    if (strpos($arg, '--')!==false) {
      if (preg_match('/^--([a-zA-Z0-9_-]+)(?:=(.*))?$/', $arg, $match)) {
        $key = str_replace('--', '', $match[1]);
        $value = isset($match[2]) ? $match[2] : true;
        $CHANTER_ARG_SPELL[$key] = $value;
      }
    }else {
      $CHANTER_ARG_CAST[] = $arg;
    }
  }

  $CHANTER_ARG_CAST = trim(implode(' ', $CHANTER_ARG_CAST));

  aether_arcane("Chanter.entity.chanter_arg_extract");
}

function chanter_arg_get_cast() {
  global $CHANTER_ARG_CAST;
  
  aether_arcane("Chanter.entity.chanter_arg_cast");
  return $CHANTER_ARG_CAST;
}


/* CAST
 * todo cast a chanter
 * */
function chanter_cast( String $args, Callable $callable = NULL ) {
  if (empty($callable)) {
    $return = chanter_cast_get($args);
  }else {
    chanter_cast_set($args, $callable);
    $return = true;
  }

  aether_arcane("Chanter.manifest.cast");
  return $return;
}

function chanter_cast_set( String $arg, Callable $callable ) {
  global $CHANTER_ARG_CAST;
  global $CHANTER_CAST;
  global $CHANTER_CAST_LIST;

  chanter_arg_extract( $arg );

  if (!in_array($CHANTER_ARG_CAST, $CHANTER_CAST_LIST)) {
    $CHANTER_CAST[$CHANTER_ARG_CAST] = $callable;
  }

  aether_arcane("Chanter.entity.chanter_set");
}

function chanter_cast_get( String $arg ) {
  global $CHANTER_ARG_CAST;
  global $CHANTER_CAST;
  
  chanter_arg_extract( $arg );

  if (isset($CHANTER_CAST[$CHANTER_ARG_CAST])) {
    $return = $CHANTER_CAST[$CHANTER_ARG_CAST];
  }else {
    $return = function() use ($CHANTER_ARG_CAST) {
      print('not found');
    };
  };

  aether_arcane("Chanter.entity.chanter_get");
  return $return;
}

function chanter_cast_has( String $arg ) {
  global $CHANTER_ARG_CAST;

  chanter_arg_extract( $arg );

  if (isset($CHANTER_CAST[$CHANTER_ARG_CAST])) {
    $return = true;
  }else {
    $return = false;
  };

  aether_arcane("Chanter.entity.chanter_has");
  return $return;
}


/* SPELL
 * todo spell checker */
function chanter_spell( String $name, $values = NULL ) {
  if (empty($values)) {
    $return = chanter_spell_get($name);
  }else {
    chanter_spell_set($name, $values);
    $return = true;
  }
  
  aether_arcane("Chanter.manifest.cast");
  return $return;
}

function chanter_spell_set( String $name, String $value ) {
  global $CHANTER_ARG_SPELL;

  $CHANTER_ARG_SPELL[$name] = $value;
  
  aether_arcane("Chanter.entity.chanter_spell_set");
  return true;
}

function chanter_spell_get( String $name ) {
  global $CHANTER_ARG_SPELL;

  $return = false;

  if (isset($CHANTER_ARG_SPELL[$name])) {
    $return = $CHANTER_ARG_SPELL[$name];
  }else {
    $return = true;
  };

  aether_arcane("Chanter.entity.chanter_spell_get");
  return $return;
}

function chanter_spell_chain() {
  global $CHANTER_ARG_SPELL;

  $spell = '';
  foreach ($CHANTER_ARG_SPELL as $key=>$value) {
    $spell .= '--'.$key.'='.$value.' ';
  }
  
  aether_arcane("Chanter.entity.chanter_spell_chain");
  return $spell;
}



/* ECHO
 * todo set echo in chanter */
function chanter_echo( String $echo ) {
  
}