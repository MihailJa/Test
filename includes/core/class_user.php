<?php

class User {

    // GENERAL

    public static function user_info($data) {
        // vars
        $user_id = isset($data['user_id']) && is_numeric($data['user_id']) ? $data['user_id'] : 0;
        $phone = isset($data['phone']) ? preg_replace('~[^\d]+~', '', $data['phone']) : 0;
        // where
        if ($user_id) $where = "user_id='".$user_id."'";
        else if ($phone) $where = "phone='".$phone."'";
        else return [];
        // info
        $q = DB::query("SELECT user_id, first_name, last_name, middle_name, email, gender_id, count_notifications FROM users WHERE ".$where." LIMIT 1;") or die (DB::error());
        if ($row = DB::fetch_row($q)) {
            return [
                'id' => (int) $row['user_id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'middle_name' => $row['middle_name'],
                'gender_id' => (int) $row['gender_id'],
                'email' => $row['email'],
                'phone' => (int) $row['phone'],
                'phone_str' => phone_formatting($row['phone']),
                'count_notifications' => (int) $row['count_notifications']
            ];
        } else {
            return [
                'id' => 0,
                'first_name' => '',
                'last_name' => '',
                'middle_name' => '',
                'gender_id' => 0,
                'email' => '',
                'phone' => '',
                'phone_str' => '',
                'count_notifications' => 0
            ];
        }
    }

    public static function user_get_or_create($phone) {
        // validate
        $user = User::user_info(['phone' => $phone]);
        $user_id = $user['id'];
        // create
        if (!$user_id) {
            DB::query("INSERT INTO users (status_access, phone, created) VALUES ('3', '".$phone."', '".Session::$ts."');") or die (DB::error());
            $user_id = DB::insert_id();
        }
        // output
        return $user_id;
    }

    // TEST

    public static function owner_info() {
        // your code here ...вывод всей информации об текущем пользователе
         $user = self::user_info($data); //массив данных пользователя


    }

    public static function owner_update($data = []) {
        // your code here ...
/*
        Метод API обновления информации по текущему пользователю

* Название `POST /user.update`
* Content-Type: application/json
* Можно обновлять только поля `first_name`, `last_name`, `middle_name`, `email` и `phone`
* Если одного из полей нет в запросе - оно не обновляется
* Если в запросе нет ни одного из полей - выводим ошибку
* Поля `middle_name` и `email` могут быть пустые
* Поля `first_name`, `last_name` и `phone` должны быть непустыми
* Поле `phone` должно содержать 11 цифр и начинаться с 7
* Email должен автоматически переводиться в нижний регистр
* Телефон должен очищаться от нецифровых символов (при вводе `+7-900-000-00-00` телефон должен корректно обновляться)
* При каждом обновлении профиля в базу должна добавлять запись с уведомлением, что информация обновлена*/

$response = array();
$update_columns = array();
$user_id = htmlspecialchars(strip_tags($data['user_id'])) ?? '';
$first_name = htmlspecialchars(strip_tags($data['first_name'])) ?? '';
$last_name = htmlspecialchars(strip_tags($data['last_name'])) ?? '';
$middle_name = htmlspecialchars(strip_tags($data['middle_name'])) ?? '';
$email = htmlspecialchars(strip_tags($data['email'])) ?? '';
$phone = htmlspecialchars(strip_tags($data['phone'])) ?? '';

if(empty($data) || (!$first_name && !$last_name && !$middle_name && !$email && !$phone)){
    $response['message'] = "empty data";
}

filter_var($email, FILTER_VALIDATE_EMAIL) ? $email = strtolower($email) : $email = '';
$phone =  preg_replace('~[^\d]+~', '', $phone);
preg_match('~^([7])([\d]{10})$~', $phone) ? $phone : $phone = '';


if(trim($first_name) !== "") $update_columns[] = "first_name = '".$first_name."'"; 
if(trim($last_name) !== "") $update_columns[] = "last_name = '".$last_name."'";
if(trim($middle_name) !== "") $update_columns[] = "middle_name = '".$middle_name."'";
if(trim($email) !== "") $update_columns[] = "email = '".$email."'";
if(trim($phone) !== "") $update_columns[] = "phone = '".$phone."'";


    if ($user_id) $where = "user_id='".$user_id."'";
    else if ($phone) $where = "phone='".$phone."'";
    else $response['message'] = "no id or no phone";

    if(count($update_columns) > 0){
        // sql query
        $sql = "UPDATE users SET " . implode(", ", $update_columns) . " WHERE $where";
    }
    $q  = DB::query($sql) or die (DB::error());




    }

}
