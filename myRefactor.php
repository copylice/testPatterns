<?php
use App;

class Feed
{
    private $connection;
    private $writers;
    public function __construct(Connection $connection,array $writers){
        $this->connection = $connection;
        $this->writers = $writers;
    }
 
    public function getFeed($type) {
        return $this->getWriter($type)->getData($this->connection->getData());
    }
 
    private function getWriter(string $name): Writer
    {
        foreach ($this->writers as $current) {
            if ($current->supports($name)) {
                return $current;
            }
        }
    
        $message = sprint('Writer named %s is not found', $name);
        throw new WriterNotFoundException($message);
    }
}



interface Writer
{
    public function supports(string $name): bool;
    
    public function getData(array $data): string;
}

class XmlWriter implements Writer
{
    public const TYPE = 'xml';
    
    public function supports(string $name): bool
    {
        return $name === static::TYPE;
    }
    
    public function getData(array $data): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<data>';
        foreach ($data as $row) {
            $xml .= '<x1>' . $row[0] . '</x1>';
            $xml .= '<x2>' . $row[1] . '</x2>';
            $xml .= '<x3>' . $row[2] . '</x3>';
        }
        $xml .= '</data>';
        
        return $xml;
    }
    
    
}

class CsvWriter implements Writer
{
    public const TYPE = 'csv';
    
    public function supports(string $name): bool
    {
        return $name === static::TYPE;
    }
    
    public function getData(array $data): string
    {
        $csv = '';
        foreach ($data as $row) {
            $csv .= $row[0] . ';' . $row[1] . ';' . $row[2] . '\n';
        }
        
        return $csv;
    }
}

class WriterNotFoundException extends InvaidArgumentException
{}