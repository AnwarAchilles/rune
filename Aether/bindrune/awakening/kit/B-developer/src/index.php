<?php

/* Nirvana PHP
 * full documentation here
 * https://github.com/AnwarAchilles/nirvana-native-php
 * */

Nirvana::environment([
  'configure'=> [
    'development'=> true
  ]
]);


// rest api example product
Nirvana::rest('GET', 'api/product', function() {
  $products = [
    [
      'id'=> 1,
      'name'=> 'Product 1'
    ],
    [
      'id'=> 2,
      'name'=> 'Product 2'
    ],
    [
      'id'=> 3,
      'name'=> 'Product 3'
    ],
  ];
  return $products;
});