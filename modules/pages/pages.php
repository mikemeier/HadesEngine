<?php
/**
 * display pages from database
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */

class pages {
    /**
     * main action
     */
    public static function main() {
        // get page via id or url
        if(url::param('id')) {
            //$id = filter::int(url::param('id'));
            $id = url::param('id');
            $page = db::fetch_array(db::query('SELECT name, url, content FROM he'.NR.'_pages
                                               WHERE
                                                    id = '.$id));
        } else {
            // get param or use default
            if(url::param('url')) {
                $url = filter::string(url::param('url'));
            } else {
                $url = 'main';
            }
            $page = db::fetch_array(db::query('SELECT name, url, content FROM he'.NR.'_pages
                                               WHERE
                                                    url = '.$url));
        }
        // build page
        $tpl = new tpl('page');
        $tpl->set($page);
        echo $tpl->parse();
    }
}