<?php

class Notification {

    // TEST
     // $title, $description, $date_time, $is_read
    // your code here ...
    
    public static function notifications_info($unread = false){
        $notifications = [];
        $user_id = Session::$user_id;
              
        if ($user_id) $where = "user_id='".$user_id."'";     
        if ($unread)  $where .= " and is_read=true";
        else return [];

        $q = DB::query("SELECT title, description, date_time, is_read FROM notifications WHERE ".$where." ;") or die (DB::error());
        if ($result = DB::fetch_all($q)) {            
            $notifications = $result;
        } else {
            $notifications = [
                'title' => '',
                'description' => '',
                'date_time' => '',
                'is_read' => '',
                
            ];
        }

        return $notifications;
    }

    public static function notifications_read(){
        //заменить is_read на true
        $user_id = Session::$user_id;
        if(!$user_id)  error_response(1006, 'User no authorization.');
       
        if ($user_id) $where = "user_id='".$user_id."'";  

        $q = DB::query("UPDATE notifications SET is_read=TRUE  WHERE ".$where.";") or die (DB::error());
         
        return ['success' => true];

    }
}
