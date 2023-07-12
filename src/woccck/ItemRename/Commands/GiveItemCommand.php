<?php

declare(strict_types=1);

namespace woccck\ItemRename\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use woccck\ItemRename\Item\Items;
use woccck\ItemRename\ItemRenameTag;
use woccck\ItemRename\Utils\Utils;

class GiveItemCommand extends Command implements PluginOwned
{
    /** @var ItemRenameTag */
    public ItemRenameTag $plugin;

    public function __construct()
    {
        parent::__construct("giveitem", "Give custom item", "/giveitem <item> <player>", ["gi"]);
        $this->setPermission("itemrenametag.giveitem");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be run by a player.");
            return false;
        }

        $config = Utils::getConfig();

        if (empty($args)) {
            $sender->sendMessage(TextFormat::RED . "Usage: /gi <identifier> [amount] [player]");
            return false;
        }

        $identifier = strtolower($args[0]);

        if ($identifier === "list") {
            $identifiers = $config->get("items", []);

            $sender->sendMessage(TextFormat::YELLOW . "Available item identifiers:");

            foreach ($identifiers as $id => $itemData) {
                $sender->sendMessage("- $id");
            }

            return true;
        }

        if ($identifier === "reload") {
            if ($sender->hasPermission("itemrenametag.reload")) {
                $config->reload();
                $sender->sendMessage(TextFormat::GREEN . TextFormat::BOLD . "(!) " . TextFormat::RESET . TextFormat::GREEN . "Successfully reloaded configuration!");
                return true;
            }
        }

        $amount = isset($args[1]) ? (int) $args[1] : 1;
        $playerName = isset($args[2]) ? $args[2] : $sender->getName();

        $itemData = $config->getNested("items.$identifier");

        if ($itemData === null) {
            $sender->sendMessage(TextFormat::RED . "Item identifier '$identifier' not found.");
            return false;
        }

        $item = Items::get(0, $amount);

        if ($item === null) {
            $sender->sendMessage(TextFormat::RED . "Failed to create item for identifier '$identifier'.");
            return false;
        }

        $player = Server::getInstance()->getPlayerByPrefix($playerName);

        if ($player !== null && $player->isOnline()) {
            $player->getInventory()->addItem($item);
            $sender->sendMessage(TextFormat::GREEN . "Given $amount x '$identifier' to $playerName.");
        } else {
            $sender->sendMessage(TextFormat::RED . "Player '$playerName' not found.");
        }

        return true;
    }


    public function getOwningPlugin(): ItemRenameTag
    {
        return $this->plugin;
    }
}
