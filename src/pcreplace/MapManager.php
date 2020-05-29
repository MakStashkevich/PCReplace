<?php

namespace pcreplace;

use pocketmine\block\Block;
use pocketmine\entity\Item;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;

class MapManager
{
	/**
	 * @param Player $player
	 */
	static function startReplace(Player $player)
	{
		self::replaceAllBlocks($player->asPosition());
		self::updateLevel($player->getLevel());
	}

	/**
	 * Replace blocks with all world
	 *
	 * @param Position $pos
	 */
	static function replaceAllBlocks(Position $pos)
	{
		$level = $pos->getLevel();
		$y = Level::Y_MAX / 2;
		foreach ($level->getChunks() as $chunk) {
			$x = $chunk->getX();
			$z = $chunk->getZ();
			self::replaceBlocks(new Position($x, $y, $z, $level), 16);
		}
	}

	/**
	 * Replace PC blocks to PE blocks
	 *
	 * @param Position $pos
	 * @param int|integer $radius
	 */
	static function replaceBlocks(Position $pos, int $radius = 10)
	{
		$level = $pos->getLevel();
		for ($x = $pos->x - $radius; $x < $pos->x + $radius; $x++) {
			for ($y = $pos->y - $radius; $y < $pos->y + $radius; $y++) {
				for ($z = $pos->z - $radius; $z < $pos->z + $radius; $z++) {
					$vector = new Vector3($x, $y, $z);
					$block = $level->getBlock($vector);
					$id = $block->getId();
					$meta = $block->getDamage();

					//rotate buttons
					switch ($id) {
						case 77: //Stone Button
						case 143: //Wooden Button
							//                            $level->setBlock($vector, Block::get($id, $meta >> 4));
							continue;
					}

					//replace pc to pe blocks
					if (isset(self::LIST[$id . ':' . $meta])) {
						$data = self::LIST[$id . ':' . $meta];
						$level->setBlock($vector, Block::get($data[0], $data[1]));
					}
				}
			}
		}
	}

	/**
	 * Remove drops & clear level cache
	 *
	 * @param Level $level
	 */
	static function updateLevel(Level $level)
	{
		foreach ($level->getEntities() as $drops) {
			if ($drops instanceof Item) {
				$drops->close();
			}
		}
		$level->clearCache();
	}
}