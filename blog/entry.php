<?php

/**
 * autor and date
 */

// default
$enableDateAndCreator = true;
$showCreator          = true;
$showDate             = true;

switch ($Site->getAttribute('quiqqer.settings.blog.entry.dateAndCreator')) {
    case 'showCreator':
        $showDate = false; // hide date
        break;
    case 'showDate':
        $showCreator = false; // hide author
        break;
    case 'hide':
        $enableDateAndCreator = false; // disable date and author
        break;
}

/**
 * comments
 */
$Request = QUI::getRequest();
$baseUrl = $Request->getScheme() . '://' . $Request->getHttpHost() . $Request->getBasePath();
$url     = $baseUrl . $_SERVER['REQUEST_URI'];

$fbLangParam = $Site->getProject()->getLang() . '_';
$fbLangParam .= strtoupper($Site->getProject()->getLang());

$pageIdentifier = $Site->getProject()->getName() . '-';
$pageIdentifier .= $Site->getProject()->getLang() . '-';
$pageIdentifier .= $Site->getId();

// disable comments on one page
$comments = false;
if ($Site->getAttribute('quiqqer.settings.blog.entry.comments') != 'disable') {
    $comments = $Project->getConfig('blog.settings.general.enableComments');
}

$type = 'disqus';
if ($Project->getConfig('blog.settings.general.type')) {
    $type = $Project->getConfig('blog.settings.general.type');
}

$numberOfPosts = 6;
if ($Project->getConfig('blog.settings.facebook.numberOfPosts')) {
    $numberOfPosts = $Project->getConfig('blog.settings.facebook.numberOfPosts');
}


/**
 * More blog entries
 */
$amountOfSiblings    = $Project->getConfig('blog.settings.entries.more.amount');
$moreEntriesShowDate = $Project->getConfig('blog.settings.entries.more.show_date');
$moreEntriesShowTime = $Project->getConfig('blog.settings.entries.more.show_time');

$previousSiblings = array_reverse($Site->previousSiblings($amountOfSiblings));
$nextSiblings     = $Site->nextSiblings($amountOfSiblings);


$Project->getConfig();


$Engine->assign(array(
    'enableDateAndCreator' => $enableDateAndCreator,
    'showCreator'          => $showCreator,
    'showDate'             => $showDate,
    'comments'             => $comments,
    'type'                 => $type,
    'url'                  => $url,
    'pageIdentifier'       => $pageIdentifier,
    'disqusLink'           => $Project->getConfig('blog.settings.disqus.link') . '/embed.js',
    'fbLangParam'          => $fbLangParam,
    'numberOfPosts'        => $Project->getConfig('blog.settings.facebook.numberOfPosts'),
    'apiVer'               => $Project->getConfig('blog.settings.facebook.apiVer'),
    'appId'                => $Project->getConfig('blog.settings.facebook.appId'),
    'moreEntriesShowDate'  => $moreEntriesShowDate,
    'moreEntriesShowTime'  => $moreEntriesShowTime,
    'previousSiblings'     => $previousSiblings,
    'nextSiblings'         => $nextSiblings,
));
