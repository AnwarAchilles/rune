<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Specter;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  // create next static method

  public static function _arise() {
    self::_setEchoes();
  }
  
  private static function _setEchoes() {
    forger_fix([
      ['type'=>'item', 'target'=>SPECTER_ECHOES_SOUL],
      ['type'=>'item', 'target'=>SPECTER_ECHOES_CAST],
    ]);

  }


  public static function _aether_awaken() {
    self::awaken();
  }

  public static function awaken() {}


  public static function observer( $repo, $callback ) {
    if (!file_exists($repo)) {
      whisper_echo("{{COLOR-DANGER}}{{ICON-WARNING}}{{label-warning}} Specter Repo not exits!! {{nl}}");
      aether_exit(true);  
    }

    $index = 0;
    $last = forger_observer( $repo );

    whisper_echo("\n SPECTER {{color-danger}}::{{color-end}} OBSERVER");
    whisper_echo("\n {{color-secondary}}Your watch this directory '$repo'");
    whisper_echo("\n {{color-secondary}}Running successfully exit with [{{color-danger}}Ctrl+C{{color-end}}].\n\n");
    self::seer( function($animation) use (&$last, &$index, $repo, $callback) {
      $current = forger_observer($repo);
      
      if ($last !== $current) {
        global $AETHER_STOPWATCH;
        $AETHER_STOPWATCH = microtime(true);
        $callback();
        $index++;
        $last = $current;
      }

      if ($index > 5) {
        whisper_clear(true);
        whisper_echo("\n SPECTER {{color-danger}}::{{color-end}} OBSERVER");
        whisper_echo("\n {{color-success}}{{icon-success}}{{color-secondary}}Successfully clearing your console..");
        whisper_echo("\n {{color-secondary}}Running successfully exit with [{{color-danger}}Ctrl+C{{color-end}}].\n\n");
        $index = 0;
      }
      return false;
    });
  }

  public static function devserver( $configure ) {
    $config = (object) $configure;
    
    // Default value
    $config->host ??= '127.0.0.1';
    $config->port ??= '8000';
    $config->mode ??= 'private';
    
    // Jika mode public, ubah host ke 0.0.0.0
    if ($config->mode == 'public') {
      $config->host = '0.0.0.0';
    }
    
    // Path ke direktori
    $path = !empty($config->path) ? ' -t ' . escapeshellarg($config->path) : '';
    
    // Jika ada file router (kayak router.php)
    $file = !empty($config->file) ? ' ' . escapeshellarg($config->file) : '';
    
    // Gabungkan semua
    $command = 'php -S ' . $config->host . ':' . $config->port . $path . $file;
    
    // whisper
    whisper_echo("\n SPECTER {{color-danger}}::{{color-end}} DEVSERVER");
    whisper_echo("\n {{color-secondary}}Your local development server in http://{$config->host}:{$config->port}");
    whisper_echo("\n {{color-secondary}}Running successfully exit with [{{color-danger}}Ctrl+C{{color-end}}].\n\n");
    
    // Jalankan server
    shell_exec($command);
  }


  public static function soul( String $name, Mixed $value = NULL ) {
    if ($value) {
      specter_soul_set($name, $value);
      $return = $value;
    }else {
      $return = specter_soul_get($name);
    }

    aether_arcane("Specter.manifest.soul");
    return $return;
  }

  public static function cast( String $arg, Array $options = [] ) {
    if (is_array($options)) {
      specter_cast_set($arg, $options);
      $return = true;
    }else {
      $return = specter_cast_get($arg);
    }

    aether_arcane("Specter.manifest.cast");
    return $return;
  }
  
  public static function seer( ?Callable $callback ) {
    specter_seer_set($callback);

    aether_arcane("Specter.manifest.seer");
  }

  public static function exit( String $arg ) {
    specter_cast_save( $arg, false );
  }



  // public static function observer( $folder, $runner ) {
  //   $item_modified = 0;
  //   $base_modified = 0;
  //   $first_run = true;

  //   whisper_nl("");
  //   whisper_nl("{{COLOR-DANGER}} RUNE:SPECTER - O B S E R V E R");
  //   whisper_nl("{{COLOR-SECONDARY}} exit this observer with Ctrl+C");
  //   whisper_nl("");
  //   self::seer(function() use (&$item_modified, &$folder, &$base_modified, &$first_run, $runner) {
  //     $item_modified_watch = specter_folder($folder);
  //     $base_modified_watch = filemtime(AETHER_FILE);

  //     if (!$first_run && $item_modified_watch != $item_modified) {
  //       $runner();
  //     }

  //     if (!$first_run && $base_modified != $base_modified_watch) {
  //       $runner();
  //     }

  //     // simpan waktu terakhir + matikan flag first run
  //     $item_modified = $item_modified_watch;
  //     $base_modified = $base_modified_watch;
  //     $first_run = false;
  //   });
  // }
  
  // public static function seer( ?Callable $callback ) {
  //   $targetSpeed = 0.1;
  //   while (true) {
  //       gc_enable();
  //       $start = microtime(true);

  //       // Jalankan fungsi utama
  //       $callback();

  //       $end = microtime(true);
  //       $duration = $end - $start;

  //       // Jika loop terlalu cepat, delay sedikit biar gak makan CPU 100%
  //       $sleepTime = $targetSpeed - $duration;
  //       if ($sleepTime > 0) {
  //         usleep((int)($sleepTime * 1_000_000)); // usleep dalam mikrodetik
  //       }
  //       gc_collect_cycles();
  //   }
  // }

  // public static function open(string $arg, array $options = []): bool {
  //   global $SPECTER_ITEMS;
  //   global $SPECTER_STATS;

  //   // Bangun argumen command utama
  //   $arg = weaver_bind($arg, 'self', 'php ' . AETHER_FILE);
    
  //   $defaults = [
  //     'blocking' => false,
  //     'visible'  => true,
  //     'exit'     => true,
  //     'title'    => $arg  // Judul terminal (khusus visible = true)
  //   ];
  //   $opt = array_merge($defaults, $options);

  //   $SPECTER_ITEMS[] = $arg;

  //   $SPECTER_STATS[$arg] = true;
  //   keeper_set('specter.json', json_encode($SPECTER_STATS));

  //   $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
  //   if ($isWin) {
  //     // WINDOWS MODE
  //     $cmdFlag = $opt['exit'] ? '/C' : '/K';
  //     $cmd     = "cmd $cmdFlag \"$arg\"";

  //     if ($opt['blocking']) {
  //       if ($opt['visible']) {
  //         shell_exec("start \"{$opt['title']}\" $cmd");
  //       } else {
  //         shell_exec($cmd);
  //       }
  //     } else {
  //       if ($opt['visible']) {
  //         pclose(popen("start \"{$opt['title']}\" $cmd", 'r'));
  //       } else {
  //         // $cmd = str_replace('cmd /C php', 'C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe', $cmd);
  //         // echo $cmd; die;
  //         pclose(popen("start \"RUNE_SPECTER\" /B $cmd", 'r'));
  //       }
  //     }

  //   } else {
  //     // UNIX / LINUX / MACOS
  //     $cmd = $opt['exit'] ? "$arg; exit" : "$arg; exec bash";

  //     if ($opt['blocking']) {
  //       if ($opt['visible']) {
  //         shell_exec("gnome-terminal --title=\"{$opt['title']}\" -- bash -c \"$cmd\"");
  //       } else {
  //         shell_exec($cmd);
  //       }
  //     } else {
  //       if ($opt['visible']) {
  //         shell_exec("gnome-terminal --title=\"{$opt['title']}\" -- bash -c \"$cmd\" > /dev/null 2>&1 &");
  //       } else {
  //         shell_exec("$cmd > /dev/null 2>&1 &");
  //       }
  //     }
  //   }

  //   return true;
  // }

  // public static function close(string $arg) {
  //   global $SPECTER_STATS;
  //   $KEEPER_SPECTER_STATS = json_decode(keeper_get('specter.json'), true);
  //   $SPECTER_STATS = array_merge($SPECTER_STATS, $KEEPER_SPECTER_STATS);
    
  //   $arg = weaver_bind($arg, 'self', 'php ' . AETHER_FILE);
  //   $SPECTER_STATS[$arg] = false;
  //   keeper_set('specter.json', json_encode($SPECTER_STATS));
  // }

  // public static function set( Array $args ) {
  //   if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
  //     foreach ($args as $arg) {
  //       $arg = weaver_bind($arg, 'self', 'php ' . AETHER_FILE);
  //       shell_exec('start "' . strtoupper($arg) . '" cmd /c "mode con: cols=120 lines=10 && ' . $arg . ' && exit"');
  //     }
  //   }else {
  //     foreach ($args as $arg) {
  //       $arg = weaver_bind($arg, 'self', 'php ' . AETHER_FILE);
  //       shell_exec('echo -e "\033[8;10;120t" && ' . $arg);
  //     }
  //   }
  // }

  // public static function get( String $arg ) {
  //   global $SPECTER_STATS;
  //   $KEEPER_SPECTER_STATS = json_decode(keeper_get('specter.json'), true);
  //   $SPECTER_STATS = array_merge($SPECTER_STATS, $KEEPER_SPECTER_STATS);
  //   $arg = weaver_bind($arg, 'self', 'php ' . AETHER_FILE);
  //   return $SPECTER_STATS[$arg];
  // }

}