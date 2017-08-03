<?php

namespace ms;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\utils\TextFormat;

class PCReplaceCommand extends Command {

    private $plugin;

    /**
     * Register command
     */
	public function __construct($plugin)
    {
        $this->plugin = $plugin;
    	parent::__construct("PCReplace", "Replace PC blocks to PE blocks", "/pcreplace", ["pcr"]);
    }

    /**
     * Execute command
     * @param  CommandSender $sender
     * @param  string        $label
     * @param  array         $args
     */
    public function execute(CommandSender $sender, $label, array $args)
    {
        if($sender Instanceof Player){
            $sender->sendMessage("Now your server may hang for a while.");
            $sender->sendMessage("The time it takes for the card depends on its size.");
            $this->plugin->replaceAllBlocks(new Position($sender->x, $sender->y, $sender->z, $sender->getLevel()));
            $this->plugin->updateLevel($sender->getLevel());
            $sender->sendMessage("All blocks in level replaced!");
        } else {
            $sender->sendMessage(TextFormat::RED.'Command only for players!');
        }
    }

}