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
        $Engine        = QUI::getTemplateManager()->getEngine();

//        $Engine->assign([
//            'this' => $this,
//            'html' => $html
//        ]);

        return $Engine->fetch(dirname(__FILE__) . '/Author.html');
    }
}
