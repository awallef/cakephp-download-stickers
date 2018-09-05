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
      'path' => TMP.'stickers'.DS,
      'name' => 'stickers.pdf',
      'title' => 'Stickers',
      'author' => 'WGR',
    ],

    'font' => [
      'face' => 'Arial',
      'style' => 'B', // or ''
      'size' => 8,
      'color' => '#000000'
    ],

    'sticker' => [
      'width' => 74,
      'height' => 105,
      'background' => TMP.'stickers'.DS.'front.jpg',
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

    // document
    $this->pdf = new PDF($this->getConfig('pdf.orientation'), $this->getConfig('pdf.unit'), $this->getConfig('pdf.size'));
    $this->pdf->SetTitle($this->getConfig('pdf.title'));
    $this->pdf->SetAuthor($this->getConfig('pdf.author'));
    $this->pdf->SetAutoPageBreak(true);
    $this->pdf->AddPage();

    // Text
    call_user_func_array(array($this->pdf, "SetTextColor"), $this->hexToArray($this->getConfig('font.color')));
    $this->pdf->SetFont($this->getConfig('font.face'), $this->getConfig('font.style'), $this->getConfig('font.size'));

    // Sticker
    $this->sticker = new Sticker();
    $this->sticker->setWidth($this->getConfig('sticker.width'))
      ->setHeight($this->getConfig('sticker.height'))
      ->setbackground($this->getConfig('sticker.background'));
  }

  public function save()
  {
    $folder = new Folder($this->getConfig('pdf.path'), true, 0777);
    $this->pdf->Output('F',$folder->path.$this->getConfig('pdf.name'));
    return $this;
  }

  public function drawText($x, $y, $text)
  {
    $this->pdf->Text($this->pdf->GetX() + $x , $this->pdf->GetY() + $y, $text);
    return $this;
  }

  public function drawOneStricker($text = null, $x = 0, $y = 0)
  {
    $this->_drawStricker();
    if($text) $this->drawText($x, $y, $text);
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
