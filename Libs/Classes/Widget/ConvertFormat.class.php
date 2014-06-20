<?php
class ConvertFormat
{
    public static function json_encode ($data)
    {
        switch ($type = gettype($data)) {
            case 'NULL':
                $encodedData = 'null';
                break;

            case 'boolean':
                $encodedData = ($data ? 'true' : 'false');
                break;

            case 'integer':
            case 'double':
            case 'float':
                $encodedData = $data;
                break;

            case 'string':
                $encodedData = '"' . addslashes($data) . '"';
                break;

            case 'object':
                $data = get_object_vars($data);
            case 'array':
                $output_index_count = 0;
                $output_indexed = array();
                $output_associative = array();
                foreach ($data as $key => $value) {
                    $output_indexed[] = self::json_encode($value);
                    $output_associative[] = self::json_encode($key) . ':' . self::json_encode($value);
                    if ($output_index_count !== NULL && $output_index_count++ !== $key) {
                        $output_index_count = NULL;
                    }
                }
                if ($output_index_count !== NULL) {
                    $encodedData = '[' . implode(',', $output_indexed) . ']';
                } else {
                    $encodedData = '{' . implode(',', $output_associative) . '}';
                }
                break;

            default:
                $encodedData = ''; // Not supported
                break;
        }

        return $encodedData;
    }

    public static function json_decode ($data, $assoc=false, $depth=512, $options=0)
    {
        if(function_exists('json_decode')) {
            return json_decode($data, $assoc, $depth);
        }

    }

    /**
     * Rebuild array, and Bind id with specified item. If not specified item name, it will bind the array
     * @param array $list
     * @param string $idLabel The ID label
     * @param string $nameLabel The name label
     *
     * @return array
     */
    static public function bindIdWithName($list, $idLabel, $nameLabel=null)
    {
        $bindList = array();

        foreach((array)$list as $_list) {
            if(isset($_list[$idLabel])) {
                if(isset($nameLabel, $_list[$nameLabel])) {
                    $bindList[$_list[$idLabel]] = $_list[$nameLabel];
                } else {
                    $bindList[$_list[$idLabel]] = $_list;
                }
            }
        }

        return $bindList;
    }

    /**
     * Build array from object list
     * @param array $objectList The object list
     * @param string $getMethod The method to get attribute
     * @param string $idProperty The property name used as array key
     * @param string $nameProperty The property name used as array value
     *
     * @return array
     */
    static public function buildArrayFromObjectList($objectList, $getMethod, $idProperty, $nameProperty=null)
    {
        $bindList = array();

        try {
            foreach((array)$objectList as $_object) {
                $idLabel = call_user_func(array($_object, $getMethod), $idProperty);
                if($idLabel) {
                    if(isset($nameProperty)) {
                        $nameLabel = call_user_func(array($_object, $getMethod), $nameProperty);
                        $bindList[$idLabel] = $nameLabel;
                    } else {
                        $bindList[$idLabel] = $_object;
                    }
                }
            }
        } catch(Exception $e) {

        }

        return $bindList;
    }

    /**
     * Convert date format
     * @param string $fromDate The date string to be converted
     * @param string $fromFormat The format of $fromData
     * @param strng $toFormat The format is used to convert date
     *
     *  @return string
     */
    static public function convertDateFormat($fromDate, $fromFormat, $toFormat)
    {
        if(!$fromDate) {
            return $fromDate;
        }

        $dateTimeInfo = array();
        $toDate = '';
        $i = $startPosition = 0;
        while(isset($fromFormat[$i])) {
            switch ($fromFormat[$i]) {
                case 'Y':
                    //$cutLengths['Y'] =$startPosition;
                    $dateTimeInfo['Y'] = substr($fromDate, $startPosition, 4);
                    $startPosition = $startPosition + 4;
                    break;

                case 'm':
                case 'd':
                case 'H':
                case 'i':
                case 's':
                    //$cutLengths[$fromFormat[$i]] = $startPosition;
                    $dateTimeInfo[$fromFormat[$i]] = substr($fromDate, $startPosition, 2);
                    $startPosition = $startPosition + 2;
                    break;

                default:
                    $startPosition++;
                    break;
            }
            $i++;
        }

        $toDate = $toDate . (isset($dateTimeInfo['Y']) && preg_match('/[0-9]{4}/',$dateTimeInfo['Y']) ? $dateTimeInfo['Y'] : date('Y'));
        $toDate = $toDate . (isset($dateTimeInfo['m']) && preg_match('/[0-9]{2}/',$dateTimeInfo['m']) ? $dateTimeInfo['m'] : date('m'));
        $toDate = $toDate . (isset($dateTimeInfo['d']) && preg_match('/[0-9]{2}/',$dateTimeInfo['d']) ? $dateTimeInfo['d'] : date('d'));
        $toDate = $toDate . ' ';
        $toDate = $toDate . (isset($dateTimeInfo['H']) && preg_match('/[0-9]{2}/',$dateTimeInfo['H']) ? $dateTimeInfo['H'] : date('H'));
        $toDate = $toDate . (isset($dateTimeInfo['i']) && preg_match('/[0-9]{2}/',$dateTimeInfo['i']) ? $dateTimeInfo['i'] : date('i'));
        $toDate = $toDate . (isset($dateTimeInfo['s']) && preg_match('/[0-9]{2}/',$dateTimeInfo['s']) ? $dateTimeInfo['s'] : date('s'));

        $tmpTime = strtotime($toDate);

        return date($toFormat, $tmpTime);
    }

    /**
     * Convert seconds into time format
     * @param int $seconds The seconds
     *
     * @return string  Time format HH:MM:SS.  HH could be larger than 24.
     */
    static public function secondsToTime($seconds)
    {
        $seconds = round($seconds);

        $h = intval($seconds/3600);
        $m = intval(($seconds%3600)/60);
        $s = $seconds%60;

        return sprintf('%d:%02d:%02d', $h, $m, $s);
    }


}
