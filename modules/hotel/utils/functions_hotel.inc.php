<?php

function validate_hotel($value){
  //echo json_encode("Inside validate_products on function products inc php");
//  exit;
    $error = array();
    $valid = true;
    $filter = array(
        'name' => array(
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => array('regexp' => '/^[0-9A-Za-z\s]{1,255}$/')
        ),
        'price' => array(
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => array('regexp' => '/^[0-9]{1,30}$/')
        ),
        'fecha_inicio' => array(
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => array('regexp' => '/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/')
        ),
        'fecha_final' => array(
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => array('regexp' => '/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/')
        ),
    );

    $result = filter_var_array($value, $filter);

    //Non checked data:
    $result['valoracion'] = $value['valoracion'];
    $result['observaciones'] = $value['observaciones'];
    $result['comunidad'] = $value['comunidad'];
    $result['provincia'] = $value['provincia'];
    $result['municipio'] = $value['municipio'];
    $result['wifi'] = $value['wifi'];
    $result['piscina'] = $value['piscina'];
    $result['playa'] = $value['playa'];
    $result['actividades'] = $value['actividades'];
    $result['comida'] = $value['comida'];
    $result['spa'] = $value['spa'];


    if($result['fecha_inicio'] && $result['fecha_final']){
        $dates = validate_dates3($result['fecha_inicio'],$result['fecha_final']);
        if($dates){
            $error['fecha_inicio'] = 'Reception date must be before of the expiration date';
            $error['fecha_inicio'] = 'Expiration date must be after reception date';
            $valid = false;
        }
    }

    if ($result['country']==='Select country'){
            $error['country']="You need to choose a country";
            $valid = false;
        }

    if ($result['province']==='Select province'){
            $error['province']="You need to choose a province";
            $valid = false;
        }

    if ($result['city']==='Select city'){
            $error['city']="You need to choose a city";
            $valid = false;
        }

    if ($result['proddesc']==='Input product description'){
            $error['proddesc']="Please enter a description";
            $valid = false;
    }

    if ($result != null && $result){
        if(!$result['prodname']){
            $error['prodname'] = "PHP Name must be 2 to 30 letters";
            $valid = false;
        }

        if(!$result['prodref']){
            $error['prodref'] = "Reference must be 2 to 30 letters";
            $valid = false;
        }

        if(!$result['prodprice']){
            $error['prodprice'] = "Price must be numbers (like 1.2)";
            $valid = false;
        }

        if (!$result['date_reception']) {
            if ($result['date_reception'] == "") {
                $error['date_reception'] = "Reception date can't be empty";
                $valid = false;
            } else {
                $error['date_reception'] = 'error reception format date (dd/mm/yyyy)';
                $valid = false;
            }
        }

        if (!$result['date_expiration']) {
            if ($result['date_expiration'] == "") {
                $error['date_expiration'] = "Expiration date can't be empty";
                $valid = false;
            } else {
                $error['date_expiration'] = 'error format date (dd/mm/yyyy)';
                $valid = false;
            }
        }

        if(!$result['proddesc']){
            $error['proddesc'] = "Description must be 2 to 90 letters";
            $valid = false;
        }
    } else {
        $valid = false;
    };

    return $return = array('result' => $valid, 'error' => $error, 'data' => $result );
}//End of function validate product


//http://stackoverflow.com/questions/8722806/how-to-compare-two-dates-in-php

//http://php.net/manual/es/datetime.diff.php

function val_dates($datetime1,$datetime2){
    $date1 = new DateTime();
    $newDate1 = $date1->createFromFormat('d/m/Y', $datetime1);
    $date2 = new DateTime();
    $newDate2 = $date2->createFromFormat('d/m/Y', $datetime2);
    var_dump($newDate1->diff($newDate2));

    if($date1 <= $date2){
      return true;
    }
      return false;
 }

function validate_dates($start_days, $dayslight) {
    $start_day = date("m/d/Y", strtotime($start_days));
    $daylight = date("m/d/Y", strtotime($dayslight));

    list($mes_one, $dia_one, $anio_one) = split('/', $start_day);
    list($mes_two, $dia_two, $anio_two) = split('/', $daylight);

    $dateOne = new DateTime($anio_one . "-" . $mes_one . "-" . $dia_one);
    $dateTwo = new DateTime($anio_two . "-" . $mes_two . "-" . $dia_two);

    if ($dateOne <= $dateTwo) {
        return true;
    }
    return false;
}

function validate_dates2($first_date,$second_date){
      $day1 = substr($first_date, 0, 2);
      $month1 = substr($first_date, 3, 2);
      $year1 = substr($first_date, 6, 4);
      $day2 = substr($second_date, 0, 2);
      $month2 = substr($second_date, 3, 2);
      $year2 = substr($second_date, 6, 4);
      //echo json_encode(strtotime($day2 . "-" . $month2 . "-" . $year2);
      //exit;

      if(strtotime($day1 . "-" . $month1 . "-" . $year1) <= strtotime($day2 . "-" . $month2 . "-" . $year2)){
        return true;
      }
        return false;
}

function validate_dates3($date1,$date2){
  $fixedDate = $date1;
  $variableDate = $date2;
  // Now we do our timestamping magic!
  $fixedDate = implode('', array_reverse(explode('/', $fixedDate)));
  $variableDate = implode('', array_reverse(explode('/', $variableDate)));

    if ($variableDate < $fixedDate){ // 20100428 < 20090501
        return true;
    }
        return false;
}

function check_in_range($start_date, $end_date){
      // Convert to timestamp
      $start_ts = strtotime($start_date);
      $end_ts = strtotime($end_date);

      // Check that user date is between start & end
      if ($start_ts <= $end_ts){
        return true;
      }else{
        return false;
      }
}
