<?php

namespace QUI\Blog;

class BlogList
{
    /**
     * event on child create
     *
     * @param Integer $newId
     * @param \QUI\Projects\Site\Edit $Parent
     */
    static function onChildCreate($newId, $Parent)
    {
        if ( $Parent->getAttribute( 'type' ) !== 'quiqqer/blog:blog/list' ) {
            return;
        }

        $Project = $Parent->getProject();
        $Site    = new \QUI\Projects\Site\Edit( $Project, $newId );

        $Site->setAttribute( 'nav_hide', 1 );
        $Site->setAttribute( 'release_from', date('Y-m-d H:i:s') );
        $Site->setAttribute('type', 'quiqqer/blog:blog/entry');
        $Site->save();
    }
}