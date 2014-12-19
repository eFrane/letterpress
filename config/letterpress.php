<?php
return [
  /**
   * The locale used for all language dependent input fixes.
   * Note that this can be overridden every single time
   * simply with:
   *
   * ```php
   *   $letterpress->press($input, ['letterpress.locale' => 'en_US'])
   * ```
   *
   * Important: The provided locale must be an IETF language code.
   **/
  'locale'   => 'en_GB',

  'markdown' => [
    'enabled' => true,

    // use "markdown extra" syntax instead of parsedown's defaults
    // this only works if parsedown-extra was installed  
    'useMarkdownExtra' => false
  ],

  'markup' => [
    'enabeld' => true,

    'blockQuoteFix' => true
  ],

  'media' => [
    'enabled' => true
  ],

  'microtypography' => [
    'enabled' => true,
    'useDefaults' => true,

    // you only need to add your additional fixers here, most of
    // the stuff is handled by the defaults in config/jolitypo.php
    'additionalFixers' => []
  ]
];
