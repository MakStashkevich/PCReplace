<?php

namespace pcreplace\commands;

use pcreplace\sources\Settings;
use pcreplace\world\WorldManager;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;

class ReplaceMapOnFolderCommand extends ReplaceCommand
{
	/** @var string */
	protected $description = 'Replace pc anvil map on folder to pmanvil folder';

	/**
	 * @param CommandSender $sender
	 * @param string $label
	 * @param array $args
	 *
	 * @return mixed|void
	 */
	function execute(CommandSender $sender, $label, array $args)
	{
		if (!$sender Instanceof ConsoleCommandSender) {
			$sender->sendMessage(Settings::PREFIX . TextFormat::RED . 'Command only for console!');
			return false;
		}

		if (!isset($args[0])) {
			$sender->sendMessage(Settings::PREFIX . TextFormat::RED . 'Select name folder world');
			return false;
		}
		$folder = trim($args[0]);

		if (!WorldManager::replaceMapOnFolder($folder)) {
			$sender->sendMessage(Settings::PREFIX . TextFormat::RED . 'Map ' . $folder . ' replaced failed!');
			return false;
		}

		$sender->sendMessage(Settings::PREFIX . 'Map ' . $folder . ' replaced success!');
	}
}