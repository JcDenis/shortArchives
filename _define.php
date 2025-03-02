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
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2025-02-24T23:31:12+00:00',
    ]
);
