<?php 
  //Class to check email on existing.
  class Email_Exists_Checker{ 
    //calls from other scripts// 
    public function check_email($email){ 
      $domain = self::get_domain($email);   //get domain from email 
      getmxrr($domain, $mx_servers);       //get mx servers of domain 
      $telnet_result = self::check_by_telnet($mx_servers[0], $email, $domain);  //check 
      return self::validate(end($telnet_result));  //return result 
    } 
 
    //must work inside// 
    function get_domain($email){ 
      $at_index = strpos($email, '@'); 
      //not valid adress 
      if($at_index == false){ 
        return false; 
      } 
      $domain = substr($email, $at_index + 1); 
      return $domain; 
    } 
 
    function check_by_telnet($mx_host, $email, $domain){ 
      exec('(echo "HELO ' . $domain . '"; sleep 1; echo "MAIL FROM: <no-reply@gmail.com>"; sleep 1; echo "RCPT TO: <' . $email . '>"; sleep 2;) | telnet ' . $mx_host . ' 25' , $telnet_result); 
      return $telnet_result; 
    } 
 
    function validate($string){ 
      $string = substr($string, 0, 3);
      return ($string == "250");
    } 
  } 
?>
