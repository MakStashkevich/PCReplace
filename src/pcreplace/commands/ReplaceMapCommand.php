<?php

namespace pcreplace\commands;

use pcreplace\MapManager;
use pcreplace\sources\Settings;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ReplaceMapCommand extends Command
{
	/** @var string */
	protected $description = 'Replace pc anvil map to pmanvil';

	/**
	 * @param CommandSender $sender
	 * @param string $label
	 * @param array $args
	 *
	 * @return mixed|void
	 */
	function execute(CommandSender $sender, $label, array $args)
	{
		if (!$sender Instanceof Player) {
			$sender->sendMessage(Settings::PREFIX . TextFormat::RED . 'Command only for players!');
			return;
		}

		$sender->sendMessage(Settings::PREFIX . 'Now your server may hang for a while.');
		$sender->sendMessage(Settings::PREFIX . 'The time it takes for the card depends on its size.');

		MapManager::startReplace($sender);

		$sender->sendMessage(Settings::PREFIX . 'All blocks in level replaced!');
	}
}