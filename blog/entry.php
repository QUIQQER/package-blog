<?php

/**
 * author and date
 */

/** @var QUI\Projects\Project $Project */
/** @var QUI\Projects\Site $Site */
/** @var QUI\Template $Template */

/** @var QUI\Interfaces\Template\EngineInterface $Engine */

use QUI\Projects\Media\Image;
use QUI\Projects\Media\Utils as MediaUtils;

// default
$enableDateAndCreator = true;
$showCreator = false;
$showDate = false;

if ($Project->getConfig('blog.settings.entry.showCreator')) {
    $showCreator = $Project->getConfig('blog.settings.entry.showCreator');
}

if ($Project->getConfig('blog.settings.entry.showDate')) {
    $showDate = $Project->getConfig('blog.settings.entry.showDate');
}

switch ($Site->getAttribute('quiqqer.settings.blog.entry.dateAndCreator')) {
    case 'showAll':
        $showCreator = true;
        $showDate = true;
        break;

    case 'showCreator':
        // hide date
        $showCreator = true;
        $showDate = false;
        break;

    case 'showDate':
        // hide author
        $showDate = true;
        $showCreator = false;
        break;

    case 'hide':
        // disable date and author
        $enableDateAndCreator = false;
        break;
}

if (!$showCreator && !$showDate) {
    $enableDateAndCreator = false;
}

/**
 * comments
 */
$Request = QUI::getRequest();
$baseUrl = $Request->getScheme() . '://' . $Request->getHttpHost() . $Request->getBasePath();
$url = $baseUrl . $_SERVER['REQUEST_URI'];

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
$nextSiblings = $Site->nextSiblings($amountOfSiblings);

$Project->getConfig();


// Meta
$MetaList = new QUI\Controls\Utils\MetaList();
$MetaList->add('type', 'BlogPosting');
$MetaList->add('headline', $Site->getAttribute('title'));
$MetaList->add('description', $Site->getAttribute('short'));
$MetaList->add('datePublished', $Site->getAttribute('release_from'));
$MetaList->add('dateModified', $Site->getAttribute('e_date'));
$MetaList->add('mainEntityOfPage', $Site->getUrlRewrittenWithHost());

/**
 * Author
 */
$quiqqerUser = $Site->getAttribute('c_user');
$userName = null;
$userAvatar = null;

// guest author enabled?
if ($Site->getAttribute('quiqqer.settings.blog.guestAuthor.enable')) {
    $guestUser = $Site->getAttribute('quiqqer.settings.blog.guestAuthor.quiqqerUser');
    $guestName = $Site->getAttribute('quiqqer.settings.blog.guestAuthor.name');
    $guestAvatar = $Site->getAttribute('quiqqer.settings.blog.guestAuthor.avatar');

    if ($guestUser) {
        $quiqqerUser = $guestUser;
    } elseif ($guestName) {
        $userName = $guestName;
        $quiqqerUser = null;

        if ($guestAvatar) {
            $userAvatar = $guestAvatar;
        }
    }
}

if ($quiqqerUser) {
    try {
        $User = QUI::getUsers()->get($quiqqerUser);
        $MetaList->add('author', $User->getName());
        $Engine->assign('author', $User->getName());
    } catch (QUI\Exception $Exception) {
        QUI\System\Log::addInfo($Exception->getMessage(), [
            'project' => $Project->getName(),
            'lang' => $Project->getLang(),
            'site' => $Site->getId()
        ]);
        $Engine->assign('author', null);
    }
} else {
    $MetaList->add('author', $userName);
    $Engine->assign('author', $userName);
}


// publisher
$Publisher = new QUI\Controls\Utils\MetaList\Publisher();
$Publisher->importFromProject($Site->getProject());
$MetaList->add('publisher', $Publisher);

// image
$image = $Site->getAttribute('image_site');
$imageAbsolutePath = '';
$host = QUI::getRequest()->getHost();
$scheme = QUI::getRequest()->getScheme();

if (str_contains($image, 'fa-')) {
    $image = '';
}

if (MediaUtils::isMediaUrl($image)) {
    try {
        $Image = MediaUtils::getImageByUrl($image);
        // structured data needs absolute urls for images
        $imageAbsolutePath = $scheme . '://' . $host . $Image->getSizeCacheUrl();
    } catch (QUI\Exception $Exception) {
    }
}

// use default
if (empty($imageAbsolutePath)) {
    try {
        $Placeholder = $Site->getProject()->getMedia()->getPlaceholderImage();

        if ($Placeholder instanceof Image) {
            // structured data needs absolute urls for images
            $imageAbsolutePath = $scheme . '://' . $host . $Placeholder->getSizeCacheUrl();
        }
    } catch (QUI\Exception $Exception) {
    }
}

if (!empty($imageAbsolutePath)) {
    $MetaList->add('image', $imageAbsolutePath);
}

$Engine->assign([
    'enableDateAndCreator' => $enableDateAndCreator,
    'showCreator' => $showCreator,
    'showDate' => $showDate,
    'comments' => $comments,
    'type' => $type,
    'url' => $url,
    'pageIdentifier' => $pageIdentifier,
    'disqusLink' => $Project->getConfig('blog.settings.disqus.link') . '/embed.js',
    'fbLangParam' => $fbLangParam,
    'numberOfPosts' => $Project->getConfig('blog.settings.facebook.numberOfPosts'),
    'apiVer' => $Project->getConfig('blog.settings.facebook.apiVer'),
    'appId' => $Project->getConfig('blog.settings.facebook.appId'),
    'moreEntriesShowDate' => $moreEntriesShowDate,
    'moreEntriesShowTime' => $moreEntriesShowTime,
    'previousSiblings' => $previousSiblings,
    'nextSiblings' => $nextSiblings,
    'MetaList' => $MetaList,
    'showTitle' => $Project->getConfig('blog.settings.entry.showTitle'),
    'showDescription' => $Project->getConfig('blog.settings.entry.showDescription')
]);

// json schema
try {
    $Template->extendHeader($MetaList->getJsonLdSchema());
} catch (\QUI\Exception $e) {
    QUI\System\Log::addWarning($e->getMessage());
}
