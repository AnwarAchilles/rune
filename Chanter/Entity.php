<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */

function chanter() {
  return true;
}

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
  global $CHANTER_SPELL;

  $CHANTER_ARG_CAST = [];
  $CHANTER_ARG_SPELL = $CHANTER_SPELL;
  // $CHANTER_ARG_SPELL = array_merge($CHANTER_ARG_SPELL, []);
  // $CHANTER_ARG_SPELL = array_merge($CHANTER_ARG_SPELL, []);

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
        $value = (isset($match[2])) ? $match[2] : false;
        $value = (!empty($value)) ? $value : true;
        $CHANTER_ARG_SPELL[$key] = $value;
      }
    }else {
      $CHANTER_ARG_CAST[] = $arg;
    }
  }
  
  $CHANTER_ARG_CAST = trim(implode(' ', $CHANTER_ARG_CAST));

  aether_arcane("Chanter.entity.chanter_arg_extract");
}



/* CAST
 * todo cast a chanter
 * */
function chanter_cast( String $args, ?Callable $callable ) {
  if (is_callable($callable)) {
    $return = chanter_cast_get($args);
  }else {
    chanter_cast_set($args, $callable);
    $return = true;
  }

  aether_arcane("Chanter.manifest.cast");
  return $return;
}

function chanter_cast_set( String $arg, ?Callable $callable ) {
  global $CHANTER_ARG_CAST;
  global $CHANTER_CAST;
  global $CHANTER_CAST_LIST;
  global $CHANTER_ECHO;

  chanter_arg_extract( $arg );
  
  if (!in_array($CHANTER_ARG_CAST, $CHANTER_CAST_LIST)) {
    $CHANTER_CAST[$CHANTER_ARG_CAST] = $callable;
    $CHANTER_ECHO[$CHANTER_ARG_CAST] = [$CHANTER_ARG_CAST, $arg, ''];
  }
  
  aether_arcane("Chanter.entity.chanter_set");
}

function chanter_cast_get( String $arg ) {
  global $CHANTER_ARG_CAST;
  global $CHANTER_CAST;
  global $CHANTER_ECHO;
  
  chanter_arg_extract( $arg );

  if (isset($CHANTER_CAST[$CHANTER_ARG_CAST])) {
    $return = $CHANTER_CAST[$CHANTER_ARG_CAST];
  }else {
    $return = function() use ($CHANTER_ARG_CAST, $CHANTER_ECHO) {
      
      if (aether_has_entity('whisper')) {
        whisper_echo("{{color-warning}}{{icon-warning}}{{label-warning}}Chanter cast with '$CHANTER_ARG_CAST' not found.");
      }else {
        print("[!] Chanter cast with '$CHANTER_ARG_CAST' not found.");
      }

      whisper_echo("\n{{color-info}}{{icon-info}}{{label-info}}You mean: ");
      foreach (array_keys($CHANTER_ECHO) as $cast) {
        similar_text($cast, $CHANTER_ARG_CAST, $persen);

        if ($persen >= 70) { // ambil yang mirip banget aja, bisa diatur sendiri
          if (aether_has_entity('whisper')) {
            whisper_echo("{{color-info}}{$cast}, ");
          }
          break; // cukup ambil satu yang paling relevan dulu
        }
      }

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
  global $CHANTER_SPELL;

  $CHANTER_SPELL[$name] = $value;
  
  aether_arcane("Chanter.entity.chanter_spell_set");
  return true;
}

function chanter_spell_get( String $name ) {
  global $CHANTER_ARG_SPELL;

  if (isset($CHANTER_ARG_SPELL[$name])) {
    $return = $CHANTER_ARG_SPELL[$name];
  }else {
    $return = false;
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
  
  $spell = trim($spell);
  aether_arcane("Chanter.entity.chanter_spell_chain");
  return $spell;
}

function chanter_spell_has( String $name ) {
  global $CHANTER_ARG_SPELL;

  if (isset($CHANTER_ARG_SPELL[$name]) && $CHANTER_ARG_SPELL[$name] !== '1') {
    $return = true;
  }else {
    $return = false;
  };
}



/* ECHO
 * todo set echo in chanter */
function chanter_echo( String $echo ) {
  global $CHANTER_ECHO;
  global $CHANTER_ARG;
  global $CHANTER_ARG_CAST;
  global $CHANTER_ARG_SPELL;

  if (is_string(!empty($CHANTER_ARG_CAST))) {
    $CHANTER_ECHO[$CHANTER_ARG_CAST] = $echo;
  
    $return = true;
  }else {
    $return = false;
  }

  aether_arcane("Chanter.entity.chanter_echo");
  return $return;
}

function chanter_echo_set( String $arg, String $notes ) {
  global $CHANTER_ECHO;

  if (!isset($CHANTER_ECHO[$arg])) {
    $CHANTER_ECHO[$arg][2] = $notes;
  }
  
  aether_arcane("Chanter.entity.chanter_echo_set");
  return true;
}

function chanter_echo_get( String $arg ) {
  global $CHANTER_ECHO;

  $return = (isset($CHANTER_ECHO[$arg])) ? $CHANTER_ECHO[$arg] : false;
  
  aether_arcane("Chanter.entity.chanter_echo_get");
  return $return;
}



/* WHISPER LATCH */
function chanter_whisper_drain( $run ) {
  whisper_drain_start();
  $x = $run();
  $return = whisper_drain_get();
  whisper_drain_end();
  whisper_echo($return);
}