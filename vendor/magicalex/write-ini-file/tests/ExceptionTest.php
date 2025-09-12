<?php

use PHPUnit\Framework\TestCase;
use WriteiniFile\WriteiniFile;

class ExceptionTest extends TestCase
{
    private $file = 'tests/file_ini/corruptiniFile.ini';

    public function testLoadWithoutCorruptiniFile()
    {
        chmod($this->file, 0000);

        try {
            $object = new WriteiniFile($this->file);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $this->assertEquals($error, "Unable to parse file ini : {$this->file}");
    }

    public function testWriteInCorruptiniFile()
    {
        chmod($this->file, 0644);

        try {
            $object = new WriteiniFile($this->file);
            $object->create([
                'section 1' => ['foo' => 'string']
            ]);
            chmod($this->file, 0000);
            $object->write();
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $this->assertEquals($error, "Unable to write in the file ini : {$this->file}");
        chmod($this->file, 0644);
    }
}
