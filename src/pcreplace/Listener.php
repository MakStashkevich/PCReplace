<?php

namespace pcreplace;

use pcreplace\sources\Settings;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;

class Listener implements \pocketmine\event\Listener
{
	/**
	 * Player Interact
	 *
	 * @param PlayerInteractEvent $event
	 */
	function onUse(PlayerInteractEvent $event)
	{
		$player = $event->getPlayer();
		$item = $event->getItem();
		$block = $event->getBlock();
		$face = $event->getFace();

		$player->sendMessage($block . ' & blockFace: ' . $face);

		if ($item->getId() === Settings::REPLACE_ITEM) {
			$player->sendMessage(Settings::PREFIX . 'Starting replace blocks...');

			MapManager::replaceBlocks($player->asPosition(), Settings::REPLACE_RADIUS);
			MapManager::updateLevel($player->getLevel());

			$player->sendMessage(Settings::PREFIX . 'All blocks replaced!');
		}
	}

	/**
	 * @param PlayerMoveEvent $event
	 */
	function onMove(PlayerMoveEvent $event)
	{
		$player = $event->getPlayer();
		$player->sendPopup($player->asPosition() . ' & direction: ' . $player->getDirection());
	}
}