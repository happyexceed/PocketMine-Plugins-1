<?php

namespace rapidcmd;

use pocketmine\plugin\PluginBase;
use rapidcmd\command\RCMDStorage;
use rapidcmd\command\RapidCMDCommand;
use rapidcmd\event\RapidCMDListener;

class RapidCMD extends PluginBase{
    public function onEnable(){
        $this->saveFiles();
	$this->registerAll();
    }
    private function saveFiles(){
        if(!is_dir($this->getDataFolder())) mkdir($this->getDataFolder());
        if(file_exists($this->getDataFolder()."config.yml")){
            if($this->getConfig()->get("version") !== $this->getDescription()->getVersion() or !$this->getConfig()->exists("version")){
		$this->getServer()->getLogger()->warning("An invalid configuration file for ".$this->getDescription()->getName()." was detected.");
		if($this->getConfig()->getNested("plugin.autoUpdate") === true){
		    $this->saveResource("config.yml", true);
                    $this->getServer()->getLogger()->warning("Successfully updated the configuration file for ".$this->getDescription()->getName()." to v".$this->getDescription()->getVersion().".");
		}
	    }	
        }
        else{
            $this->saveDefaultConfig();
        }
    }
    private function registerAll(){
        $this->getServer()->getCommandMap()->register("rapidcmd", new RapidCMDCommand($this));
    	$this->getServer()->getPluginManager()->registerEvents(new RapidCMDListener($this), $this);
        $this->getCommandStorage()->registerDefaults();
    }
    /**
     * @return RCMDStorage
     */
    public function getCommandStorage(){
        return new RCMDStorage($this);
    }
}