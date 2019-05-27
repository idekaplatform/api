<?php
namespace App\Utils;

class Slugger
{
    /** @var \Transliterator **/
    protected $transliterator;
    
    public function __construct()
    {
        $this->transliterator = \Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC');
    }
    
    public function slugify(string $string): string
    {
        return preg_replace(
            '/[^a-z0-9]/',
            '-',
            strtolower(trim(strip_tags($this->transliterator->transliterate($string))))
        );
    }
}