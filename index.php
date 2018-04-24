<?php

const DEFAULT_COLOR_LENGTH = 3;
const DEFAULT_BASE_CONVERT = 16;
const DEFAULT_SQUARE = false;

$name = getGet('n', '');
$colorLength = getGet('cl', DEFAULT_COLOR_LENGTH);
$baseConvert = getGet('b', DEFAULT_BASE_CONVERT);
$square = (bool) getGet('s', DEFAULT_SQUARE);

$total = getNameProduct($name, $square);

$hex = hexify($total, $baseConvert, $colorLength);

$colors = makeColors($hex, $colorLength);

$divs = makeDivs($colors);

echo implode("\n", $divs);

/**
 * @param string $key
 * @param mixed|null $default
 * @return mixed
 */
function getGet(string $key, $default = null)
{
    return isset($_GET[ $key ]) ? $_GET[ $key ] : $default;
}

/**
 * @param string $name
 * @param bool $square
 * @return int
 */
function getNameProduct(string $name, bool $square): int
{
    $length = strlen($name);
    $total = null;

    for ($i = 0; $i < $length; $i++) {
        $letter = $name[ $i ];
        $letterNumber = ord($letter);
        $letterNumber *= ($square ? $letterNumber : 1);
        $total = $letterNumber * ($total ?? 1);
    }

    return $total;
}

/**
 * @param int $total
 * @param int $baseConvert
 * @param int $colorLength
 * @return string
 */
function hexify(int $total, int $baseConvert, int $colorLength): string
{
    $hex = base_convert($total, 10, $baseConvert);
    $hexLength = strlen($hex);
    $newHexLength = $hexLength + ($colorLength - ($hexLength % $colorLength));
    $hex = str_pad($hex, $newHexLength, '0', STR_PAD_LEFT);
    return $hex;
}

/**
 * @param string $hex
 * @param int $colorLength
 * @return array
 */
function makeColors(string $hex, int $colorLength): array
{
    $colors = str_split($hex, $colorLength);
    return $colors;
}

/**
 * @param array $colors
 * @return array
 */
function makeDivs(array $colors): array
{
    $colorCount = count($colors);
    $widthPercent = (1 / $colorCount) * 100;
    $divs = [];

    foreach ($colors as $color) {
        $divs[] = makeDiv($color, $widthPercent);
    }

    return $divs;
}

/**
 * @param string $color
 * @param float $widthPercent
 * @return string
 */
function makeDiv(string $color, float $widthPercent): string
{
    return "<div style=\"background-color: #{$color}; width: {$widthPercent}%; height: 100%; float: left; /*margin-right: 3px;*/\"></div>";
}
