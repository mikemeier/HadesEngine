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
    public static function main() {
        // get page via id or url
        if (url::param('id')) {
            $id = filter::int(url::param('id'));

            $result = core::$db->query('SELECT name, url, content FROM {PREFIX}pages WHERE id = ?', array($id));
            $page = core::$db->fetchArray($result);
        } else {
            // get param or use default
            if (url::param('url')) {
                $url = filter::string(url::param('url'));
            } else {
                $url = 'main';
            }

            $result = core::$db->query('SELECT name, url, content FROM {PREFIX}pages WHERE url = ?', array($url));
            $page = core::$db->fetchArray($result);
        }

        // show a 404 page if no data is served
        if (!$page) {
            $page['name'] = '404';
            $page['content'] = 'Sorry it\'s not here!';
        }

        // set page name in front of site name
        tpl::title("{$page['name']} - ");

        // build page
        $tpl = new tpl('page');
        $tpl->set($page);
        echo $tpl->parse();
    }
}
