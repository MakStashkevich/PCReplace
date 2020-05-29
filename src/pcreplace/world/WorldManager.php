<?php

namespace pcreplace\world;

use pcreplace\world\regions\RegionWorld;
use pcreplace\sources\Settings;
use pocketmine\block\Block;
use pocketmine\entity\Item;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\Compound;
use pocketmine\Player;
use pocketmine\Server;

class WorldManager
{
	/**
	 * @param Player $player
	 */
	static function startReplace(Player $player)
	{
		self::replaceBlocks($player->asPosition());
		self::clearLevelDrops($player->getLevel());
	}

	/**
	 * Replace blocks with all world
	 *
	 * @param Position $pos
	 */
	static function replaceBlocks(Position $pos)
	{
		$level = $pos->getLevel();
		$y = Level::Y_MAX / 2;
		foreach ($level->getChunks() as $chunk) {
			$x = $chunk->getX();
			$z = $chunk->getZ();
			self::replaceBlocksRadius(new Position($x, $y, $z, $level), 16);
		}
	}

	/**
	 * Replace PC blocks to PE blocks
	 *
	 * @param Position $pos
	 * @param int|integer $radius
	 */
	static function replaceBlocksRadius(Position $pos, int $radius = 10)
	{
		$level = $pos->getLevel();
		for ($x = $pos->x - $radius; $x < $pos->x + $radius; $x++) {
			for ($y = $pos->y - $radius; $y < $pos->y + $radius; $y++) {
				for ($z = $pos->z - $radius; $z < $pos->z + $radius; $z++) {
					$vector = new Vector3($x, $y, $z);
					$block = $level->getBlock($vector);
					$blockId = $block->getId();
					$blockMeta = $block->getDamage();

					// replace pc to pe blocks
					if (isset(Settings::LIST[$blockId . ':' . $blockMeta])) {
						$data = Settings::LIST[$blockId . ':' . $blockMeta];
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
	static function clearLevelDrops(Level $level)
	{
		foreach ($level->getEntities() as $drops) {
			if ($drops instanceof Item) {
				$drops->close();
			}
		}
		$level->clearCache();
	}

	/**
	 * @param string $folder
	 *
	 * @return bool
	 */
	static function replaceMapOnFolder(string $folder)
	{
		$server = Server::getInstance();
		if (!$server instanceof Server) {
			return false;
		}

		$patch = $server->getFilePath() . 'worlds';
		if (!is_dir($patch)) {
			return false;
		}

		$patch .= DIRECTORY_SEPARATOR . $folder;
		if (!is_dir($patch)) {
			return false;
		}

		$patchNbt = $patch . DIRECTORY_SEPARATOR . 'level.dat';
		$patchRegionsFolder = $patch . DIRECTORY_SEPARATOR . 'region';
		if (!file_exists($patchNbt) || !is_dir($patchRegionsFolder)) {
			return false;
		}

		$nbt = new NBT(NBT::BIG_ENDIAN);
		$nbt->readCompressed(file_get_contents($patchNbt));
		$level = $nbt->getData();
		if (!isset($level['Data']) || !$level['Data'] instanceof Compound) {
			return false;
		}

		// get level data
		$data = $level['Data'] ?? [];
		$isChanged = isset($data['PCReplace']);
		if ($isChanged) {
			return false;
		}

		// get regions level
		$regions = RegionWorld::loadAll($patchRegionsFolder);
		foreach ($regions as $region) {
			$regionX = $region->getX();
			$regionZ = $region->getZ();
			echo 'Change blocks on region X:' . $regionX . ', Z:' . $regionZ . PHP_EOL;

			// get chunks
			for ($chunkX = 0; $chunkX < RegionWorld::SECTION_COUNT; $chunkX++) {
				for ($chunkZ = 0; $chunkZ < RegionWorld::SECTION_COUNT; $chunkZ++) {
					$chunk = $region->readChunk($chunkX - $regionX * RegionWorld::SECTION_COUNT, $chunkZ - $regionZ * RegionWorld::SECTION_COUNT);
					if ($chunk !== null) {
						$chunk->setX($chunkX);
						$chunk->setZ($chunkZ);

						// replace blocks
						for ($x = 0; $x < RegionWorld::SECTION_COUNT_CHUNK; $x++) {
							for ($z = 0; $z < RegionWorld::SECTION_COUNT_CHUNK; $z++) {
								for ($y = 0; $y < RegionWorld::SECTION_COUNT_CHUNK; $y++) {
									$blockId = $chunk->getBlockId($x, $y, $z);
									$blockMeta = $chunk->getBlockData($x, $y, $z);

									// replace pc to pe blocks
									if (isset(Settings::LIST[$blockId . ':' . $blockMeta])) {
										$data = Settings::LIST[$blockId . ':' . $blockMeta];
										$id = $data[0] ?? 0;
										$meta = $data[1] ?? 0;
										$chunk->setBlockId($x, $y, $z, $id);
										$chunk->setBlockData($x, $y, $z, $meta);
										echo 'Change block ' . $blockId . ':' . $blockMeta . ' to ' . $id . ':' . $meta . PHP_EOL;
 									}
								}
							}
						}

						// rewrite chunk blocks
						$region->writeChunk($chunk);
					}
				}
			}

			// close & save region
			$region->close();
		}

		// save time edit world
		$data['PCReplace'] = time();
		$nbt = new NBT(NBT::BIG_ENDIAN);
		$nbt->setData(new Compound('', [
			'Data' => $data
		]));
		$buffer = $nbt->writeCompressed();
		file_put_contents($patchNbt, $buffer);
		return true;
	}
}