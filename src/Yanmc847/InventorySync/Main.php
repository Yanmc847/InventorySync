<?php

namespace Yanmc847\InventorySync;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\{
	Listener,
	player\PlayerLoginEvent,
	player\PlayerQuitEvent
};

class Main extends PluginBase implements Listener {

	private $config;
	private static $instance;

	public function onLoad() : void
	{
		self::$instance = $this;
	}

	public function onEnable() : void
	{
		@mkdir($this->getDataFolder());
		if(!file_exists($this->getDataFolder()."config.yml")) $this->saveResource("config.yml");
		$this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		DatabaseInv::init();
        
        $this->getServer()->getCommandMap()->register("" ,new resetinventory());
	}

	public function onDisable(): void
    {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            if(DatabaseInv::isRegistered($player)){
                DatabaseInv::saveInventory($player);
            }
        }
    }

    public function onLogin(PlayerLoginEvent $ev)
	{
		$player = $ev->getPlayer();

		if(!DatabaseInv::isRegistered($player)){
			DatabaseInv::register($player);
		} else {
			DatabaseInv::restoreInventory($player);
		}
	}

	public function onQuit(PlayerQuitEvent $ev)
	{
		$player = $ev->getPlayer();

		if(DatabaseInv::isRegistered($player)){
			DatabaseInv::saveInventory($player);
		}
	}

	public function getConfig() : Config
	{
		return $this->config;
	}

	public static function getInstance()
	{
		return self::$instance;
	}
}