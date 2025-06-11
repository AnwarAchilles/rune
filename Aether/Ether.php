<?php

/*
 * ETHER
 * Represents constants and rules for this domain.
 */

define('AETHER', true);

define('AETHER_FILE', $_SERVER['PHP_SELF']);

define('AETHER_REPO', getcwd());

define('AETHER_VERSION', '1.0.0');

define('AETHER_COPYRIGHT', 'RUNE {{COPYRIGHT-VERSION}} | Created By @anwarachilles');

define('AETHER_RUNE_LOCATION', realpath(__DIR__.'/../'));

define('AETHER_SELF_WEAVER', __DIR__.'/weaver/');

define('AETHER_LOGS', AETHER_REPO.'/.echoes/logs/');

define('AETHER_ECHOES', AETHER_REPO.'/.echoes/');

define('AETHER_ECHOES_RUNE', AETHER_ECHOES . '/' . AETHER_FILE . '/');

define('AETHER_ECHOES_INFORMATION', AETHER_ECHOES_RUNE.'/information.json');

define('AETHER_ECHOES_ARCANE', AETHER_ECHOES_RUNE.'/arcane.txt');

define('AETHER_ECHOES_ARCANES', AETHER_ECHOES_RUNE.'/arcanes.txt');

define('AETHER_ECHOES_ARTEFACT', AETHER_ECHOES_RUNE.'/artefacts/');

define('AETHER_PHP_VERSION', '8.1.10');


/* ECHOES
 * migration to Keeper, only keeper have access to echoes
 *  */


/* PHANTASM
 * how to use bitwise
 * */
define('AETHER_PHANTASM_MANIFEST', \Rune\Manifest::mana());

define('AETHER_PHANTASM_ETHER', \Rune\Manifest::mana());

define('AETHER_PHANTASM_ESSENCE', \Rune\Manifest::mana());

define('AETHER_PHANTASM_ENTITY', \Rune\Manifest::mana());

