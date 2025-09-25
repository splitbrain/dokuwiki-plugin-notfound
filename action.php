<?php

use dokuwiki\Extension\ActionPlugin;
use dokuwiki\Extension\EventHandler;
use dokuwiki\Extension\Event;


class action_plugin_notfound extends ActionPlugin
{
    /** @inheritdoc */
    public function register(EventHandler $controller)
    {
        $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'check404');
        $controller->register_hook('TPL_CONTENT_DISPLAY', 'BEFORE', $this, 'show404');
    }

    /**
     * Event handler for ACTION_ACT_PREPROCESS
     *
     * Check if the requested page exists, if not change the action to 'notfound'
     *
     * @see https://www.dokuwiki.org/devel:events:ACTION_ACT_PREPROCESS
     * @param Event $event Event object
     * @param mixed $param optional parameter passed when event was registered
     * @return bool true if the event was handled, false to let other handlers process it
     */
    public function check404(Event $event, $param)
    {
        if ($event->data != 'show') return false;
        global $INFO;
        if ($INFO['exists']) return false;

        $event->data = 'notfound';
        $event->stopPropagation();
        $event->preventDefault();
        return true;
    }

    /**
     * Event handler for TPL_CONTENT_DISPLAY
     *
     * If the action is 'notfound', display the configured 404 page instead
     *
     * @see https://www.dokuwiki.org/devel:events:TPL_CONTENT_DISPLAY
     * @param Event $event Event object
     * @param mixed $param optional parameter passed when event was registered
     * @return bool true if the event was handled, false to let other handlers process it
     */
    public function show404(Event $event, $param)
    {
        global $ACT;
        if ($ACT != 'notfound') return false;
        $event->stopPropagation();
        $event->preventDefault();

        global $ID;
        $oldid = $ID;
        $ID = $this->getConf('404page');
        echo p_wiki_xhtml($ID, '', false);
        $ID = $oldid;
        $ACT = 'show';

        return true;
    }
}
