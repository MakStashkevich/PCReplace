<?php

namespace pcreplace;

use pcreplace\commands\ReplaceMapCommand;
use pcreplace\commands\ReplaceMapOnFolderCommand;
use pcreplace\sources\Settings;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Loader extends PluginBase
{
	/**
	 * Starting plugin...
	 */
	function onEnable()
	{
		$server = $this->getServer();

		// start listener
		$server->getPluginManager()->registerEvents(new Listener(), $this);

		// register commands
		$commands = [
			new ReplaceMapCommand($this, 'replacemap', ['rpm', 'rmap']),
			new ReplaceMapOnFolderCommand($this, 'replacemapfolder', ['rmf', 'rpmf', 'rmapf', 'repmapf'])
		];
		$server->getCommandMap()->registerAll('MSCommand', $commands);

		// log to console
		$this->log(TextFormat::GREEN . 'Load!');
		$this->log(TextFormat::AQUA . 'Check updates on ' . TextFormat::LIGHT_PURPLE . 'github.com/MakStashkevich');
		$this->log(TextFormat::AQUA . 'Tell me on ' . TextFormat::LIGHT_PURPLE . 't.me/MakStashkevich');
	}

	/**
	 * @param string $message
	 */
	function log(string $message)
	{
		$this->getServer()->getLogger()->info(Settings::PREFIX . $message);
	}
}
