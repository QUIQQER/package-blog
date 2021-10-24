<?php

/**
 * This file contains \QUI\Bricks\Controls\TextAndImageMultiple
 */

namespace QUI\Blog\Controls;

use QUI;

/**
 * Class Author
 *
 * @author  Dominik Chrzanowski
 * @package quiqqer/bricks
 */
class Author extends QUI\Control
{
    /**
     * constructor
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->addCSSFile(
            dirname(__FILE__) . '/Author.css'
        );
    }

    /**
     * (non-PHPdoc)
     *
     * @see \QUI\Control::create()
     */
    public function getBody()
    {
        $Engine = QUI::getTemplateManager()->getEngine();
        $Site   = $this->getSite();

        if ($Site->getAttribute("type") !== 'quiqqer/blog:blog/entry') {
            return '';
        }

        // author
        $User      = QUI::getUsers()->get($Site->getAttribute('c_user'));
        $userName  = $User->getName();
        $UserImage = $User->getAvatar()->getAttribute("url");

        if ($Site->getAttribute('quiqqer.settings.guestAuthor')) {

            $userName = $Site->getAttribute('quiqqer.settings.guestAuthor.name');
            $UserImage = $Site->getAttribute('quiqqer.settings.guestAuthor.avatar');

            if ($Site->getAttribute('quiqqer.settings.guestAuthor.list')) {
                $list = QUI::getUsers()->get($Site->getAttribute('quiqqer.settings.guestAuthor.list'));

                $userName  = $list->getName();
                $UserImage = $list->getAvatar()->getAttribute("url");
            }
        }

        $Engine->assign([
            'AuthorImage' => $UserImage,
            'authorName'  => $userName,
        ]);

        return $Engine->fetch(dirname(__FILE__) . '/Author.html');
    }

    /**
     * @return mixed|QUI\Projects\Site
     *
     * @throws QUI\Exception
     */
    protected function getSite()
    {
        if ($this->getAttribute('Site')) {
            return $this->getAttribute('Site');
        }

        $Site = QUI::getRewrite()->getSite();

        $this->setAttribute('Site', $Site);

        return $Site;
    }
}
