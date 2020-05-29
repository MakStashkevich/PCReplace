<?php

namespace pcreplace\commands;

use pcreplace\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ReplaceCommand extends Command
{
	/** @var Loader */
	private $loader;

	/**
	 * ReplaceCommand constructor.
	 *
	 * @param Loader $loader
	 * @param string $name
	 * @param array $aliases
	 * @param string $description
	 */
	function __construct(Loader $loader, string $name, array $aliases = [], string $description = null)
	{
		$this->loader = $loader;
		parent::__construct($name, $description ?? $this->description, '/' . $name, $aliases);
	}

	/**
	 * @return Loader
	 */
	function getLoader(): Loader
	{
		return $this->loader;
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 *
	 * @return mixed|void
	 */
	function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		return false;
	}
}