<?php

namespace easymessages\task;

use easymessages\EasyMessages;
use pocketmine\scheduler\PluginTask;

class InfinitePopupTask extends PluginTask{
    /** @var EasyMessages */
    private $plugin;
    /**
     * @param EasyMessages $plugin
     */
    public function __construct(EasyMessages $plugin){
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }
    /**
     * @param int $currentTick
     */
    public function onRun($currentTick){
        $this->plugin->broadcastPopup($this->plugin->getConfig()->getNested("popup.infiniteMessage"));
    }
}
