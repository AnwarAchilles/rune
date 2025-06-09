<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */

function specter() {
  return true;
}

function specter_folder($path) {
  $lastModifiedTime = 0;
  foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $f) {
    if (!$f->isFile()) continue;
    $time = $f->getMTime();
    $lastModifiedTime = max($lastModifiedTime, $time);
  }
  return $lastModifiedTime;
}