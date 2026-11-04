<?php

declare(strict_types=1);

namespace Dotclear\Plugin\shortArchives;

use Dotclear\App;
use Dotclear\Database\MetaRecord;
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
            || !$w->checkHomeOnly(App::url()->getType())
        ) {
            return '';
        }

        $rs = App::blog()->getDates(['type' => 'month']);
        if ($rs->isEmpty()) {
            return '';
        }

        $active_year = null;
        if (App::url()->getType() === 'archive'
            && App::frontend()->context()->exists('archives')
            && ($archives = App::frontend()->context()->__get('archives')) instanceof MetaRecord
            && !$archives->isEmpty()
        ) {
            $active_year = Date::dt2str(__('%Y'), $archives->strField('dt'));
        }

        $posts = [];
        while ($rs->fetch()) {
            $url = $rs->__call('url', []);

            $posts[Date::dt2str(__('%Y'), $rs->strField('dt'))][] = [
                'url'    => is_string($url) ? $url : '',
                'date'   => Html::escapeHTML(Date::dt2str(__('%B'), $rs->strField('dt'))),
                'nbpost' => $rs->intField('nb_post'),
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

        $link = $w->get('allarchivesslinktitle');
        if (App::url()->getBase('archive') && is_string($link) && $link !== '') {
            $res .= '<p><strong><a href="' . App::blog()->url() . App::url()->getURLFor('archive') . '">' .
            Html::escapeHTML($link) . '</a></strong></p>';
        }

        $class = $w->get('class');
        $title = $w->get('title');

        return $w->renderDiv(
            (bool) $w->get('content_only'),
            My::id() . ' ' . (is_string($class) ? $class : ''),
            '',
            (is_string($title) && $title !== '' ? $w->renderTitle(Html::escapeHTML($title)) : '') . $res
        );
    }
}
