<?php
namespace App\Pdf;

class Sticker
{
  protected $width = 74;
  protected $height = 105;
  protected $background = null;
  protected $code = null;

  public function setWidth($width){ $this->width = $width; return $this; }
  public function setHeight($height){ $this->height = $height; return $this; }
  public function setBackground($background){ $this->background = $background; return $this; }
  public function setCode($code){ $this->code = $code; return $this; }


  public function getWidth(){ return $this->width; }
  public function getHeight(){ return $this->height; }
  public function getBackground(){ return $this->background; }
  public function getCode(){ return $this->code; }
}
