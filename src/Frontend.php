<?php

declare(strict_types=1);

namespace Dotclear\Plugin\shortArchives;

use Dotclear\App;
use Dotclear\Core\Process;

/**
 * @brief       shortArchives frontend class.
 * @ingroup     shortArchives
 *
 * @author      annso (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Frontend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::FRONTEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        App::behavior()->addBehaviors([
            'initWidgets'       => Widgets::initWidgets(...),
            'publicHeadContent' => function (): void {
                echo
                My::jsLoad('accordion') .
                My::cssLoad('frontend');
            },
        ]);

        return true;
    }
}
