<?php

namespace pcreplace\sources;

interface ConvertIds
{
	/** @var array */
	const PC_TO_PE_11 = [ // PC Blocks to PE 1.1.0 Blocks
		'31:0' => [32, 0], // Dead Shrub -> Dead Bush

		'43:6' => [43, 7], // Double Nether Brick Slab -> Double Quartz Slab
		'43:7' => [43, 6], // Double Quartz Slab -> Double Nether Brick Slab

		'44:6' => [44, 7], // Nether Brick Slab -> Quartz Slab
		'44:7' => [44, 6], // Quartz Slab -> Nether Brick Slab

		'95:0' => [20, 0], // White Stained Glass -> Glass
		'95:1' => [20, 0], // Orange Stained Glass -> Glass
		'95:2' => [20, 0], // Magenta Stained Glass -> Glass
		'95:3' => [20, 0], // Light Stained Glass -> Glass
		'95:4' => [20, 0], // Yellow Stained Glass -> Glass
		'95:5' => [20, 0], // Lime Stained Glass -> Glass
		'95:6' => [20, 0], // Pink Stained Glass -> Glass
		'95:7' => [20, 0], // Gray Stained Glass -> Glass
		'95:8' => [20, 0], // Light Gray Stained Glass -> Glass
		'95:9' => [20, 0], // Cyan Stained Glass -> Glass
		'95:10' => [20, 0], // Purple Stained Glass -> Glass
		'95:11' => [20, 0], // Blue Stained Glass -> Glass
		'95:12' => [20, 0], // Brown Stained Glass -> Glass
		'95:13' => [20, 0], // Green Stained Glass -> Glass
		'95:14' => [20, 0], // Red Stained Glass -> Glass
		'95:15' => [20, 0], // Black Stained Glass -> Glass

		'119:0' => [90, 0], // End Portal

		'125:0' => [157, 0], // Double Oak Wood Slab
		'125:1' => [157, 1], // Double Spruce Wood Slab
		'125:2' => [157, 2], // Double Birch Wood Slab
		'125:3' => [157, 3], // Double Jungle Wood Slab
		'125:4' => [157, 4], // Double Acacia Wood Slab
		'125:5' => [157, 5], // Double Dark Oak Wood Slab

		'126:0' => [158, 0], // Oak Wood Slab
		'126:1' => [158, 1], // Spruce Wood Slab
		'126:2' => [158, 2], // Birch Wood Slab
		'126:3' => [158, 3], // Jungle Wood Slab
		'126:4' => [158, 4], // Acacia Wood Slab
		'126:5' => [158, 5], // Dark Oak Wood Slab
		// 6,7 not found on Java
		'126:8' => [158, 8], // Upper Oak Wood Slab
		'126:9' => [158, 9], // Upper Spruce Wood Slab
		'126:10' => [158, 10], // Upper Birch Wood Slab
		'126:11' => [158, 11], // Upper Jungle Wood Slab
		'126:12' => [158, 12], // Upper Acacia Wood Slab
		'126:13' => [158, 13], // Upper Dark Oak Wood Slab

		'143:0' => [143, 1], // Wooden Button (Down)
		'143:1' => [143, 5], // Wooden Button (East)
		'143:2' => [143, 4], // Wooden Button (West)
		'143:3' => [143, 3], // Wooden Button (South)
		'143:4' => [143, 2], // Wooden Button (North)
		'143:5' => [143, 0], // Wooden Button (Up)
		// 6,7 not found on Java
		'143:8' => [143, 1], // Wooden Button (Powered, Down)
		'143:9' => [143, 5], // Wooden Button (Powered, East)
		'143:10' => [143, 4], // Wooden Button (Powered, West)
		'143:11' => [143, 3], // Wooden Button (Powered, South)
		'143:12' => [143, 2], // Wooden Button (Powered, North)
		'143:13' => [143, 0], // Wooden Button (Powered, Up)

		'96:0' => [96, 3], // Wooden Trapdoor (Bottom, North)
		'96:1' => [96, 2], // Wooden Trapdoor (Bottom, South)
		'96:2' => [96, 1], // Wooden Trapdoor (Bottom, West)
		'96:3' => [96, 0], // Wooden Trapdoor (Bottom, East)
		'96:4' => [96, 11], // Wooden Trapdoor (Bottom, true, North)
		'96:5' => [96, 10], // Wooden Trapdoor (Bottom, true, South)
		'96:6' => [96, 9], // Wooden Trapdoor (Bottom, true, West)
		'96:7' => [96, 8], // Wooden Trapdoor (Bottom, true, East)
		'96:8' => [96, 7], // Wooden Trapdoor (Top, North)
		'96:9' => [96, 6], // Wooden Trapdoor (Top, South)
		'96:10' => [96, 5], // Wooden Trapdoor (Top, West)
		'96:11' => [96, 4], // Wooden Trapdoor (Top, East)
		'96:12' => [96, 15], // Wooden Trapdoor (Top, true, North)
		'96:13' => [96, 14], // Wooden Trapdoor (Top, true, South)
		'96:14' => [96, 13], // Wooden Trapdoor (Top, true, West)
		'96:15' => [96, 12], // Wooden Trapdoor (Top, true, East)

		'157:0' => [126, 0], // Activator Rail
		'158:0' => [125, 0], // Dropper

		'160:0' => [160, 0], // White Stained Glass Pane -> Stained Glass Pane
		'160:1' => [160, 0], // Orange Stained Glass Pane -> Stained Glass Pane
		'160:2' => [160, 0], // Magenta Stained Glass Pane -> Stained Glass Pane
		'160:3' => [160, 0], // Light Blue Stained Glass Pane -> Stained Glass Pane
		'160:4' => [160, 0], // Yellow Stained Glass Pane -> Stained Glass Pane
		'160:5' => [160, 0], // Lime Stained Glass Pane -> Stained Glass Pane
		'160:6' => [160, 0], // Pink Stained Glass Pane -> Stained Glass Pane
		'160:7' => [160, 0], // Gray Stained Glass Pane -> Stained Glass Pane
		'160:8' => [160, 0], // Light Gray Stained Glass Pane -> Stained Glass Pane
		'160:9' => [160, 0], // Cyan Stained Glass Pane -> Stained Glass Pane
		'160:10' => [160, 0], // Purple Stained Glass Pane -> Stained Glass Pane
		'160:11' => [160, 0], // Blue Stained Glass Pane -> Stained Glass Pane
		'160:12' => [160, 0], // Brown Stained Glass Pane -> Stained Glass Pane
		'160:13' => [160, 0], // Green Stained Glass Pane -> Stained Glass Pane
		'160:14' => [160, 0], // Red Stained Glass Pane -> Stained Glass Pane
		'160:15' => [160, 0], // Black Stained Glass Pane -> Stained Glass Pane

		'166:0' => [0, 0], // Barrier -> Air (PE Block not found)
		'176:0' => [0, 0], // Free-standing Banner -> Air (PE Block not found)
		'177:0' => [0, 0], // Wall-mounted Banner -> Air (PE Block not found)

		//'60:0' => [60, 0], // Farmland -> Farmland
		'60:1' => [60, 0], // Farmland -> Farmland
		'60:2' => [60, 0], // Farmland -> Farmland
		'60:3' => [60, 0], // Farmland -> Farmland
		'60:4' => [60, 0], // Farmland -> Farmland

		'188:0' => [85, 1], // Spruce Fence
		'189:0' => [85, 2], // Birch Fence
		'190:0' => [85, 3], // Jungle Fence
		'191:0' => [85, 0], // Dark Oak Fence
		'192:0' => [85, 4], // Acacia Fence

		'198:0' => [208, 0], // End Rod
		'199:0' => [240, 0], // Chorus Plant
		'202:0' => [201, 2], // Purple Pillar
		'204:0' => [181, 1], // Purple Double Slab
		'205:0' => [182, 1], // Purple Slab

		'207:0' => [0, 0], // Beetroot Block -> Air (PE Block not found)
		'208:0' => [198, 0], // Grass Path
		'210:0' => [188, 0], // Repeating Command Block
		'211:0' => [189, 0], // Chain Command Block
		'212:0' => [207, 0], // Frosted Ice

		'213:0' => [0, 0], // Magma Block -> Air (PE Block not found)
		'217:0' => [0, 0], // Structure Void -> Air (PE Block not found)
		'218:0' => [251, 0], // Observer -> Observer

		'219:0' => [218, 0], // White Shulker Box
		'220:0' => [218, 1], // Orange Shulker Box
		'221:0' => [218, 2], // Magenta Shulker Box
		'222:0' => [218, 3], // Light Blue Shulker Box
		'223:0' => [218, 4], // Yellow Shulker Box
		'224:0' => [218, 5], // Lime Shulker Box
		'225:0' => [218, 6], // Pink Shulker Box
		'226:0' => [218, 7], // Gray Shulker Box
		'227:0' => [218, 8], // Light Gray Shulker Box
		'228:0' => [218, 9], // Cyan Shulker Box
		'229:0' => [218, 10], // Purple Shulker Box
		'230:0' => [218, 11], // Blue Shulker Box
		'231:0' => [218, 12], // Brown Shulker Box
		'232:0' => [218, 13], // Green Shulker Box
		'233:0' => [218, 14], // Red Shulker Box
		'234:0' => [218, 15], // Black Shulker Box

		'235:0' => [220, 0], // White Glazed Terracotta
		'236:0' => [221, 0], // Orange Glazed Terracotta
		'237:0' => [222, 0], // Magenta Glazed Terracotta
		'238:0' => [223, 0], // Light Blue Glazed Terracotta
		'239:0' => [224, 0], // Yellow Glazed Terracotta
		'240:0' => [225, 0], // Lime Glazed Terracotta
		'241:0' => [226, 0], // Pink Glazed Terracotta
		'242:0' => [227, 0], // Gray Glazed Terracotta
		'243:0' => [228, 0], // Light Gray Glazed Terracotta
		'244:0' => [229, 0], // Cyan Glazed Terracotta
		'245:0' => [219, 0], // Purple Glazed Terracotta
		'246:0' => [231, 0], // Blue Glazed Terracotta
		'247:0' => [232, 0], // Brown Glazed Terracotta
		'248:0' => [233, 0], // Green Glazed Terracotta
		'249:0' => [234, 0], // Red Glazed Terracotta
		'250:0' => [235, 0], // Black Glazed Terracotta

		'251:0' => [236, 0], // White Concrete
		'251:1' => [236, 1], // Orange Concrete
		'251:2' => [236, 2], // Magenta Concrete
		'251:3' => [236, 3], // Light Blue Concrete
		'251:4' => [236, 4], // Yellow Concrete
		'251:5' => [236, 5], // Lime Concrete
		'251:6' => [236, 6], // Pink Concrete
		'251:7' => [236, 7], // Gray Concrete
		'251:8' => [236, 8], // Light Gray Concrete
		'251:9' => [236, 9], // Cyan Concrete
		'251:10' => [236, 10], // Purple Concrete
		'251:11' => [236, 11], // Blue Concrete
		'251:12' => [236, 12], // Brown Concrete
		'251:13' => [236, 13], // Green Concrete
		'251:14' => [236, 14], // Red Concrete
		'251:15' => [236, 15], // Black Concrete

		'252:0' => [237, 0], // White Concrete Powder
		'252:1' => [237, 1], // Orange Concrete Powder
		'252:2' => [237, 2], // Magenta Concrete Powder
		'252:3' => [237, 3], // Light Blue Concrete Powder
		'252:4' => [237, 4], // Yellow Concrete Powder
		'252:5' => [237, 5], // Lime Concrete Powder
		'252:6' => [237, 6], // Pink Concrete Powder
		'252:7' => [237, 7], // Gray Concrete Powder
		'252:8' => [237, 8], // Light Gray Concrete Powder
		'252:9' => [237, 9], // Cyan Concrete Powder
		'252:10' => [237, 10], // Purple Concrete Powder
		'252:11' => [237, 11], // Blue Concrete Powder
		'252:12' => [237, 12], // Brown Concrete Powder
		'252:13' => [237, 13], // Green Concrete Powder
		'252:14' => [237, 14], // Red Concrete Powder
		'252:15' => [237, 15], // Black Concrete Powder

		'255:0' => [0, 0], // Structure Block -> Air (PE Block not found)
	];
}