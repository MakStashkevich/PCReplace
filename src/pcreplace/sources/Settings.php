<?php

namespace pcreplace\sources;

use pocketmine\utils\TextFormat;

interface Settings
{
	/** @var string */
	const PREFIX = TextFormat::YELLOW . 'Loader> ' . TextFormat::GREEN;
	/** @var array */
	const LIST = ConvertIds::PC_TO_PE_11; // 1.1.*
	/** @var int */
	const REPLACE_ITEM = 352;
	/** @var int */
	const REPLACE_RADIUS = 15;
}