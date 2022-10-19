<?php
use App;

class Feed {
    public $feed;
    
    public function db() {
        return App::getDbConnection();
    }
    
    public function xmlWriter($data) {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<data>';
        foreach ($data as $row) {
            $xml .= '<x1>' . $row[0] . '</x1>';
            $xml .= '<x2>' . $row[1] . '</x2>';
            $xml .= '<x3>' . $row[2] . '</x3>';
        }
        $xml .= '</data>';
        
        $this->feed = $xml;
    }
    
    public function csvWriter($data) {
        $csv = '';
        foreach ($data as $row) {
            $csv .= $row[0] . ';' . $row[1] . ';' . $row[2] . '\n';
        }
        
        $this->feed = $csv;
    }
    
    public function getFeed($type) {
        if ($type == 'csv') {
            return $this->csvWriter($this->db()->getData());
        } elseif ($type == 'xml') {
            return $this->xmlWriter($this-db()->getData());
        }
    }
}
?>