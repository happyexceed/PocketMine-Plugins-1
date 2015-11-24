<?php

namespace phputils\task;

use phputils\PHPUtils;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Player;
use pocketmine\Server;

class QueryPocketMineTask extends AsyncTask{
    const SERVER_OFFLINE = 0;
    const PLUGIN_NOT_FOUND = 1;
    /** @var string */
    private $plugin;
    /** @var string */
    private $sender;
    /** @var mixed */
    private $data = null;
    /** @var mixed */
    private $result = null;
    /**
     * @param string $plugin
     */
    public function __construct($plugin, $sender){
        $this->plugin = strtolower($plugin);
        $this->sender = strtolower($sender);
    }
    public function onRun(){
        try{
            //$attempts = 0;
            $this->data = json_decode(file_get_contents("http://forums.pocketmine.net/api.php"), true);
            foreach($this->data["resources"] as $info){
                if(strtolower($info["title"]) === $this->plugin){
                    $this->result = $info;
                    //echo $info["title"]." was found after ".$attempts." failed attempt(s)!\n";
                    break;
                }
                else{
                    $this->result = self::PLUGIN_NOT_FOUND;
                    //$attempts++;
                    //echo $attempts." attempt(s) have been made to find ".$this->plugin.".\n";
                }
            }
        }
        catch(\RuntimeException $exception){
            $this->result = self::SERVER_OFFLINE;
        }
    }
    /**
     * @param Server $server
     */
    public function onCompletion(Server $server){
        if(($plugin = $server->getPluginManager()->getPlugin("PHPUtils")) instanceof PHPUtils){
            if(($player = $server->getPlayer($this->sender)) instanceof Player){
                $plugin->sendPluginInfo($player, $this->result);
            }
            else{
                $plugin->sendPluginInfo(new ConsoleCommandSender(), $this->result);
            }
        }
    }
}