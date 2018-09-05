<?php
namespace App\Pdf;

use Cake\Core\InstanceConfigTrait;
use Cake\Filesystem\Folder;

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
      'background' => WWW_ROOT.'img'.DS.'front.jpg',
    ],

    'cropMarks' => [
      'innerCrop' => 5,
      'length' => 7
    ],

    'margins' => [
      'left' => 5,
      'bottom' => 5
    ]

  ];

  public $pdf;
  public $sticker;

  public function __construct(array $config = [])
  {
    $this->setConfig($config);

    $this->pdf = new PDF($this->getConfig('pdf.orientation'), $this->getConfig('pdf.unit'), $this->getConfig('pdf.size'));
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
    $this->_drawStricker();
    $nextX = $this->pdf->GetX() +  $this->getConfig('margins.left') + $this->sticker->getWidth();
    $nextY = $this->pdf->GetY() +  $this->getConfig('margins.bottom') + $this->sticker->getHeight();

    $enoughWidth = $nextX + $this->sticker->getWidth() <= $this->pdf->GetRightMarginPos();
    $enoughHeight = $nextY + $this->sticker->getHeight() <= $this->pdf->GetBottomMarginPos();

    $this->pdf->SetX($nextX);

    if(!$enoughWidth)
    {
      $this->pdf->SetX($this->pdf->GetLeftMarginPos());
      $this->pdf->SetY($nextY, false);
    }
    if(!$enoughHeight && !$enoughWidth)
    {
      $this->pdf->SetX($this->pdf->GetLeftMarginPos());
      $this->pdf->SetY($this->pdf->GetTopMarginPos(), false);
      $this->pdf->AddPage();
    }

    return $this;
  }

  public function setSticker($width, $height,$background)
  {
    $this->sticker->setbackground($background)->setWidth($width)->setHeight($height);

    $minMargins = 16;
    $number = floor(($this->pdf->GetPageWidth() - $minMargins) / $width);
    $number = floor(($this->pdf->GetPageWidth() - $minMargins - ($number - 1) * $this->getConfig('margins.left')) / $width);
    $margin = $this->pdf->GetPageWidth() - ( $number * $width ) - ( ($number - 1) * $this->getConfig('margins.left') );
    $this->pdf->SetTheMargins($margin/2, 10);

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

  // protected

  protected function _drawStricker()
  {
    $this->pdf->Image($this->sticker->getBackground(), $this->pdf->GetX(), $this->pdf->GetY(), $this->sticker->getWidth());
    return $this;
  }
}
