<?php
/**
 * display pages from database
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */

class pages {
    /**
     * main action
     */
    public static function main($id) {
        // get page via id or url
        if(filter::isInt($id)) {
            $page = db::fetch_array(db::query('SELECT name, url, content FROM he'.NR.'_pages
                                               WHERE
                                                    id = '.$id));
        } else {
            // get param or use default
            if($id) {
                $url = filter::string($id);
            } else {
                $url = 'main';
            }
            $page = db::fetch_array(db::query('SELECT name, url, content FROM he'.NR.'_pages
                                               WHERE
                                                    url = "'.$url.'"'));
        }

        // make 404 page if no data is served
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