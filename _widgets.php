<?php
/**
 * @brief shortArchives, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugin
 *
 * @author annso, Pierre Van Glabeke and Contributors
 *
 * @copyright Jean-Crhistian Denis
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_RC_PATH')) {
    return;
}

dcCore::app()->addBehavior('initWidgets', ['shortArchivesWidgets','initWidgets']);

class shortArchivesWidgets
{
    public static function initWidgets($w)
    {
        $w->create(
            'shortArchives',
            __('Short Archives'),
            ['shortArchivesWidgets', 'shortArchivesWidgets'],
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

    public static function shortArchivesWidgets($w)
    {
        if ($w->offline) {
            return;
        }

        if (!$w->checkHomeOnly(dcCore::app()->url->type)) {
            return;
        }

        $rs = dcCore::app()->blog->getDates(['type' => 'month']);
        if ($rs->isEmpty()) {
            return;
        }

        $active_year = null;
        if ((dcCore::app()->url->type == 'archive') && preg_match('`^/([0-9]{4})/([0-9]{2})$`', (string) dcCore::app()->url->args, $matches)) {
            $active_year = $matches[1];
        }

        $posts = [];
        while ($rs->fetch()) {
            $posts[dt::dt2str(__('%Y'), $rs->dt)][] = [
                'url'    => $rs->url(),
                'date'   => html::escapeHTML(dt::dt2str(__('%B'), $rs->dt)),
                'nbpost' => $rs->nb_post,
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
                    ($w->postcount ? ' (' . $post[$i]['nbpost'] . ')' : '') .
                    '</li>';
            }
            $res .= '</ul></li>';
        }
        $res .= '</ul>';

        if (dcCore::app()->url->getBase('archive') && !is_null($w->allarchivesslinktitle) && $w->allarchivesslinktitle !== '') {
            $res .= '<p><strong><a href="' . dcCore::app()->blog->url . dcCore::app()->url->getURLFor('archive') . '">' .
            html::escapeHTML($w->allarchivesslinktitle) . '</a></strong></p>';
        }

        return $w->renderDiv(
            $w->content_only,
            'shortArchives ' . $w->class,
            '',
            ($w->title ? $w->renderTitle(html::escapeHTML($w->title)) : '') . $res
        );
    }
}
