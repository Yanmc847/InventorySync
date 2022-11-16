<?php
    
namespace Yanmc847\InventorySync;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;

class resetinventory extends Command
{
    public function __construct()
    {
       parent::__construct("resetinventory", "Reset all inventory", "/resetinventory", ["resetinventory"]); 
    }
    	public function execute(CommandSender $sender, string $commandLabel, array $args) {
            if($sender->hasPermission("resetinventory.command")) {
            if ($sender instanceof Player) {
                $db = new \MySQLi(Main::getInstance()->getConfig()->get("mysql-host"), Main::getInstance()->getConfig()->get("mysql-user"), Main::getInstance()->getConfig()->get("mysql-password"), Main::getInstance()->getConfig()->get("mysql-database"));
                $db->query("DROP TABLE inventories");
		        $db->close();
                $sender->sendMessage("Success, delete player-data and restart server");
            }
            } else {
         $sender->sendMessage("You don't have permission to use this command");
        }
       }
    
}