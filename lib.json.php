<?php

// Without php 5.2 we need a set of custom function

function getval($val)
{
        if(is_string($val)) return '"'.rawurlencode($val).'"';
        elseif(is_int($val)) return sprintf('%d', $val);
        elseif(is_float($val)) return sprintf('%F', $val);
        elseif(is_bool($val)) return ($val ? 'true' : 'false');
        else  return 'null';
}
    
function custom_json_encode($array)
{
	// determine type
	if(is_numeric(key($array))) {

    // indexed (list)
    $output = '[';
    for($i = 0, $last = (sizeof($array) - 1); isset($array[$i]); ++$i) {
    	if(is_array($array[$i])) $output .= custom_json_encode($array[$i]);
        else  $output .= getval($array[$i]);
        if($i !== $last) $output .= ',';
        }
    $output .= ']';

        } else {

            // associative (object)
            $output = '{';
            $last = sizeof($array) - 1;
            $i = 0;
            foreach($array as $key => $value) {
                $output .= '"'.$key.'":';
                if(is_array($value)) $output .= custom_json_encode($value);
                else  $output .= getval($value);
                if($i !== $last) $output .= ',';
                ++$i;
            }
            $output .= '}';

        }

        // return
        return $output;

    }
    
function queryToJson($result, $header)
	{
		$resultArray = array();
		$count = 0;
		while($i = mysql_fetch_row($result))
		{
			for($k = 0; $k < count($i); $k++)
			{
				$resultArray[$count][mysql_field_name($result, $k)] = $i[$k];	
			}
			$count++;
		}
		
		if (PHP_VERSION_ID > 50100) {
			//echo "using json_encode - detected PHP version ". PHP_VERSION_ID;
			$data = json_encode($resultArray);
		} else {
			//echo "using custom_json_encode - detected PHP version ". PHP_VERSION_ID;
			$data = custom_json_encode($resultArray);
		}
		
		$data = "{\"$header\":".$data."}";
		return $data;
	}    

function mkJsonResult($title,$num,$rowsPerPage, $qry_result, $fieldsArray){
	
	$i=0;	
	$fieldsArrayKeys = array_keys($fieldsArray);
	
	$returnStr = queryToJson($qry_result,$title); 	

	return 	$returnStr;
}


?>