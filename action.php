<?php
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');


class action_plugin_notfound extends DokuWiki_Action_Plugin {

    function register(Doku_Event_Handler $controller) {
        $controller->register_hook('ACTION_ACT_PREPROCESS','BEFORE', $this, '_check404');
        $controller->register_hook('TPL_CONTENT_DISPLAY','BEFORE', $this, '_show404');
    }


    function _check404(&$event , $param) {
        if($event->data != 'show') return false;
        global $INFO;
        if($INFO['exists']) return false;

        $event->data = 'notfound';
        $event->stopPropagation();
        $event->preventDefault();
        return true;
    }

    function _show404(&$event, $param) {
        global $ACT;
        if($ACT != 'notfound') return false;
        $event->stopPropagation();
        $event->preventDefault();

        global $ID;
        $oldid = $ID;
        $ID = $this->getConf('404page');
        echo p_wiki_xhtml($ID,'',false);
        $ID = $oldid;
        $ACT='show';

        return true;
    }
}
