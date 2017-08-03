<?php

namespace ms;

use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class PCReplace extends PluginBase implements Listener {

	const LANGUAGE = "RU"; // RU or EN

	const REPLACE_ITEM = 352;
	const REPLACE_RADIUS = 15;

	/**
	 * Starting plugin...
	 */
	public function onEnable()
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getCommandMap()->register('MSCommand', new PCReplaceCommand($this));
		$this->getServer()->getLogger()->info(TextFormat::YELLOW."PCReplace> ".TextFormat::GREEN."Load!");
		$this->getServer()->getLogger()->info(TextFormat::YELLOW."PCReplace> ".TextFormat::AQUA."Check updates on ".TextFormat::LIGHT_PURPLE."github.com/MakStashkevich");
		$this->getServer()->getLogger()->info(TextFormat::YELLOW."PCReplace> ".TextFormat::AQUA."Tell me on ".TextFormat::LIGHT_PURPLE."t.me/MakStashkevich");
	}

	/**
     * Player Interact
     * @param  PlayerInteractEvent $event
     */
    public function onUse(PlayerInteractEvent $event)
	{
    	$player = $event->getPlayer();
    	$item = $event->getItem();
    	$block = $event->getBlock();
    	if ($item->getId() == self::REPLACE_ITEM) {
            $player->sendMessage("Starting replace blocks...");
    		$this->replaceBlocks(new Position($player->x, $player->y, $player->z, $player->getLevel()), self::REPLACE_RADIUS);
    		$this->updateLevel($player->getLevel());
    		$player->sendMessage("All blocks replaced!");
    	}
    }

    /**
     * Replace blocks with all world
     * @param  Position $pos
     */
    public function replaceAllBlocks(Position $pos)
    {
    	$level = $pos->getLevel();
    	$y = Level::Y_MAX / 2;
    	foreach ($level->getChunks() as $chunk) {
    		$x = $chunk->getX();
    		$z = $chunk->getZ();
    		$this->replaceBlocks(new Position($x, $y, $z, $level), 16);
    	}
    }

    /**
     * Replace PC blocks to PE blocks
     * @param  Position    $player
     * @param  int|integer $radius
     */
    public function replaceBlocks(Position $pos, int $radius = 10)
    {
    	$level = $pos->getLevel();
    	for ($x = $pos->x - $radius; $x < $pos->x + $radius; $x++){
    		for ($y = $pos->y - $radius; $y < $pos->y + $radius; $y++){
    			for ($z = $pos->z - $radius; $z < $pos->z + $radius; $z++){
    				$vector = new Vector3($x, $y, $z);
    				$block = $level->getBlock($vector);
    				$id = $block->getId();
    				$meta = $block->getDamage();
    				switch ($id) {
    					case 126:
    					$level->setBlock($vector, Block::get(158, $meta));
    					break;

    					case 125:
    					if($meta == 5 || $meta == 1) $level->setBlock($vector, Block::get(5, 1));
    					break;

    					case 188:
    					if($meta == 0) $level->setBlock($vector, Block::get(85, 1));
    					break;

    					case 44:
    					if($meta == 6) $level->setBlock($vector, Block::get(44, 7));
    					break;

    					case 165:
    					$level->setBlock($vector, Block::get(35, $meta));
    					break;

    					case 77:
    					case 143:
    					$level->setBlock($vector, Block::get(0, 0));
    					break;

    					case 248:
    					case 249:
    					case 255:
    					$level->setBlock($vector, Block::get(170, 0)); // Just because :)
    					break;
    				}
    			}
    		}
    	}
    }

    /**
     * Remove drops & clear level cache
     * @param  Level  $level
     */
    public function updateLevel(Level $level)
    {
    	foreach ($level->getEntities() as $drops) {
            if ($drops instanceof \pocketmine\entity\Item) $drops->close();
        }
    	$level->clearCache(true);
    }
}