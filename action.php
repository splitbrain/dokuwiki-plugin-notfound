<?php
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');


class action_plugin_notfound extends DokuWiki_Action_Plugin {

    function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_CONTENT_DISPLAY','BEFORE', $this, '_show404');
    }

    function _show404(&$event, $param) {
        global $ACT, $INFO;
        if($ACT != 'show' || $INFO['exists']) return false;
        $event->stopPropagation();
        $event->preventDefault();

        global $ID;
        $oldid = $ID;
        $ID = $this->getConf('404page');
        echo p_wiki_xhtml($ID,'',false);
        $ID = $oldid;

        return true;
    }
}
