<?php

namespace woccck\ItemRename;

use pocketmine\plugin\PluginBase;
use woccck\ItemRename\Events\ItemListener;
use woccck\ItemRename\Commands\GiveItemCommand;

class ItemRenameTag extends PluginBase {

    /** @var ItemRenameTag */
    private static ItemRenameTag $instance;

    public function onLoad(): void
    {
        self::$instance = $this;
    }
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->registerEvents();
        $this->registerCommands();
    }

    public function registerCommands() {
        $this->getServer()->getCommandMap()->registerAll("itemrenametag", [
            new GiveItemCommand()
        ]);
    }

    public function registerEvents() {
        $this->getServer()->getPluginManager()->registerEvents(new ItemListener(), $this);
    }

    public static function getInstance(): ItemRenameTag
    {
        return self::$instance;
    }
}
