<?php

namespace pcreplace\world\regions;

use pcreplace\world\WorldUtils;
use pocketmine\level\format\FullChunk;
use pocketmine\level\format\mcregion\Chunk;
use pocketmine\math\Vector2;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteArray;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\IntArray;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\utils\Binary;
use pocketmine\utils\ChunkException;

class RegionWorld
{
	/** @var int */
	const VERSION = 0x01;
	/** @var int */
	const MAX_Y = 0x100;
	/** @var int */
	const MASK_Y = 0xff;
	/** @var int */
	const COMPRESSION_GZIP = 1;
	/** @var int */
	const COMPRESSION_ZLIB = 2;
	/**
	 * 256 sectors (1 MiB)
	 *
	 * @var int
	 */
	const MAX_SECTOR_LENGTH = 256 << 12;
	/**
	 * Section count is 32 chunks (512 blocks)
	 *
	 * @var int
	 */
	const SECTION_COUNT = 32;
	/** @var int */
	const SECTION_COUNT_CHUNK = 16;

	/** @var int */
	static $COMPRESSION_LEVEL = 7;

	/**
	 * @var string (r.X.Z.mcr)
	 */
	static $fileRegex = 'r\.(?<x>\d)\.(?<z>\d)\.mcr';

	/** @var int */
	protected $x = 0;

	/** @var int */
	protected $z = 0;

	/** @var string */
	protected $filePath = '';

	/** @var false|resource */
	protected $filePointer;

	/** @var int */
	protected $lastSector = 0;

	/** @var array */
	protected $locationTable = [];

	/**
	 * RegionWorld constructor.
	 *
	 * @param string $path
	 * @param $regionX
	 * @param $regionZ
	 */
	function __construct(string $path, int $regionX, int $regionZ)
	{
		$this->x = $regionX;
		$this->z = $regionZ;
		$this->filePath = $path;
		$exists = file_exists($this->filePath);
		touch($this->filePath);
		$this->filePointer = fopen($this->filePath, 'r+b');
		stream_set_read_buffer($this->filePointer, 1024 * 16); //16KB
		stream_set_write_buffer($this->filePointer, 1024 * 16); //16KB

		if (!$exists) {
			return;
		}

		$this->loadLocationTable();
	}

	protected function loadLocationTable()
	{
		fseek($this->filePointer, 0);
		$this->lastSector = 1;

		$data = unpack('N*', fread($this->filePointer, 4 * 1024 * 2)); //1024 records * 4 bytes * 2 times
		for ($i = 0; $i < 1024; ++$i) {
			$index = $data[$i + 1];
			$this->locationTable[$i] = [$index >> 8, $index & 0xff, $data[1024 + $i + 1]];
			if (($this->locationTable[$i][0] + $this->locationTable[$i][1] - 1) > $this->lastSector) {
				$this->lastSector = $this->locationTable[$i][0] + $this->locationTable[$i][1] - 1;
			}
		}
	}

	/**
	 * @param string $dir
	 *
	 * @return RegionWorld[]
	 */
	static function loadAll(string $dir): array
	{
		$files = WorldUtils::getDirContents($dir);
		$regions = [];
		foreach ($files as $file) {
			if (self::isCorrectFile($file)) {
				$pos = self::getZXFile($file);
				$x = $pos->getX();
				$z = $pos->getY();
				$regions[self::regionHash($x, $z)] = new RegionWorld($file, $x, $z);
			}
		}
		return $regions;
	}

	/**
	 * @param string $file
	 *
	 * @return bool
	 */
	static function isCorrectFile(string $file): bool
	{
		return preg_match(static::$fileRegex, $file) > 0;
	}

	/**
	 * @param string $file
	 *
	 * @return Vector2
	 */
	static function getZXFile(string $file)
	{
		$x = 0;
		$z = 0;

		preg_match(static::$fileRegex, $file, $data);
		if (isset($data['x'])) {
			$x = (int) $data['x'];
		}
		if (isset($data['z'])) {
			$z = (int) $data['z'];
		}

		return new Vector2($x, $z);
	}

	/**
	 * Returns the region unique hash/key
	 *
	 * @param int $x
	 * @param int $z
	 *
	 * @return string
	 */
	static function regionHash(int $x, int $z): string
	{
		return WorldUtils::getXZHash($x, $z);
	}

	function __destruct()
	{
		if (is_resource($this->filePointer)) {
			$this->writeLocationTable();
			fclose($this->filePointer);
		}
	}

	private function writeLocationTable()
	{
		$write = [];

		for ($i = 0; $i < 1024; ++$i) {
			$write[] = (($this->locationTable[$i][0] << 8) | $this->locationTable[$i][1]);
		}
		for ($i = 0; $i < 1024; ++$i) {
			$write[] = $this->locationTable[$i][2];
		}

		fseek($this->filePointer, 0);
		fwrite($this->filePointer, pack('N*', ...$write), 4096 * 2);
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return Chunk|null
	 */
	function readChunk(int $x, int $z)
	{
		$index = self::getChunkOffset($x, $z);
		if ($index < 0 or $index >= 4096) {
			return null;
		}

		if (!$this->isChunkGenerated($index)) {
			return null;
		}

		fseek($this->filePointer, $this->locationTable[$index][0] << 12);
		$length = Binary::readInt(fread($this->filePointer, 4));
		$compression = ord(fgetc($this->filePointer));

		if ($length <= 0 or $length > self::MAX_SECTOR_LENGTH) { //Not yet generated / corrupted
			if ($length >= self::MAX_SECTOR_LENGTH) {
				$this->locationTable[$index][0] = ++$this->lastSector;
				$this->locationTable[$index][1] = 1;
				self::debug('Corrupted chunk header detected');
			}
			$this->generateChunk($x, $z);
			fseek($this->filePointer, $this->locationTable[$index][0] << 12);
			$length = Binary::readInt(fread($this->filePointer, 4));
			$compression = ord(fgetc($this->filePointer));
		}

		if ($length > ($this->locationTable[$index][1] << 12)) { //Invalid chunk, bigger than defined number of sectors
			self::debug('Corrupted bigger chunk detected');
			$this->locationTable[$index][1] = $length >> 12;
			$this->writeLocationIndex($index);
		} else if ($compression !== self::COMPRESSION_ZLIB and $compression !== self::COMPRESSION_GZIP) {
			self::debug('Invalid compression type');
			return null;
		}

		$chunk = Chunk::fromBinary(fread($this->filePointer, $length - 1));
		if (!$chunk instanceof Chunk) {
			return null;
		}

		return $chunk;
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return int
	 */
	protected static function getChunkOffset(int $x, int $z)
	{
		return $x + ($z << 5);
	}

	/**
	 * @param int $index
	 *
	 * @return bool
	 */
	protected function isChunkGenerated(int $index): bool
	{
		return !($this->locationTable[$index][0] === 0 or $this->locationTable[$index][1] === 0);
	}

	/**
	 * @param string $message
	 */
	static function debug(string $message)
	{
		echo '[RegionWorld] ' . $message . PHP_EOL;
	}

	/**
	 * @param int $x
	 * @param int $z
	 */
	function generateChunk(int $x, int $z)
	{
		$nbt = new Compound('Level', []);
		$nbt->xPos = new IntTag('xPos', ($this->getX() * 32) + $x);
		$nbt->zPos = new IntTag('zPos', ($this->getZ() * 32) + $z);
		$nbt->LastUpdate = new LongTag('LastUpdate', 0);
		$nbt->TerrainPopulated = new ByteTag('TerrainPopulated', 0);
		$nbt->V = new ByteTag('V', self::VERSION);
		$nbt->InhabitedTime = new LongTag('InhabitedTime', 0);
		$biomes = str_repeat(Binary::writeByte(-1), 256);
		$nbt->Biomes = new ByteArray('Biomes', $biomes);
		$nbt->HeightMap = new IntArray('HeightMap', array_fill(0, 256, self::MAX_Y - 1));
		$nbt->BiomeColors = new IntArray('BiomeColors', array_fill(0, 256, Binary::readInt("\x00\x85\xb2\x4a")));

		$half = str_repeat("\x00", 16384);
		$full = $half . $half;
		$nbt->Blocks = new ByteArray('Blocks', $full);
		$nbt->Data = new ByteArray('Data', $half);
		$nbt->SkyLight = new ByteArray('SkyLight', str_repeat("\xff", 16384));
		$nbt->BlockLight = new ByteArray('BlockLight', $half);

		$nbt->Entities = new Enum('Entities', []);
		$nbt->Entities->setTagType(NBT::TAG_Compound);
		$nbt->TileEntities = new Enum('TileEntities', []);
		$nbt->TileEntities->setTagType(NBT::TAG_Compound);
		$nbt->TileTicks = new Enum('TileTicks', []);
		$nbt->TileTicks->setTagType(NBT::TAG_Compound);
		$writer = new NBT(NBT::BIG_ENDIAN);
		$nbt->setName('Level');
		$writer->setData(new Compound('', ['Level' => $nbt]));
		$chunkData = $writer->writeCompressed(ZLIB_ENCODING_DEFLATE, self::$COMPRESSION_LEVEL);

		if ($chunkData !== false) {
			$this->saveChunk($x, $z, $chunkData);
		}
	}

	/**
	 * @return int
	 */
	function getX(): int
	{
		return $this->x;
	}

	/**
	 * @return int
	 */
	function getZ(): int
	{
		return $this->z;
	}

	/**
	 * @param int $x
	 * @param int $z
	 * @param string $chunkData
	 */
	protected function saveChunk(int $x, int $z, string $chunkData)
	{
		$length = strlen($chunkData) + 1;
		if ($length + 4 > self::MAX_SECTOR_LENGTH) {
			throw new ChunkException('Chunk is too big! ' . ($length + 4) . ' > ' . self::MAX_SECTOR_LENGTH);
		}
		$sectors = (int) ceil(($length + 4) / 4096);
		$index = self::getChunkOffset($x, $z);
		$indexChanged = false;
		if ($this->locationTable[$index][1] < $sectors) {
			$this->locationTable[$index][0] = $this->lastSector + 1;
			$this->lastSector += $sectors; //The GC will clean this shift 'later'
			$indexChanged = true;
		} else if ($this->locationTable[$index][1] != $sectors) {
			$indexChanged = true;
		}
		$this->locationTable[$index][1] = $sectors;
		$this->locationTable[$index][2] = time();

		fseek($this->filePointer, $this->locationTable[$index][0] << 12);
		fwrite($this->filePointer, str_pad(Binary::writeInt($length) . chr(self::COMPRESSION_ZLIB) . $chunkData, $sectors << 12, "\x00", STR_PAD_RIGHT));
		if ($indexChanged) {
			$this->writeLocationIndex($index);
		}
	}

	/**
	 * @param int $index
	 */
	protected function writeLocationIndex(int $index)
	{
		fseek($this->filePointer, $index << 2);
		fwrite($this->filePointer, pack('N', ($this->locationTable[$index][0] << 8) | $this->locationTable[$index][1]), 4);
		fseek($this->filePointer, 4096 + ($index << 2));
		fwrite($this->filePointer, pack('N', $this->locationTable[$index][2]), 4);
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return bool
	 */
	function chunkExists(int $x, int $z)
	{
		return $this->isChunkGenerated(self::getChunkOffset($x, $z));
	}

	/**
	 * @param int $x
	 * @param int $z
	 */
	function removeChunk(int $x, int $z)
	{
		$index = self::getChunkOffset($x, $z);
		$this->locationTable[$index][0] = 0;
		$this->locationTable[$index][1] = 0;
	}

	/**
	 * @param FullChunk $chunk
	 */
	function writeChunk(FullChunk $chunk)
	{
		$chunkData = $chunk->toBinary();
		if ($chunkData !== false) {
			$this->saveChunk($chunk->getX() - ($this->getX() * 32), $chunk->getZ() - ($this->getZ() * 32), $chunkData);
		}
	}

	function close()
	{
		$this->writeLocationTable();
		fclose($this->filePointer);
	}
}