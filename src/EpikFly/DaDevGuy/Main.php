<?php 
declare(strict_types=1);

namespace EpikFly\DaDevGuy;

use libs\Vecnavium\FormsUI\SimpleForm;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{
    public function onEnable(): void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->getResource("config.yml");
        if($this->getConfig()->get("config-ver") != 1){
            $this->getLogger()->info("§l§cWARNING: §r§cEpikFly's config is NOT up to date. Please delete the config.yml and restart the server or the plugin may not work properly.");
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if($command->getName() === "fly"){
            if($sender instanceof Player){
                $this->flyui($sender);
            } else {
                $sender->sendMessage("Please Use This Command In-Game!");
            }
            return true;
        }
    }

    public function flyui($player){
        $form = new SimpleForm(function(Player $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }

            switch($result){
                case 0:
                    if(!$player->getAllowFlight()){
                        $player->setFlying(true);
                        $player->setAllowFlight(true);
                        $player->sendMessage($this->getConfig()->get("epikfly.enable-message"));
                    }
                    else
                    {
                        $player->setFlying(false);
                        $player->setAllowFlight(false);
                        $player->sendMessage($this->getConfig()->get("epikfly.disable-message"));
                    }
            }
        });
        $form->setTitle($this->getConfig()->get("epikfly.title"));
        $form->addButton($this->getConfig()->get("epikfly.button.title"));
        $player->sendForm($form);
        return $form;
    }
}