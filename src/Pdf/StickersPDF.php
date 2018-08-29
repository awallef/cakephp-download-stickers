<?php
namespace App\Pdf;

use Cake\Core\InstanceConfigTrait;
use Cake\Filesystem\Folder;
use FPDF;

class StickersPDF
{
  use InstanceConfigTrait;

  protected $_defaultConfig = [

    'pdf' => [
      'orientation' => 'P',
      'unit' => 'mm',
      'size' => 'A4',
      'reverse' => false,
      'path' => APP.'stickers'.DS,
      'name' => 'stickers.pdf',
      'title' => 'Stickers',
      'author' => 'WGR',
    ],

    'font' => [
      'face' => 'Arial',
      'size' => 8,
      'color' => '#000000'
    ],

    'sticker' => [
      'width' => 74,
      'height' => 105,
      'background' => WWW_ROOT.'img'.DS.'chucknorris.png',
    ],

    'cropMarks' => [
      'innerCrop' => 5,
      'length' => 7
    ],

  ];

  public $pdf;
  public $sticker;

  public function __construct(array $config = [])
  {
    $this->setConfig($config);

    $this->pdf = new FPDF($this->getConfig('pdf.orientation'), $this->getConfig('pdf.unit'), $this->getConfig('pdf.size'));
    $this->pdf->SetTitle($this->getConfig('pdf.title'));
    $this->pdf->SetAuthor($this->getConfig('pdf.author'));
    $this->pdf->SetAutoPageBreak(true);
    $this->pdf->AddPage();

    $this->sticker = new Sticker();
    $this->sticker->setWidth($this->getConfig('sticker.width'))
      ->setHeight($this->getConfig('sticker.height'))
      ->setbackground($this->getConfig('sticker.background'));
  }

  public function save()
  {
    $folder = new Folder($this->getConfig('pdf.path'), true, 0755);
    $this->pdf->Output('F',$folder->path.$this->getConfig('pdf.name'));
    return $this;
  }

  public function drawOneStricker()
  {
    $this->pdf->Image($this->sticker->getBackground(), 0, 0, $this->sticker->getWidth());
    return $this;
  }

  public function setSticker($background,$width, $height)
  {
    $this->sticker->setbackground($background)->setWidth($width)->setHeight($height);
    return $this;
  }

  public function setCode($code)
  {
    $this->sticker->setCode($code);
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
