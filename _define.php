<?php
/**
 * @file
 * @brief       The plugin shortArchives definition
 * @ingroup     shortArchives
 *
 * @defgroup    shortArchives Plugin shortArchives.
 *
 * Display blog archives in an accordion menu, sorted by year.
 *
 * @author      annso (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

$this->registerModule(
    'shortArchives',
    'Display blog archives in an accordion menu, sorted by year',
    'annso, Pierre Van Glabeke and Contributors',
    '2.2',
    [
        'requires'    => [['core', '2.28']],
        'permissions' => 'My',
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . basename(__DIR__) . '/issues',
        'details'     => 'https://github.com/JcDenis/' . basename(__DIR__) . '/src/branch/master/README.md',
        'repository'  => 'https://github.com/JcDenis/' . basename(__DIR__) . '/raw/branch/master/dcstore.xml',
    ]
);
