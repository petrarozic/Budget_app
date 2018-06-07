<?php
   /******************************
   * trazim zadnju rijec u URL. postupak:
   * 1. razlomim URL pomocu / --> explode('/', $url)
   * 2. nadem koliko je znakova / --> substr_count($url, '/')
   * 3. pamtim zadnji nastavak u lomljenju
   * NAP: home i profile imaju drugaciji URL ( 'index.php?rt=home' ) pa lomim na zadnjem znaku '='
          --> mozda ima pametnije rjesenje?
   ******************************/
   $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
   $extension= explode('/', $url)[substr_count($url, '/')];

   if (substr_count($extension, 'index.php') !== 0) {
     $extension= explode('=', $extension)[substr_count($url, '=')];
   }
?>
