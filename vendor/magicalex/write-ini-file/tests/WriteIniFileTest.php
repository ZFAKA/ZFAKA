<?php

use PHPUnit\Framework\TestCase;
use WriteiniFile\WriteiniFile;

class WriteIniFileTest extends TestCase
{
    private $var = [
        'section 1' => [
            'foo'        => 'string',
            'bool_true'  => true,
            'bool_false' => false,
            'int_true'   => 1,
            'int_false'  => 0,
            'int'        => 10,
            'float'      => 10.3,
            'foo_array'  => [
                'string',
                10.3,
                true,
                false
            ]
        ],
        'section 2' => [
            'foo'              => 'string',
            'var'              => 'string string',
            'bool_true_string' => 'true',
            'int_true_string'  => '1',
            'int_false_string' => '0',
            'float_string'     => '10.5L',
            'empty_string'     => ''
        ]
    ];

    public function testCreate()
    {
        $writingTest = 'tests/file_ini/testCreate2.ini';
        $expected = 'tests/file_ini/testCreate1.ini';
        $object = new WriteiniFile($writingTest);
        $object->create($this->var);
        $object->write();

        $this->assertFileEquals($expected, $writingTest);
    }

    public function testUpdate()
    {
        $writingTest = 'tests/file_ini/testUpdate2.ini';
        $expected = 'tests/file_ini/testUpdate1.ini';

        $object = new WriteiniFile($writingTest);
        $object->create($this->var);
        $object->write();
        $object->update([
            'section 1' => ['foo' => 'bar', 'int' => 100]
        ]);
        $object->write();

        $this->assertFileEquals($expected, $writingTest);
    }

    public function testRm()
    {
        $writingTest = 'tests/file_ini/testRm2.ini';
        $expected = 'tests/file_ini/testRm1.ini';

        $object = new WriteiniFile($writingTest);
        $object->create($this->var);
        $object->write();
        $object->rm([
            'section 1' => ['foo' => 'string', 'int' => 10]
        ]);
        $object->write();

        $this->assertFileEquals($expected, $writingTest);
    }

    public function testErase()
    {
        $writingTest = 'tests/file_ini/testErase2.ini';
        $expected = 'tests/file_ini/testErase1.ini';

        $object = new WriteiniFile($writingTest);
        $object->create($this->var);
        $object->write();
        $object->erase();
        $object->write();

        $this->assertFileEquals($expected, $writingTest);
    }

    public function testAdd()
    {
        $writingTest = 'tests/file_ini/testAdd2.ini';
        $expected = 'tests/file_ini/testAdd1.ini';

        $object = new WriteiniFile($writingTest);
        $object->create($this->var);
        $object->write();
        $object->add([
            'section 3' => ['foo' => 'bar', 'var_float' => 10.5]
        ]);
        $object->write();

        $this->assertFileEquals($expected, $writingTest);
    }

    public function testWithFiledoesnotExist()
    {
        $writingTest = 'tests/file_ini/donotExist.ini';
        $object = new WriteiniFile($writingTest);

        $this->assertFileNotExists($writingTest);
    }

    public function testEscapeCharacters()
    {
        $writingTest = 'tests/file_ini/testEscapeCharacters2.ini';
        $expected = 'tests/file_ini/testEscapeCharacters1.ini';

        $object = new WriteiniFile($writingTest);
        $object->create([
            'section 1' => [
                'foo' => '/usr/bin/example --name="Greg\'s test" --output=./dist/',
                'bar' => 'Exclamation!question?period.'
            ]
        ]);
        $object->write();

        $this->assertFileEquals($expected, $writingTest);
    }
}
