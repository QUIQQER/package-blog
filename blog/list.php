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

/**
 * Which pages should be listed?
 * "children": List direct children only (default)
 * "all": List all blog entries from this project
 */
$byType = false;

if ($Site->getAttribute('quiqqer.settings.blog.sitesToDisplay') == 'all') {
    $byType = 'quiqqer/blog:blog/entry';
}

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
            $image = $Site->getProject()->getMedia()->getPlaceholderImage()->getSizeCacheUrl();
        } catch (QUI\Exception $Exception) {
        }
    }

    $MetaList->add('image', $image);
});

$Engine->assign([
    'ChildrenList' => $ChildrenList
]);
