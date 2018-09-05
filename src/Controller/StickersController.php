<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Pdf\StickersPDF;

class StickersController extends AppController
{
  public function create()
  {
    $pdf = new StickersPDF();
    $pdf->setSticker(65,65, WWW_ROOT.'img'.DS.'front.jpg')
    ->drawOneStricker()
    ->drawOneStricker()
    ->drawOneStricker()
    ->drawOneStricker()
    ->drawOneStricker()
    ->drawOneStricker()
    ->drawOneStricker()
    ->drawOneStricker()
    ->drawOneStricker()
    ->drawOneStricker()
    ->drawOneStricker()
    ->drawOneStricker()
    ->drawOneStricker()
    ->save();
  }
}
