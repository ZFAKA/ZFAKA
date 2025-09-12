<?php

namespace WriteiniFile;

/**
 * Class WriteiniFile.
 */
class WriteiniFile
{
    /**
     * @var string $path_to_ini_file
     */
    protected $path_to_ini_file;

    /**
     * @var array $data_ini_file
     */
    protected $data_ini_file;

    /**
     * Constructor.
     *
     * @param string $ini_file
     */
    public function __construct($ini_file)
    {
        $this->path_to_ini_file = $ini_file;

        if (file_exists($ini_file) === true) {
            $this->data_ini_file = @parse_ini_file($ini_file, true, INI_SCANNER_TYPED);
        } else {
            $this->data_ini_file = [];
        }

        if (false === $this->data_ini_file) {
            throw new \Exception(sprintf('Unable to parse file ini : %s', $this->path_to_ini_file));
        }
    }

    /**
     * method for change value in the ini file.
     *
     * @param array $new_value
     */
    public function update(array $new_value)
    {
        $this->data_ini_file = array_replace_recursive($this->data_ini_file, $new_value);
    }

    /**
     * method for create ini file.
     *
     * @param array $new_ini_file
     */
    public function create(array $new_ini_file)
    {
        $this->data_ini_file = $new_ini_file;
    }

    /**
     * method for erase ini file.
     */
    public function erase()
    {
        $this->data_ini_file = [];
    }

    /**
     * method for add new value in the ini file.
     *
     * @param array $add_new_value
     */
    public function add(array $add_new_value)
    {
        $this->data_ini_file = array_merge_recursive($this->data_ini_file, $add_new_value);
    }

    /**
     * method for remove some values in the ini file.
     *
     * @param array $add_new_value
     */
    public function rm(array $rm_value)
    {
        $this->data_ini_file = self::arrayDiffRecursive($this->data_ini_file, $rm_value);
    }

    /**
     * method for write data in the ini file.
     *
     * @return bool true for a succes
     */
    public function write()
    {
        $data_array = $this->data_ini_file;
        $file_content = null;
        foreach ($data_array as $key_1 => $groupe) {
            $file_content .= "\n[".$key_1."]\n";
            foreach ($groupe as $key_2 => $value_2) {
                if (is_array($value_2)) {
                    foreach ($value_2 as $key_3 => $value_3) {
                        $file_content .= $key_2.'['.$key_3.'] = '.self::encode($value_3)."\n";
                    }
                } else {
                    $file_content .= $key_2.' = '.self::encode($value_2)."\n";
                }
            }
        }
        $file_content = preg_replace('#^\n#', '', $file_content);
        $result = @file_put_contents($this->path_to_ini_file, $file_content);
        if (false === $result) {
            throw new \Exception(sprintf('Unable to write in the file ini : %s', $this->path_to_ini_file));
        }

        return ($result !== false) ? true : false;
    }

    /**
     * method for encode type for ini file.
     *
     * @param mixed $value
     *
     * @return string
     */
    private static function encode($value)
    {
        if ($value == '1' || $value === true) {
            return 1;
        } elseif ($value == '0' || $value === false) {
            return 0;
        } elseif ($value == '') {
            return '""';
        }

        if (is_numeric($value)) {
            $value = $value * 1;
            if (is_int($value)) {
                return (int) $value;
            } elseif (is_float($value)) {
                return (float) $value;
            }
        } // @codeCoverageIgnore

        return '"'.str_replace('"', '\"', $value).'"';
    }

    /**
     * Computes the difference of 2 arrays recursively
     * source : http://php.net/manual/en/function.array-diff.php#91756.
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    private static function arrayDiffRecursive(array $array1, array $array2)
    {
        $finalArray = [];
        foreach ($array1 as $KeyArray1 => $ValueArray1) {
            if (array_key_exists($KeyArray1, $array2)) {
                if (is_array($ValueArray1)) {
                    $arrayDiffRecursive = self::arrayDiffRecursive($ValueArray1, $array2[$KeyArray1]);
                    if (count($arrayDiffRecursive)) {
                        $finalArray[$KeyArray1] = $arrayDiffRecursive;
                    }
                }
            } else {
                $finalArray[$KeyArray1] = $ValueArray1;
            }
        }

        return $finalArray;
    }
}
