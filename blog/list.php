<?php

/**
 * Blog List
 */

$ChildrenList = new QUI\Controls\ChildrenList(array(
    'showSheets'  => true,
    'showImages'  => true,
    'showShort'   => true,
    'showHeader'  => true,
    'showContent' => false,

    'Site'  => $Site,
    'where' => array(
        'type' => 'quiqqer/blog:blog/entry'
    ),
    'limit' => $Site->getAttribute( 'quiqqer.settings.blog.max' ),

    'itemtype'       => "http://schema.org/ItemList",
    'child-itemtype' => "http://schema.org/BlogPosting"
));

$Engine->assign(array(
    'ChildrenList' => $ChildrenList
));
