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
use Dotclear\Helper\Date;
use Dotclear\Helper\Html\Html;
use Dotclear\Plugin\widgets\WidgetsStack;
use Dotclear\Plugin\widgets\WidgetsElement;

class Widgets
{
    public static function initWidgets(WidgetsStack $w): void
    {
        #Top c
        $w->create(
            My::id(),
            My::name(),
            [self::class, 'parseWidget'],
            null,
            __('Blog Archive List an accordion menu, sorted by year')
        )
        ->addTitle(__('Archives'))
        ->setting('postcount', __('With entries counts'), 1, 'check')
        ->setting('allarchivesslinktitle', __('Link to all archives:'), __('All archives'))
        ->addHomeOnly()
        ->addContentOnly()
        ->addClass()
        ->addOffline();
    }

    public static function parseWidget(WidgetsElement $w): string
    {
        // nullsafe
        if (is_null(dcCore::app()->blog)) {
            return '';
        }

        if ($w->__get('offline') || !$w->checkHomeOnly(dcCore::app()->url->type)) {
            return '';
        }

        $rs = dcCore::app()->blog->getDates(['type' => 'month']);
        if ($rs->isEmpty()) {
            return '';
        }

        $active_year = null;
        if ((dcCore::app()->url->type == 'archive') && preg_match('`^/([0-9]{4})/([0-9]{2})$`', (string) dcCore::app()->url->args, $matches)) {
            $active_year = $matches[1];
        }

        $posts = [];
        while ($rs->fetch()) {
            $posts[Date::dt2str(__('%Y'), $rs->f('dt'))][] = [
                'url'    => $rs->__call('url', []),
                'date'   => Html::escapeHTML(Date::dt2str(__('%B'), $rs->f('dt'))),
                'nbpost' => $rs->f('nb_post'),
            ];
        }

        $res = '<ul class="arch-years">';

        foreach ($posts as $annee => $post) {
            if (!is_null($active_year) && $active_year == $annee) {
                $res .= '<li class="open">';
            } else {
                $res .= '<li>';
            }
            $res .= '<span>' . $annee . '</span><ul class="arch-months">';
            for ($i = 0; $i < sizeof($post); $i++) {
                $res .= '<li><a href="' . $post[$i]['url'] . '">' . $post[$i]['date'] . '</a>' .
                    ($w->__get('postcount') ? ' (' . $post[$i]['nbpost'] . ')' : '') .
                    '</li>';
            }
            $res .= '</ul></li>';
        }
        $res .= '</ul>';

        if (dcCore::app()->url->getBase('archive') && !is_null($w->__get('allarchivesslinktitle')) && $w->__get('allarchivesslinktitle') !== '') {
            $res .= '<p><strong><a href="' . dcCore::app()->blog->url . dcCore::app()->url->getURLFor('archive') . '">' .
            Html::escapeHTML($w->__get('allarchivesslinktitle')) . '</a></strong></p>';
        }

        return $w->renderDiv(
            (bool) $w->__get('content_only'),
            My::id() . ' ' . $w->__get('class'),
            '',
            ($w->__get('title') ? $w->renderTitle(Html::escapeHTML($w->__get('title'))) : '') . $res
        );
    }
}
