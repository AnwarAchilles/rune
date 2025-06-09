<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Specter;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  // create next static method


  public static function observer( $folder, $runner ) {
    $item_modified = 0;
    $base_modified = 0;
    $first_run = true;

    whisper_nl("");
    whisper_nl("{{COLOR-DANGER}} RUNE:SPECTER - O B S E R V E R");
    whisper_nl("{{COLOR-SECONDARY}} exit this observer with Ctrl+C");
    whisper_nl("");
    self::seer(function() use (&$item_modified, &$folder, &$base_modified, &$first_run, $runner) {
      $item_modified_watch = specter_folder($folder);
      $base_modified_watch = filemtime(AETHER_FILE);

      if (!$first_run && $item_modified_watch != $item_modified) {
        $runner();
      }

      if (!$first_run && $base_modified != $base_modified_watch) {
        $runner();
      }

      // simpan waktu terakhir + matikan flag first run
      $item_modified = $item_modified_watch;
      $base_modified = $base_modified_watch;
      $first_run = false;
    });
  }
  
  public static function seer( Callable $callback ) {
    $targetSpeed = 0.1;
    while (true) {
        gc_enable();
        $start = microtime(true);

        // Jalankan fungsi utama
        $callback();

        $end = microtime(true);
        $duration = $end - $start;

        // Jika loop terlalu cepat, delay sedikit biar gak makan CPU 100%
        $sleepTime = $targetSpeed - $duration;
        if ($sleepTime > 0) {
          usleep((int)($sleepTime * 1_000_000)); // usleep dalam mikrodetik
        }
        gc_collect_cycles();
    }
  }

  public static function open(string $arg, array $options = []): bool {
    global $SPECTER_ITEMS;
    global $SPECTER_STATS;

    // Bangun argumen command utama
    $arg = weaver_bind($arg, 'self', 'php ' . AETHER_FILE);
    
    $defaults = [
      'blocking' => false,
      'visible'  => true,
      'exit'     => true,
      'title'    => $arg  // Judul terminal (khusus visible = true)
    ];
    $opt = array_merge($defaults, $options);

    $SPECTER_ITEMS[] = $arg;

    $SPECTER_STATS[$arg] = true;
    keeper_set('specter.json', json_encode($SPECTER_STATS));

    $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    if ($isWin) {
      // WINDOWS MODE
      $cmdFlag = $opt['exit'] ? '/C' : '/K';
      $cmd     = "cmd $cmdFlag \"$arg\"";

      if ($opt['blocking']) {
        if ($opt['visible']) {
          shell_exec("start \"{$opt['title']}\" $cmd");
        } else {
          shell_exec($cmd);
        }
      } else {
        if ($opt['visible']) {
          pclose(popen("start \"{$opt['title']}\" $cmd", 'r'));
        } else {
          // $cmd = str_replace('cmd /C php', 'C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe', $cmd);
          // echo $cmd; die;
          pclose(popen("start \"RUNE_SPECTER\" /B $cmd", 'r'));
        }
      }

    } else {
      // UNIX / LINUX / MACOS
      $cmd = $opt['exit'] ? "$arg; exit" : "$arg; exec bash";

      if ($opt['blocking']) {
        if ($opt['visible']) {
          shell_exec("gnome-terminal --title=\"{$opt['title']}\" -- bash -c \"$cmd\"");
        } else {
          shell_exec($cmd);
        }
      } else {
        if ($opt['visible']) {
          shell_exec("gnome-terminal --title=\"{$opt['title']}\" -- bash -c \"$cmd\" > /dev/null 2>&1 &");
        } else {
          shell_exec("$cmd > /dev/null 2>&1 &");
        }
      }
    }

    return true;
  }


  public static function close(string $arg) {
    global $SPECTER_STATS;
    $KEEPER_SPECTER_STATS = json_decode(keeper_get('specter.json'), true);
    $SPECTER_STATS = array_merge($SPECTER_STATS, $KEEPER_SPECTER_STATS);
    
    $arg = weaver_bind($arg, 'self', 'php ' . AETHER_FILE);
    $SPECTER_STATS[$arg] = false;
    keeper_set('specter.json', json_encode($SPECTER_STATS));
  }

  public static function set( Array $args ) {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
      foreach ($args as $arg) {
        $arg = weaver_bind($arg, 'self', 'php ' . AETHER_FILE);
        shell_exec('start "' . strtoupper($arg) . '" cmd /c "mode con: cols=120 lines=10 && ' . $arg . ' && exit"');
      }
    }else {
      foreach ($args as $arg) {
        $arg = weaver_bind($arg, 'self', 'php ' . AETHER_FILE);
        shell_exec('echo -e "\033[8;10;120t" && ' . $arg);
      }
    }
  }

  public static function get( String $arg ) {
    global $SPECTER_STATS;
    $KEEPER_SPECTER_STATS = json_decode(keeper_get('specter.json'), true);
    $SPECTER_STATS = array_merge($SPECTER_STATS, $KEEPER_SPECTER_STATS);
    $arg = weaver_bind($arg, 'self', 'php ' . AETHER_FILE);
    return $SPECTER_STATS[$arg];
  }

}