<?php

use QUI\Projects\Media\Utils as MediaUtils;

/**
 * Blog List
 */

if (isset($_REQUEST['sheet'])
    && \is_numeric($_REQUEST['sheet'])
    && (int)$_REQUEST['sheet'] > 1

    || isset($_REQUEST['limit'])
) {
    $Site->setAttribute('meta.robots', 'noindex,follow');
}

$showPageContent = true;
if (isset($_REQUEST['sheet']) &&
    $Site->getAttribute('quiqqer.settings.blog.hidePageContentIfPaginationActive') ) {
    $showPageContent = false;
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

$ChildrenList->addEvent('onMetaList', function (
    QUI\Controls\ChildrenList $ChildrenList,
    QUI\Interfaces\Projects\Site $Site,
    QUI\Controls\Utils\MetaList $MetaList
) {
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
        $Image = MediaUtils::getImageByUrl($image);
        $image = $Image->getSizeCacheUrl();
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
});

$Engine->assign([
    'ChildrenList'    => $ChildrenList,
    'showPageContent' => $showPageContent
]);
