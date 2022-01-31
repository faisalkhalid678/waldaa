<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function buildResponse($type = null, $message = '', $data = []) {

    $type = ($type == 'success') ? '200' : '400';
    if (empty($data)) {
        $data = new stdClass();
    } else {
        $data = replace_null_with_empty_string($data);
    }


    return response()->json(['status' => $type, 'message' => $message, 'data' => $data]);
}

function buildResponse_pc($type = null, $message = '', $data = []) {

    $type = ($type == 'success') ? '200' : '400';


    return response()->json(['status' => $type, 'message' => $message, 'data' => $data]);
}

function buildResponseHomepage($type = null, $message = '', $data = [], $data2 = [], $data3, $data4) {

    $type = ($type == 'success') ? '200' : '400';
    if (empty($data)) {
        $data = new stdClass();
    } else {
        $data = replace_null_with_empty_string($data);
    }
    return response()->json(['status' => $type, 'message' => $message, 'sale_data' => $data, 'featured_products' => $data2, 'categories' => $data3, 'promotions' => $data4]);
}

function buildResponsePlain($type = null, $message = '', $data = []) {
    $type = ($type == 'success') ? '200' : '400';
    return response()->json(['status' => $type, 'message' => $message, 'data' => replace_null_with_empty_string($data)]);
}

function buildResponseError() {
    return $validation_error = new stdClass();
}

function replace_null_with_empty_string($array) {
    foreach ($array as $key => $value) {
        if (is_array($value))
            $array[$key] = replace_null_with_empty_string($value);
        else {
            if (is_null($value))
                $array[$key] = "";
        }
    }
    return $array;
}

//Forget Password
function sendEmail($name, $verification_code, $email) {

    $to_name = ucfirst($name);
    $to_email = $email;
    $data = array('name' => $name, "verification_code" => $verification_code);

    Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
        $message->to($to_email, $to_name)
                ->subject('Forget Password');
        $message->from('waldaainfo@gmail.com', 'Waldaa-Support');
    });
    return TRUE;
}

function getConstant($key) {
    return \Config::get('constants.' . $key);
    exit;
}
