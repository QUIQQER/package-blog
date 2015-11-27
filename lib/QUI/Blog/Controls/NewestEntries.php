<?php

/**
 * This file contains QUI\Blog\Controls\NewestEntries
 */
namespace QUI\Blog\Controls;

use QUI;

/**
 * Class NewestEntries
 *
 * @package quiqqer/blog
 */
class NewestEntries extends QUI\Control
{
    /**
     * constructor
     *
     * @param Array $attributes
     */
    public function __construct($attributes = array())
    {
        // default options
        $this->setAttributes(array(
            'max'        => 5,
            'Project'    => false,
            'class'      => 'quiqqer-blog-newestentries',
            'dateFormat' => '%d',
            'dayFormat'  => '%a'
        ));

        parent::setAttributes($attributes);

        $this->addCSSFile(
            dirname(__FILE__) . '/NewestEntries.css'
        );
    }

    /**
     * (non-PHPdoc)
     *
     * @see \QUI\Control::create()
     */
    public function getBody()
    {
        $Engine  = QUI::getTemplateManager()->getEngine();
        $Project = $this->_getProject();

        $children = $Project->getSites(array(
            'where' => array(
                'type' => 'quiqqer/blog:blog/entry'
            ),
            'limit' => (int)$this->getAttribute('max'),
            'order' => 'release_from DESC'
        ));


        $Engine->assign(array(
            'Rewrite'  => QUI::getRewrite(),
            'children' => $children,
            'this'     => $this
        ));

        return $Engine->fetch(dirname(__FILE__) . '/NewestEntries.html');
    }
}
