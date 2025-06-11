<?php

/*
 * ESSENCE
 * Represents globals and base data for this domain
 *
 * example:
 * $GLOBALS['STARTER'] = '';
 */

$GLOBALS['AETHER_STOPWATCH'] = microtime(true);

$GLOBALS['AETHER_ARCANE_STOPWATCH'] = microtime(true);

$GLOBALS['AETHER_ARCANE_STOPWATCH_STEP'] = microtime(true);

$GLOBALS['AETHER_ARISED'] = [];

$GLOBALS['AETHER_PHANTASM'] = [];

$GLOBALS['AETHER_FAMILIAR'] = false;



/* ARCANE
 *  */
$GLOBALS['AETHER_ARCANE_ITEMS'] = [];

$GLOBALS['AETHER_ARCANE_STATE'] = [
  [0.000099, 'BURST'],          // Super instan (sub-ms)
  [0.00099, 'BLINK'],          // Nyaris ga terasa
  [0.0099, 'FLASH'],          // Kilat terlihat
  [0.099, 'FLOW'],           // Stabil dan mengalir
  [0.300, 'GLIDE'],          // Mulai berat tapi lembut
  [0.500, 'FADE'],           // Energi mulai menghilang
  [1.000, 'FALL'],           // Penurunan besar terasa
  [1.500, 'DRAIN'],          // Energi ditarik perlahan
  [2.500, 'BREAK'],          // Sistem mulai rusak
  [3.500, 'COLLAPSE'],       // Hampir jatuh total
  [4.000, 'SHATTER'],        // Pecah dan tak stabil
  [5.000, 'OVERCLOCK'],      // Push limit sistem
  [10.000, 'OVERLOAD'],      // Ledakan sihir performa!
  [50.000, 'DEAD'],          // Mati. Beku. Timeout.
];



