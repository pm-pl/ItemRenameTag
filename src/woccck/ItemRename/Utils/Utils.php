<?php

namespace woccck\ItemRename\Utils;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\SpawnParticleEffectPacket;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use woccck\ItemRename\ItemRenameTag;

class Utils {

    public static function getConfig(): Config {
        return new Config(ItemRenameTag::getInstance()->getDataFolder() . "config.yml", Config::YAML);
    }

    /**
     * @param Entity $player
     * @param string $sound
     * @param int $volume
     * @param int $pitch
     * @param int $radius
     */
    public static function playSound(Entity $player, string $sound, $volume = 1, $pitch = 1, int $radius = 5): void
    {
        foreach ($player->getWorld()->getNearbyEntities($player->getBoundingBox()->expandedCopy($radius, $radius, $radius)) as $p) {
            if ($p instanceof Player) {
                if ($p->isOnline()) {
                    $spk = new PlaySoundPacket();
                    $spk->soundName = $sound;
                    $spk->x = $p->getLocation()->getX();
                    $spk->y = $p->getLocation()->getY();
                    $spk->z = $p->getLocation()->getZ();
                    $spk->volume = $volume;
                    $spk->pitch = $pitch;
                    $p->getNetworkSession()->sendDataPacket($spk);
                }
            }
        }
    }

    public static function spawnParticleV2(Entity $entity, string $particleName): void
    {
        $particleCount = 10;
        $radius = 0.5;

        $position = $entity->getEyePos();

        for ($i = 0; $i < $particleCount; $i++) {
            $offsetX = mt_rand(-$radius * 100, $radius * 100) / 100;
            $offsetY = mt_rand(-$radius * 100, $radius * 100) / 100;
            $offsetZ = mt_rand(-$radius * 100, $radius * 100) / 100;

            $particleX = $position->getX() + $offsetX;
            $particleY = $position->getY() + $offsetY;
            $particleZ = $position->getZ() + $offsetZ;

            $particlePosition = new Vector3($particleX, $particleY, $particleZ);
            self::spawnParticleNear($entity, $particleName, $particlePosition);
        }
    }

    public static function spawnParticleNear(Entity $entity, string $particleName, Vector3 $position, int $radius = 5): void
    {
        $packet = new SpawnParticleEffectPacket();
        $packet->particleName = $particleName;
        $packet->position = $position;

        foreach ($entity->getWorld()->getNearbyEntities($entity->getBoundingBox()->expandedCopy($radius, $radius, $radius)) as $player) {
            if ($player instanceof Player && $player->isOnline()) {
                $player->getNetworkSession()->sendDataPacket($packet);
            }
        }
    }
}
