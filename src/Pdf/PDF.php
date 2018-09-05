<?
namespace App\Pdf;

use FPDF;

class PDF extends FPDF
{
  public function SetTheMargins($h, $v)
  {
    $this->lMargin = $this->rMargin = $this->x = $h;
    $this->tMargin = $this->bMargin = $this->y = $v;
  }

  public function GetLeftMarginPos()
  {
    return $this->lMargin;
  }

  public function GetTopMarginPos()
  {
    return $this->tMargin;
  }

  public function GetRightMarginPos()
  {
    return $this->w - $this->rMargin;
  }

  public function GetBottomMarginPos()
  {
    return $this->h - $this->bMargin;
  }
}
