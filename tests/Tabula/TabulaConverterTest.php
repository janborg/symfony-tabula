<?php

namespace Janborg\Tabula\Test;

use Janborg\Tabula\Tabula\TabulaConverter;
use PHPUnit\Framework\TestCase;

class TabulaConverterTest extends TestCase
{
    
    public function testPdfFileIsConvertedToJson()
    {
        $file = __DIR__ .'/Testfile.pdf';

        $tabula = new TabulaConverter();
    
        $jsonResult = $tabula->setPdf($file)
            ->setOptions(
                [
                    'format' => 'json',
                    'pages' => 'all',
                    'lattice' => true,
                    'stream' => true,
                ]
            )
            ->convert();
        
        $this->assertJson($jsonResult);
        
        $arrResult = json_decode($jsonResult, true);
        $arrTableValues = $arrResult[0]['data'];
                
        $this->assertArrayHasKey('data', $arrResult[0]);
        $this->assertEquals('Spalte 1', $arrTableValues[0][0]['text']);
        $this->assertEquals('1', $arrTableValues[1][0]['text']);
        $this->assertEquals('12', $arrTableValues[4][2]['text']);
    }

    public function testAllTablesAreConverted()
    {
        $file = __DIR__ .'/Testfile.pdf';

        $tabula = new TabulaConverter();
    
        $jsonResult = $tabula->setPdf($file)
            ->setOptions(
                [
                    'format' => 'json',
                    'pages' => 'all',
                    'lattice' => true,
                    'stream' => true,
                ]
            )
            ->convert();
        
        $arrResult = json_decode($jsonResult, true);
        
        $expectedAmountOfTables = 4;

        $this->assertEquals($expectedAmountOfTables, count($arrResult));
    }

    public function testAllColumsAndRowsAreConverted()
    {
        $file = __DIR__ .'/Testfile.pdf';

        $tabula = new TabulaConverter();
    
        $jsonResult = $tabula->setPdf($file)
            ->setOptions(
                [
                    'format' => 'json',
                    'pages' => 'all',
                    'lattice' => true,
                    'stream' => true,
                ]
            )
            ->convert();
        
        $arrResult = json_decode($jsonResult, true);
        $arrTableOne = $arrResult[0]['data'];
        $arrTableTwo = $arrResult[1]['data'];
        $arrTableThree = $arrResult[2]['data'];
        $arrTableFour = $arrResult[3]['data'];
        
        $this->assertEquals('Spalte 1', $arrTableOne[0][0]['text']);
        $this->assertEquals('12', $arrTableOne[4][2]['text']);

        $this->assertEquals('Spalte 1', $arrTableTwo[0][0]['text']);
        $this->assertEquals('Zwölf', $arrTableTwo[4][2]['text']);

        $this->assertEquals('Spalte 1', $arrTableThree[0][0]['text']);
        $this->assertEquals('24', $arrTableThree[4][2]['text']);

        $this->assertEquals('Spalte 1', $arrTableFour[0][0]['text']);
        $this->assertEquals('Vierundzwanzig', $arrTableFour[4][2]['text']);
    }
}