<?php

namespace pcreplace;

use pcreplace\commands\ReplaceMapCommand;
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
		$server->getPluginManager()->registerEvents(new Listener(), $this);
		$server->getCommandMap()->register('MSCommand', new ReplaceMapCommand($this));

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
