<?php
namespace App\Pdf;

use Cake\Core\InstanceConfigTrait;
use FPDF;

class StickersPDF
{
  use InstanceConfigTrait;

  protected $_defaultConfig = [
    'orientation' => 'P',
    'unit' => 'mm',
    'size' => 'A4',
    'font' => [
      'face' => 'Arial',
      'size' => 8,
      'color' => '#000000'
    ],
    'sticker' = [
      'width' => ,
      'height' => ,
      'image' => null,
    ]
  ];

  public $pdf;

  public $codes = [];

  public function __construct(array $config = [])
  {
    $this->setConfig($config);
    $this->pdf = new FPDF($this->getConfig('orientation'), $this->getConfig('unit'), $this->getConfig('size'));
  }

  public function setSticker($image,$width, $height)
  {
    $this->getConfig('sticker.image', $image);
    $this->getConfig('sticker.width', $width);
    $this->getConfig('sticker.height', $height);
    return $this;
  }

  public function setFont($face,$size,$color)
  {
    $this->getConfig('font.face', $face);
    $this->getConfig('font.size', $size);
    $this->getConfig('font.color', $color);
    return $this;
  }



  public function hexToArray($hex)
  {
    return sscanf($hex, "#%02x%02x%02x");
  }
}
