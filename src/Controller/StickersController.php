<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Pdf\StickersPDF;

class StickersController extends AppController
{
  public function create()
  {
    $pdf = new StickersPDF();
    $pdf->drawOneStricker()
    ->save();
  }
}
