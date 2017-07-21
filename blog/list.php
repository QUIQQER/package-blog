<?php

/**
 * Blog List
 */

$ChildrenList = new QUI\Controls\ChildrenList(array(
    'showContent'    => false,
    'showImages'     => $Site->getAttribute('quiqqer.settings.blog.showImages'),
    'showHeader'     => $Site->getAttribute('quiqqer.settings.blog.showHeader'),
    'showShort'      => $Site->getAttribute('quiqqer.settings.blog.showShort'),
    'showCreator'    => $Site->getAttribute('quiqqer.settings.blog.showCreator'),
    'showTime'       => $Site->getAttribute('quiqqer.settings.blog.showTime'),
    'showDate'       => $Site->getAttribute('quiqqer.settings.blog.showDate'),
    'Site'           => $Site,
    'where'          => array(
        'type' => 'quiqqer/blog:blog/entry'
    ),
    'limit'          => $Site->getAttribute('quiqqer.settings.blog.max'),
    'itemtype'       => 'http://schema.org/Blog',
    'child-itemtype' => 'http://schema.org/BlogPosting',
    'child-itemprop' => 'blogPost',
    'display'        => $Site->getAttribute('quiqqer.settings.blog.template')
));

$Engine->assign(array(
    'ChildrenList' => $ChildrenList
));
