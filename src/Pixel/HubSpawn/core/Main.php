<?php

declare(strict_types=1);

namespace Pixel\AntiVoid\Core;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\world\Position;

class Main extends PluginBase
{
    public $config;

    public function onLoad(): void
    {
        $this->getServer()->getLogger()->info(TextFormat::RED . "I NEED SLEEEEP!!!!!!!!!!!!!");
    }

    public function onEnable(): void
    {
        $this->getServer()->getLogger()->info(TextFormat::RED . "I NEED SLEEEEP!!!!!!!!!!!!!");

        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->saveDefaultConfig();
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        $nocmd = TextFormat::RED . "You do not have permission to use this command";
        $nowrld = TextFormat::RED . "You can not use this command in this world";

        $def_wrlds = ["kitpvp", "sumo"];

        if ($sender instanceof Player)
        {
            $player = $this->getServer()->getPlayerByPrefix($sender->getName());
            
            if ($cmd->getName() == "setspawn")
            {
                if (!($player->hasPermission("setspawn.create")))
                {
                    $player->sendMessage($nocmd);
                    return true;
                }

                $world = $player->getWorld();
                $pos = new Position($player->getPosition()->x, $player->getPosition()->y, $player->getPosition()->z, $world);

                $this->config->setNested("spawn.world", $world);
                $this->config->setNested("spawn.posX", $pos->x);
                $this->config->setNested("spawn.posY", $pos->y);
                $this->config->setNested("spawn.posZ", $pos->z);
                $this->config->save();

                $player->sendMessage(TextFormat::GREEN . "Congrats you have successfuly set spawn position.");
            }

            if ($cmd->getName() == "sethub")
            {
                if (!($player->hasPermission("sethub.create")))
                {
                    $player->sendMessage($nocmd);
                    return true;
                }

                $world = $player->getWorld();
                $pos = new Position($player->getPosition()->x, $player->getPosition()->y, $player->getPosition()->z, $world);

                $this->config->setNested("hub.world", $world);
                $this->config->setNested("hub.posX", $pos->x);
                $this->config->setNested("hub.posY", $pos->y);
                $this->config->setNested("hub.posZ", $pos->z);
                $this->config->save();

                $player->sendMessage(TextFormat::GREEN . "Congrats you have successfuly set hub position.");
            }


            if ($cmd->getName() == "spawn")
            {
                if ($this->config->exists("allowed_worlds"))
                {
                    foreach($this->config->get("allowed_worlds") as $allowed)
                    {
                        if ($player->getWorld() == $allowed)
                        {
                            $player->sendMessage($nowrld);
                            return true;
                        }
                        else
                        {
                            $pos_spawn = new Position(
                                $this->config->getNested("spawn")["posX"],
                                $this->config->getNested("spawn")["posX"],
                                $this->config->getNested("spawn")["posX"],
                                $this->config->getNested("spawn")["world"]
                            );

                            $player->sendMessage(TextFormat::GREEN . "Teleporting to spawn...");
                            $player->teleport($pos_spawn);
                        }
                    }
                }
                else
                {
                    $this->config->set("allowed_worlds", $def_wrlds);
                }
            }

            if ($cmd->getName() == "hub")
            {
                if ($this->config->exists("allowed_worlds"))
                {
                    foreach($this->config->get("allowed_worlds") as $allowed)
                    {
                        if ($player->getWorld() == $allowed)
                        {
                            $player->sendMessage($nowrld);
                            return true;
                        }
                        else
                        {
                            $pos_hub = new Position(
                                $this->config->getNested("hub")["posX"],
                                $this->config->getNested("hub")["posX"],
                                $this->config->getNested("hub")["posX"],
                                $this->config->getNested("hub")["world"]
                            );

                            $player->sendMessage(TextFormat::GREEN . "Teleporting to hub...");
                            $player->teleport($pos_hub);
                        }
                    }
                }
                else
                {
                    $this->config->set("allowed_worlds", $def_wrlds);
                }
            }
        }
        else
        {
            $sender->sendMessage(TextFormat::RED . "You have to use this command in game!");
        }

        return true;
    }
}
