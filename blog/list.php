<?php

/**
 * Blog List
 */

$max   = 5;
$start = 0;

$maxsheets = 5;

$sheet_max   = 10;
$sheet_start = 0;
$sheet_end   = 0;

if ( isset( $_REQUEST['sheet'] ) )
{
    $active_sheet = (int)$_REQUEST['sheet'];
    $start        = ($active_sheet-1) * $max;

} else
{
    $active_sheet = 1;
}

$count_children = $Site->getChildren(array(
    'count'	=> 'count',
    'where' => array(
        'type' => 'quiqqer/blog:blog/entry'
    )
));

if ( is_array( $count_children ) ) {
    $count_children = count( $count_children );
}

// sheets
$sheets = ceil( $count_children / $max );

$children = $Site->getChildren(array(
    'where' => array(
        'type' => 'quiqqer/blog:blog/entry'
    ),
    'limit' => $start .','. $max
));

// sheets berechnen
$sheet_start = ($active_sheet + 3) - $sheet_max;
$sheet_end   = $sheet_start + $sheet_max;

if ( $sheet_start <= 0 )
{
    $sheet_start = 0;
    $sheet_end   = $sheet_start + $sheet_max;
}

if ( $sheet_end > $sheets ) {
    $sheet_end = $sheets;
}

$Engine->assign(array(
    'pcsg_blog_list_sheet_start' => $sheet_start,
    'pcsg_blog_list_sheet_end'   => $sheet_end,

    'pcsg_blog_list_children'   => $children,
    'pcsg_blog_list_count'	    => $count_children,
    'pcsg_blog_list_max'	    => $max,
    'pcsg_blog_list_start'	    => $start,
    'pcsg_blog_list_sheets'	    => $sheets,
    'pcsg_blog_list_active'	    => $active_sheet,
    'pcsg_blog_list_sheet_file' => OPT_DIR .'quiqqer/blog/blog/sheet.html'
));

// maximale sheets
$startsheets = 0;
$more        = false;


if ( isset( $maxsheets ) )
{
    $startsheets = 0;

    if ( !isset( $sheets ) ) {
        $sheets = false;
    }

    $count_sheets = $sheets;

    if ( isset( $_REQUEST['sheet'] ) ) {
        $startsheets = (int)$_REQUEST['sheet'];
    }

    if ( ($startsheets - 3) >= 0 )
    {
        $startsheets = ($startsheets - 3);
    } else
    {
        $startsheets = 0;
    }

    if ( ($startsheets + $maxsheets) < $sheets )
    {
        $sheets = $startsheets + $maxsheets;
        $more   = true;
    }

    $Engine->assign(array(
        'pcsg_blog_list_sheet_end'   => $sheets,
        'pcsg_blog_list_sheet_start' => $startsheets
    ));
}

$Engine->assign(array(
    'pcsg_blog_list_more'	      => $more,
    'pcsg_blog_list_count_sheets' => $count_sheets
));
