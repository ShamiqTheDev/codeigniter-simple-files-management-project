<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(APPPATH . 'libraries/PhpSpreadsheet/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
class Php_spread_sheet {
 
    //public $param;
    //public $spreadsheet;
    //public $writer;
 
    //public function __construct($param = '"en-GB-x","A4","","",10,10,10,10,6,3')
    public function __construct()
    {
        //$this->param =$param;
        //$this->pdf = new mPDF($this->param);
		
		
		//$this->spreadsheet = new Spreadsheet();
        //$sheet = $spreadsheet->getActiveSheet();
        //$sheet->setCellValue('A1', 'Hello World !');
        
        //$this->writer = new Xlsx($this->spreadsheet);
    }
}

?>