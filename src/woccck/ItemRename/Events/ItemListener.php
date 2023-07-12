<?php

namespace woccck\ItemRename\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\XpLevelUpSound;
use woccck\ItemRename\Item\Items;
use woccck\ItemRename\ItemRenameTag;
use woccck\ItemRename\Utils\Utils;

class ItemListener implements Listener
{

    /** @var array $itemRenamer */
    public array $itemRenamer = [];

    /** @var array $lorerenamer */
    public array $lorerenamer = [];

    /** @var array $nameTagMessage */
    public array $nameTagMessage = [];

    /**
     * @priority HIGHEST
     */
    public function onItemRename(PlayerChatEvent $event): void
    {
        $player = $event->getPlayer();
        if (!isset($this->itemRenamer[$player->getName()])) {
            return;
        }

        $message = $event->getMessage();
        $hand = $player->getInventory()->getItemInHand();
        $event->cancel();

        if ($hand->getTypeId() === VanillaItems::AIR()->getTypeId()) {
            $messageFormats = Utils::getConfig()->getNested("items.itemnametag.messages.air", [""]);

            foreach ($messageFormats as $messageFormat) {
                $player->sendMessage(TextFormat::colorize($messageFormat));
            }
            return;
        }

        if (count($hand->getEnchantments()) === 0) {
            $messageFormats = Utils::getConfig()->getNested("items.itemnametag.messages.renaming-non-enchanted-item", [""]);

            foreach ($messageFormats as $messageFormat) {
                $player->sendMessage(TextFormat::colorize($messageFormat));
            }
            return;
        }

        if ($message === "cancel") {
            $player->sendMessage("§r§c§l** §r§cYou have unqueued your Itemtag for this usage.");
            Utils::playSound($player, "mob.enderdragon.flap", 2);
            $player->getInventory()->addItem(Items::get(0));
            unset($this->itemRenamer[$player->getName()]);
            if (isset($this->nameTagMessage[$player->getName()])) unset($this->nameTagMessage[$player->getName()]);
        }
        if ($event->getMessage() === "confirm" && isset($this->nameTagMessage[$player->getName()])) {
            $messageFormats = Utils::getConfig()->getNested("items.itemnametag.messages.success", [""]);
            $customName = $this->nameTagMessage[$player->getName()];

            foreach ($messageFormats as $messageFormat) {
                $message = str_replace("{item_name}", $customName, $messageFormat);
                $player->sendMessage(TextFormat::colorize($message));
            }

            $player->getLocation()->getWorld()->addSound($player->getLocation(), new XpLevelUpSound(100));
            $hand->setCustomName($this->nameTagMessage[$player->getName()]);
            $player->getInventory()->setItemInHand($hand);
            unset($this->itemRenamer[$player->getName()]);
            unset($this->nameTagMessage[$player->getName()]);
        }
        if (strlen($event->getMessage()) > 26) {
            $player->sendMessage("§r§cYour custom name exceeds the 36 character limit.");
            return;
        }
        if (!isset($this->nameTagMessage[$player->getName()]) && $event->getMessage() !== "cancel" && $event->getMessage() !== "confirm") {
            $formatted = TextFormat::colorize($message);
            $player->sendMessage("§r§e§l(!) §r§eItem Name Preview: $formatted");
            $player->sendMessage("§r§7Type '§r§aconfirm§7' if this looks correct, otherwise type '§ccancel§7' to start over.");
            $this->nameTagMessage[$player->getName()] = $formatted;
        }
    }

    public function onPlayerUse(PlayerItemUseEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $hand = $player->getInventory()->getItemInHand();
        $tag = $item->getNamedTag();
        if ($tag->getString("rename", "") !== "") {
            if (isset($this->itemRenamer[$player->getName()])) {
                $player->sendMessage("§r§c§l(!) §r§cYou are already in queue for a item tag type cancel to remove it!");
                return;
            }
            if (isset($this->lorerenamer[$player->getName()])) {
                $player->sendMessage("§r§c§l(!) §r§cYou are already in queue for a lore rename tag type cancel to remove it!");
                return;
            }
            $this->itemRenamer[$player->getName()] = $player;
            $hand->setCount($hand->getCount() - 1);
            $player->getInventory()->setItemInHand($hand);
            $messageFormats = Utils::getConfig()->getNested("items.itemnametag.messages.activate", [""]);

            foreach ($messageFormats as $messageFormat) {
                $player->sendMessage(TextFormat::colorize($messageFormat));
            }
            Utils::playSound($player, "mob.enderdragon.flap", 2);
        }
    }
}
