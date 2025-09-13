<?php

declare(strict_types=1);

namespace Dotclear\Plugin\shortArchives;

use Dotclear\App;
use Dotclear\Helper\Date;
use Dotclear\Helper\Html\Html;
use Dotclear\Plugin\widgets\WidgetsStack;
use Dotclear\Plugin\widgets\WidgetsElement;

/**
 * @brief       shortArchives widgets class.
 * @ingroup     shortArchives
 *
 * @author      annso (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Widgets
{
    public static function initWidgets(WidgetsStack $w): void
    {
        #Top c
        $w->create(
            My::id(),
            My::name(),
            self::parseWidget(...),
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
        if (!App::blog()->isDefined()
            || $w->get('offline')
            || !$w->checkHomeOnly(App::url()->type)
        ) {
            return '';
        }

        $rs = App::blog()->getDates(['type' => 'month']);
        if ($rs->isEmpty()) {
            return '';
        }

        $active_year = null;
        if ((App::url()->type == 'archive') && preg_match('`^/([0-9]{4})/([0-9]{2})$`', (string) App::url()->args, $matches)) {
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
                    ($w->get('postcount') ? ' (' . $post[$i]['nbpost'] . ')' : '') .
                    '</li>';
            }
            $res .= '</ul></li>';
        }
        $res .= '</ul>';

        if (App::url()->getBase('archive') && !is_null($w->get('allarchivesslinktitle')) && $w->get('allarchivesslinktitle') !== '') {
            $res .= '<p><strong><a href="' . App::blog()->url() . App::url()->getURLFor('archive') . '">' .
            Html::escapeHTML($w->get('allarchivesslinktitle')) . '</a></strong></p>';
        }

        return $w->renderDiv(
            (bool) $w->get('content_only'),
            My::id() . ' ' . $w->get('class'),
            '',
            ($w->get('title') ? $w->renderTitle(Html::escapeHTML($w->get('title'))) : '') . $res
        );
    }
}
