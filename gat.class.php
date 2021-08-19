<?php
class GAT
{
    private const HEADER = "GRAT";
    //enums are overrated anyways
    private const TILE_OFFSET = 14;
    private const TILE_SIZE = 20;
    private const TILE_TERRAIN_OFFSET = 16;
    private const TILE_WALKABLE = 0;
    private const TILE_WALL = 1;
    private const TILE_LEDGE = 5;

    protected $path;
    protected $raw_data;
    protected $filesize;
    protected $tilecount;
    protected $tiles = [];
    protected $walkable_tiles = 0;
    protected $wall_tiles = 0;
    protected $ledge_tiles = 0;

    function __construct($path)
    {
        $this->path = $path;
        $this->_parseFile();
    }

     /**
      * Loads the GAT file's contents and collects some tile information
      */
    private function _parseFile()
    {
        $filesize = filesize($this->path);
        $fp = fopen($this->path, 'rb');
        $binary = fread($fp, $filesize);
        fclose($fp);

        $unpacked = unpack(sprintf('C%d', $filesize), $binary);
        $unpacked = array_values($unpacked); //reset array keys

        $this->raw_data = $unpacked;
        $this->tilecount = ($filesize - self::TILE_OFFSET) / self::TILE_SIZE;
        
        for($i = 0, $j = self::TILE_OFFSET; $i < $this->tilecount; $i++, $j += self::TILE_SIZE)
        {
            $this->tiles[] = array_slice($this->raw_data, $j, self::TILE_SIZE);
            switch($this->raw_data[$j + self::TILE_TERRAIN_OFFSET])
            {
                case self::TILE_WALKABLE:
                    $this->walkable_tiles++;
                    break;
                case self::TILE_WALL:
                    $this->wall_tiles++;
                    break;
                case self::TILE_LEDGE:
                    $this->ledge_tiles++;
                    break;
                default:
                    exit('invalid tile symbol');
                    break;
            }
        }
    }

    function getWalkableTiles()
    {
        return $this->walkable_tiles;
    }

    function getLedgeTiles()
    {
        return $this->ledge_tiles;
    }

    function getWallTiles()
    {
        return $this->wall_tiles;
    }

    function getTileData()
    {
        return [
            "Map" => $this->path,
            "Total" => $this->tilecount,
            "Walkable" => $this->walkable_tiles,
            "Wall" => $this->wall_tiles,
            "Ledge" => $this->ledge_tiles
        ];
    }

    //unneeded - this was my first pass through the GAT file format
    function initialFileTest()
    {
        $filesize = filesize($this->path);
        $fp = fopen($this->path, 'rb');
        $binary = fread($fp, $filesize);
        fclose($fp);

        $unpacked = unpack(sprintf('C%d', $filesize), $binary);
        $unpacked = array_values($unpacked); //reset array keys

        $tilecount = ($filesize - self::TILE_OFFSET) / self::TILE_SIZE;

        //should be dec equivalent to ascii "GRAT" (71826584)
        //echo 'header: ' . $unpacked[0] . $unpacked[1] . $unpacked[2] . $unpacked[3];

        $tiles = [];
        
        for($i = self::TILE_OFFSET; $i < $tilecount; $i += self::TILE_SIZE)
        {
            echo $unpacked[$i + 16] . $unpacked[$i + 17] . $unpacked[$i + 18] . $unpacked[$i + 19]. '<br />';
        }
    }
}