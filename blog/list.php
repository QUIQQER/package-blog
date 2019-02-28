<?php

/**
 * Blog List
 */

$ChildrenList = new QUI\Controls\ChildrenList([
    'showContent'    => false,
    'showImages'     => $Site->getAttribute('quiqqer.settings.blog.showImages'),
    'showHeader'     => $Site->getAttribute('quiqqer.settings.blog.showHeader'),
    'showShort'      => $Site->getAttribute('quiqqer.settings.blog.showShort'),
    'showCreator'    => $Site->getAttribute('quiqqer.settings.blog.showCreator'),
    'showTime'       => $Site->getAttribute('quiqqer.settings.blog.showTime'),
    'showDate'       => $Site->getAttribute('quiqqer.settings.blog.showDate'),
    'Site'           => $Site,
    'byType'         => 'quiqqer/blog:blog/entry',
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
