<?php

namespace ResourceOptimizer;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\world\World;
use pocketmine\entity\Entity;
use pocketmine\utils\Config;
use pocketmine\Server;

class ResourceOptimizer extends PluginBase implements Listener {

    private $config;

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $this->getScheduler()->scheduleRepeatingTask(new ResourceCleanupTask($this), $this->config->get("resource_cleanup_interval") * 20);
    }

    public function onDataPacketReceive(DataPacketReceiveEvent $event): void {
    }

    public function checkEntityCount(): void {
        foreach ($this->getServer()->getWorldManager()->getWorlds() as $level) {
            $entityCount = count($level->getEntities());
            if ($entityCount > $this->config->get("max_entity_count")) {
                $this->getLogger()->warning("Entity count exceeded! Current count: $entityCount");
                $this->removeExcessEntities($level);
            }
        }
    }

    public function removeExcessEntities(World $level): void {
        $entities = $level->getEntities();
        $excessEntities = array_slice($entities, $this->config->get("max_entity_count"));
        foreach ($excessEntities as $entity) {
            $entity->kill();
            $this->getLogger()->info("Excess entity removed: " . get_class($entity));
        }
    }

    public function monitorWorldMemoryUsage(): void {
        $memoryUsage = memory_get_usage(true) / 1024 / 1024;
        if ($memoryUsage > $this->config->get("max_world_memory_usage")) {
            $this->getLogger()->warning("World memory usage exceeded: $memoryUsage MB");
            if ($this->config->get("enable_auto_cleanup")) {
                $this->cleanupWorld();
            }
        }
    }

    public function cleanupWorld(): void {
        foreach ($this->getServer()->getWorldManager()->getWorlds() as $level) {
            if ($this->config->get("cleanup_inactive_entities")) {
                $this->removeInactiveEntities($level);
            }
        }
    }

    public function removeInactiveEntities(World $level): void {
        $entities = $level->getEntities();
        foreach ($entities as $entity) {
            if ($entity instanceof Entity) {
                if (!$entity->isAlive()) {
                    $entity->kill();
                    $this->getLogger()->info("Inactive entity removed: " . get_class($entity));
                }
            }
        }
    }
}

class ResourceCleanupTask extends \pocketmine\scheduler\Task {

    private ResourceOptimizer $plugin;

    public function __construct(ResourceOptimizer $plugin) {
        $this->plugin = $plugin;
    }

    abstract public function onRun(int $currentTick): void;
        $this->plugin->checkEntityCount();
        $this->plugin->monitorWorldMemoryUsage();
        $this->plugin->cleanupWorld();
    }
}
