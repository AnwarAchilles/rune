<?php

namespace Rune\Weaver;

class Phantasm extends \Rune\Phantasm {
  
  public $version = 1.0;

  public $main = 'Weaver';

  public $user = 'Anwar Achilles';

  public $note = '?';

  public $need = [];

  public $list = [
    // manifest
    [
      'type'=> 'manifest',
      'call'=> '_arise()',
      'note'=> '',
    ],
    [
      'type'=> 'manifest',
      'call'=> '_aether_awaken()',
      'note'=> '',
    ],
    [
      'type'=> 'manifest',
      'call'=> 'awaken()',
      'note'=> '',
    ],
    [
      'type'=> 'manifest',
      'call'=> 'bind( String $template, $searchOrArray, String $data="" )',
      'note'=> '',
    ],
    [
      'type'=> 'manifest',
      'call'=> 'item( $source )',
      'note'=> '',
    ],
    // ether
    [
      'type'=> 'ether',
      'call'=> 'WEAVER',
      'note'=> '',
    ],
    // essence
    [
      'type'=> 'essence',
      'call'=> '$WEAVER',
      'note'=> '',
    ],
    // entity
    [
      'type'=> 'entity',
      'call'=> 'weaver()',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'weaver_bind( $template, $search, $data )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'weaver_bind_extract( $value )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'weaver_bind_multiple( $template, $datas )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'weaver_min( $input, $type="html" )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'weaver_min_css( $input )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'weaver_min_js( $input )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'weaver_min_html( $input )',
      'note'=> '',
    ],
    [
      'type'=> 'entity',
      'call'=> 'weaver_min_php( $input )',
      'note'=> '',
    ],
  ];

  public function awakening() {}
  
}