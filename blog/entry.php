<?php

use QUI\Projects\Media\Utils as MediaUtils;

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
$amountOfSiblings = $Project->getConfig('blog.settings.entries.more.amount');

$moreEntriesShowDate = $Project->getConfig('blog.settings.entries.more.show_date');
$moreEntriesShowTime = $Project->getConfig('blog.settings.entries.more.show_time');

$previousSiblings = array_reverse($Site->previousSiblings($amountOfSiblings));
$nextSiblings     = $Site->nextSiblings($amountOfSiblings);

$Project->getConfig();


// Meta
$MetaList = new QUI\Controls\Utils\MetaList();
$MetaList->add('headline', $Site->getAttribute('title'));
$MetaList->add('datePublished', $Site->getAttribute('release_from'));
$MetaList->add('dateModified', $Site->getAttribute('e_date'));
$MetaList->add('mainEntityOfPage', $Site->getUrlRewritten());

// author
$User = QUI::getUsers()->get($Site->getAttribute('c_user'));
$MetaList->add('author', $User->getName());

// publisher
$Publisher = new QUI\Controls\Utils\MetaList\Publisher();
$Publisher->importFromProject($Site->getProject());
$MetaList->add('publisher', $Publisher);

// image
$image = $Site->getAttribute('image_site');

if (\strpos($image, 'fa-') !== false) {
    $image = '';
}

if (MediaUtils::isMediaUrl($image)) {
    try {
        $Image = MediaUtils::getImageByUrl($image);
        $image = $Image->getSizeCacheUrl();
    } catch (QUI\Exception $Exception) {
    }
}

// use default
if (empty($image)) {
    try {
        $Placeholder = $Site->getProject()->getMedia()->getPlaceholderImage();

        if ($Placeholder) {
            $image = $Placeholder->getSizeCacheUrl();
        }
    } catch (QUI\Exception $Exception) {
    }
}

$MetaList->add('image', $image);


$Engine->assign([
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
    'MetaList'             => $MetaList
]);
