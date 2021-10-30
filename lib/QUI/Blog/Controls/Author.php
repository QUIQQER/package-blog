<?php

/**
 * This file contains \QUI\Bricks\Controls\TextAndImageMultiple
 */

namespace QUI\Blog\Controls;

use QUI;
use QUI\Exception;

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
        // default options
        $this->setAttributes([
            'class'    => 'quiqqer-blog-control-author',
            'template' => 'largeImageTop' // template
        ]);

        $this->addCSSFile(dirname(__FILE__).'/Author.css');

        parent::__construct($attributes);
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

        switch ($this->getAttribute('template')) {
            case 'largeImageLeft':
                $html = '/Author.LargeImageLeft.html';
                break;

            case 'smallImageLeft':
                $html = '/Author.SmallImageLeft.html';
                break;

            case 'largeImageTop':
            default:
                $html = '/Author.LargeImageTop.html';
                break;
        }

        try {
            $authorData = $this->getAuthorData();
        } catch (Exception $Exception) {
            QUI\System\Log::addInfo($Exception->getMessage());

            $authorData = [
                'name' => false,
                'Image' => false
            ];
        }

        $Engine->assign([
            'this'        => $this,
            'authorName'  => $authorData['name'],
            'AuthorImage' => $authorData['Image'],
            'shortDesc'   => false // todo implement user short description
        ]);

        $Engine->assign('controlTemplate', $Engine->fetch(dirname(__FILE__).$html));

        return $Engine->fetch(dirname(__FILE__).$html);
    }

    /**
     * @return array|bool
     *  'name' => string
     *  'Image' => Object
     *
     * @throws Exception
     * @throws QUI\Users\Exception
     */
    private function getAuthorData()
    {
        $Site = $this->getSite();

        if ($Site->getAttribute('quiqqer.settings.blog.guestAuthor.enable')) {
            $data = $this->getGuestUserData();

            if ($data) {
                return $data;
            }
        }

        $User  = QUI::getUsers()->get($Site->getAttribute('c_user'));
        $name  = $User->getName();
        $Image = $User->getAvatar()->getAttribute("url");

        return [
            'name'  => $name,
            'Image' => $Image
        ];
    }


    /**
     * Get data for guest author
     *
     * @return array|bool
     *  'name' => string
     *  'Image' => Object
     *
     * @throws QUI\Exception
     * @throws QUI\Users\Exception
     */
    protected function getGuestUserData()
    {
        $Site = $this->getSite();

        if ($Site->getAttribute('quiqqer.settings.blog.guestAuthor.quiqqerUser')) {
            $QuiqqerUser = QUI::getUsers()->get($Site->getAttribute('quiqqer.settings.blog.guestAuthor.quiqqerUser'));

            try {
                return [
                    'name'  => $QuiqqerUser->getName(),
                    'Image' => $QuiqqerUser->getAvatar()->getAttribute("url")
                ];
            } catch (Exception $Exception) {
                QUI\System\Log::addInfo($Exception->getMessage());
            }
        }

        $name  = $Site->getAttribute('quiqqer.settings.blog.guestAuthor.name');
        $Image = $Site->getAttribute('quiqqer.settings.blog.guestAuthor.avatar');

        if (!$name) {
            return false;
        }

        return [
            'name'  => $name,
            'Image' => $Image
        ];
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
