<?php

/**
 * Blog List
 */

if (isset($_REQUEST['sheet'])
    && \is_numeric($_REQUEST['sheet'])
    && (int)$_REQUEST['sheet'] > 1) {
    $Site->setAttribute('meta.robots', 'noindex,follow');
}

/**
 * Which pages should be listed?
 * "children": List direct children only (default)
 * "all": List all blog entries from this project
 */
$byType = false;

if ($Site->getAttribute('quiqqer.settings.blog.sitesToDisplay') == 'all') {
    $byType = 'quiqqer/blog:blog/entry';
}

$ChildrenList = new QUI\Controls\ChildrenList([
    'showContent'    => false,
    'showImages'     => $Site->getAttribute('quiqqer.settings.blog.showImages'),
    'showHeader'     => $Site->getAttribute('quiqqer.settings.blog.showHeader'),
    'showShort'      => $Site->getAttribute('quiqqer.settings.blog.showShort'),
    'showCreator'    => $Site->getAttribute('quiqqer.settings.blog.showCreator'),
    'showTime'       => $Site->getAttribute('quiqqer.settings.blog.showTime'),
    'showDate'       => $Site->getAttribute('quiqqer.settings.blog.showDate'),
    'Site'           => $Site,
    'byType'         => $byType,
    'where'          => [
        'type' => 'quiqqer/blog:blog/entry'
    ],
    'limit'          => $Site->getAttribute('quiqqer.settings.blog.max'),
    'itemtype'       => 'http://schema.org/Blog',
    'child-itemtype' => 'http://schema.org/BlogPosting',
    'child-itemprop' => 'blogPost',
    'display'        => $Site->getAttribute('quiqqer.settings.blog.template')
]);

$Engine->assign([
    'ChildrenList' => $ChildrenList
]);
