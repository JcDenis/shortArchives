<?php
/**
 * @brief shortArchives, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugin
 *
 * @author annso, Pierre Van Glabeke and Contributors
 *
 * @copyright Jean-Christian Denis
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_RC_PATH') || is_null(dcCore::app()->auth)) {
    return null;
}

$this->registerModule(
    'shortArchives',
    'Display blog archives in an accordion menu, sorted by year',
    'annso, Pierre Van Glabeke and Contributors',
    '2.0.1',
    [
        'requires'    => [['core', '2.26']],
        'permissions' => dcCore::app()->auth->makePermissions([
            dcCore::app()->auth::PERMISSION_ADMIN,
        ]),
        'type'       => 'plugin',
        'support'    => 'http://forum.dotclear.org/viewtopic.php?pid=321044#p321044',
        'details'    => 'http://plugins.dotaddict.org/dc2/details/' . basename(__DIR__),
        'repository' => 'https://raw.githubusercontent.com/JcDenis/' . basename(__DIR__) . '/master/dcstore.xml',
    ]
);
