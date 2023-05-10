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
declare(strict_types=1);

namespace Dotclear\Plugin\shortArchives;

use dcCore;
use dcNsProcess;
use dcUtils;

class Frontend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_RC_PATH');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        dcCore::app()->addBehaviors([
            'initWidgets'       => [Widgets::class, 'initWidgets'],
            'publicHeadContent' => function (): void {
                echo
                dcUtils::jsModuleLoad(My::id() . '/js/accordion.js') .
                dcUtils::cssModuleLoad(My::id() . '/css/frontend.css');
            },
        ]);

        return true;
    }
}
