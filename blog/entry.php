<?php

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

$Engine->assign(array(
    'comments'       => $comments,
    'type'           => $type,
    'baserUrl'       => $baseUrl,
    'url'            => $url,
    'pageIdentifier' => $pageIdentifier,
    'disqusLink'     => $Project->getConfig('blog.settings.disqus.link') . '/embed.js',
    'fbLangParam'    => $fbLangParam,
    'numberOfPosts'  => $Project->getConfig('blog.settings.facebook.numberOfPosts'),
    'apiVer'         => $Project->getConfig('blog.settings.facebook.apiVer'),
    'appId'          => $Project->getConfig('blog.settings.facebook.appId')
));
