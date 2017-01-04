<?php

/**
 * Blog List
 */

$ChildrenList = new QUI\Controls\ChildrenList(array(
    'showContent'    => false,
    'showCreator'    => $Site->getAttribute('quiqqer.settings.blog.showCreator'),
    'showTime'       => $Site->getAttribute('quiqqer.settings.news.showTime'), // sich endgültig entscheiden:
    'showDate'       => $Site->getAttribute('quiqqer.settings.news.showTime'), // showDate vs showTime
    'Site'           => $Site,
    'where'          => array(
        'type' => 'quiqqer/blog:blog/entry'
    ),
    'limit'          => $Site->getAttribute('quiqqer.settings.blog.max'),
    'itemtype'       => "http://schema.org/ItemList",
    'child-itemtype' => "http://schema.org/BlogPosting",
    'display'        => $Site->getAttribute('quiqqer.settings.blog.template')
));

$Engine->assign(array(
    'ChildrenList' => $ChildrenList
));
