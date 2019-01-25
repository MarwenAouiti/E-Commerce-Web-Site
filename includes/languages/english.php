<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 22/11/2018
 * Time: 00:34
 */

function lang($phrase) {

    static $lang = array(

        'HOME_ADMIN'    => 'Home',
        'CATEGORIES'    => 'Categories',
        'ITEMS'         => 'Items',
        'MEMBERS'       => 'Members',
        'COMMENTS'      => 'Comments',
        'STATISTICS'    => 'Statistics',
        'LOGS'          => 'Logs',
        '' => '',
        '' => '',
        '' => '',
        '' => ''

    );
    return $lang[$phrase];
}