<?php

/**
 * display pages from database
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */
class pages {

    /**
     * main action
     */
    public static function main($id=false) {
        // get page via id or url
        if(filter::isInt($id)) {
            $id = core::$db->escape($id);

            $result = core::$db->query('SELECT name, url, content FROM {PREFIX}pages WHERE id = ?', array($id));
            $page = core::$db->fetchArray($result);
        } else {
            // get param or use default
            if(is_string($id)) {
                $url = core::$db->escape($id);
            } else {
                $url = 'main';
            }

            $result = core::$db->query('SELECT name, url, content FROM {PREFIX}pages WHERE url = ?', array($url));
            $page = core::$db->fetchArray($result);
        }

        // show a 404 page if no data is served
        if(!$page) {
            $page['name'] = '404';
            $page['content'] = 'Sorry it\'s not here!';
        }

        // set page name in front of site name
        tpl::title($page['name'].' - ');

        // build page
        $tpl = new tpl('page');
        $tpl->set($page);
        echo $tpl->parse();
    }

}
