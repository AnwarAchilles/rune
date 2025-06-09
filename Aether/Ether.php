<?php

/*
 * ETHER
 * Represents constants and rules for this domain.
 *
 * example:
 * define('STARTER', '');
 */

define('AETHER_FILE', $_SERVER['PHP_SELF']);

define('AETHER_REPO', getcwd());

define('AETHER_VERSION', '1.0.0');

define('AETHER_COPYRIGHT', 'RUNE {{COPYRIGHT-VERSION}} | Created By @anwarachilles');

define('AETHER_RUNE_LOCATION', realpath(__DIR__.'/../'));

define('AETHER_SELF_WEAVER', __DIR__.'/weaver/');

define('AETHER_LOGS', AETHER_REPO.'/.echoes/logs/');

define('AETHER_ECHOES', AETHER_REPO.'/.echoes/artefacts/');

define('AETHER_ECHOES_ARTEFACT', AETHER_REPO.'/.echoes/artefacts/');

define('AETHER_PHP_VERSION', '8.1.10');