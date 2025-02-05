<?php

/**
 * This file contains \QUI\Blog\BlogList
 */

namespace QUI\Blog;

use QUI\Exception;
use QUI\Interfaces\Projects\Site;
use QUI\Projects\Site\Edit;

/**
 * Blog list helper class
 *
 * @author www.pcsg.de (Henning Leutz)
 */
class BlogList
{
    /**
     * event on child create
     *
     * @param integer $newId
     * @param Site $Parent
     * @throws Exception
     */
    public static function onChildCreate(int $newId, Site $Parent): void
    {
        if ($Parent->getAttribute('type') !== 'quiqqer/blog:blog/list') {
            return;
        }

        $Project = $Parent->getProject();
        $Site = new Edit($Project, $newId);

        $Site->setAttribute('nav_hide', 1);
        $Site->save();
    }
}
