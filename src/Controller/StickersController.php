<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Pdf\StickersPDF;
use League\Csv\Reader;
use League\Csv\Statement;

class StickersController extends AppController
{
  public function create()
  {

    // csv
    $csv = Reader::createFromPath(TMP.'stickers'.DS.'codes.csv', 'r');
    $records = $csv->getRecords();

    // only some...
    //$stmt = (new Statement())->limit(10);
    //$records = $stmt->process($csv);

    // draw back
    $back = (new StickersPDF(['pdf.name' => 'back.pdf','font.color' => '#000000']))
    ->setSticker(65,65, TMP.'stickers'.DS.'back.jpg');
    foreach ($records as $offset => $record) $back->drawOneStricker($record[0], 27, 47.5);
    $back->save();

    // draw front
    $front = (new StickersPDF(['pdf.name' => 'front.pdf']))
    ->setSticker(65,65, TMP.'stickers'.DS.'front.jpg');
    for($i = 0; $i <= $offset; $i++) $front->drawOneStricker();
    $front->save();
  }
}
