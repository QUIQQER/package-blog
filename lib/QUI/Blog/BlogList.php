<?php

/**
 * This file contains \QUI\Blog\BlogList
 */

namespace QUI\Blog;

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
     * @param \QUI\Projects\Site\Edit $Parent
     */
    public static function onChildCreate($newId, $Parent)
    {
        if ($Parent->getAttribute('type') !== 'quiqqer/blog:blog/list') {
            return;
        }

        $Project = $Parent->getProject();
        $Site    = new \QUI\Projects\Site\Edit($Project, $newId);

        $Site->setAttribute('nav_hide', 1);
        $Site->setAttribute('release_from', date('Y-m-d H:i:s'));
        $Site->save();
    }
}
