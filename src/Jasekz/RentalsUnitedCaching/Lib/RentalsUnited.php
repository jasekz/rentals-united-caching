<?php

namespace Jasekz\RentalsUnitedCaching\Lib;
  
class RentalsUnited {  
  
    private $username = null;
    private $password = null;
    private $server_url = 'http://rm.rentalsunited.com/api/Handler.ashx';
    
    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }
  
    /**
    * Get a list of all the location where properties are provided
    * 
    * @return SimpleXMLElement
    */
    
    public function __call($name, $args){

        $post[] = "<Pull_{$name}_RQ>
                    <Authentication>
                      <UserName>".$this->username."</UserName>
                      <Password>".$this->password."</Password>
                    </Authentication>
                  </Pull_{$name}_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;
    }    

    /**
    * Get properties from a specific owner from getOwners()
    * 
    * @param mixed $ownerid, owner ID
    * @return SimpleXMLElement
    */
    
    function ListOwnerProp($ownerid){
        $post[] = "<Pull_ListOwnerProp_RQ>
                  <Authentication>
                    <UserName>".$this->username."</UserName>
                    <Password>".$this->password."</Password>
                  </Authentication>
                  <OwnerID>$ownerid</OwnerID>
                  </Pull_ListOwnerProp_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;                  
    } 

    /**
    * Get all property details based on a property ID from getPropertiesList()
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */

    function ListPropertyBasePrice($pid){
        $post[] = "<Pull_ListPropertyBasePrice_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <PropertyID>$pid</PropertyID>
              </Pull_ListPropertyBasePrice_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;
    }   

    /**
    * Get all property details based on a property ID from getPropertiesList()
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */

    function ListSpecProp($pid){
        $post[] = "<Pull_ListSpecProp_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <PropertyID>$pid</PropertyID>
              </Pull_ListSpecProp_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;
    }      

    /**
    * Get all property details based on a property ID from getPropertiesList()
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */

    function ListPropByCreationDate($from, $to, $includeNLA = false){
        $post[] = "<Pull_ListPropByCreationDate_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <CreationFrom>".$from."</CreationFrom>
                <CreationTo>".$to."</CreationTo>
                <IncludeNLA>". ($includeNLA ? 'true' : 'false') ."</IncludeNLA>
              </Pull_ListPropByCreationDate_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;
    }   

    /**
    * Get all property details based on a property ID from getPropertiesList()
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */

    function GetPropertyExternalListing($pid){
        $post[] = "<Pull_GetPropertyExternalListing_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <Properties>
                <PropertyID>$pid</PropertyID>
                </Properties>
              </Pull_GetPropertyExternalListing_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;
    }     

    /**
    * Get all property details based on a property ID from getPropertiesList()
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */

    function ListPropertyDiscounts($pid){
        $post[] = "<Pull_ListPropertyDiscounts_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <PropertyID>$pid</PropertyID>
              </Pull_ListPropertyDiscounts_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;
    }      

    /**
    * Get all property details based on a property ID from getPropertiesList()
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */

    function ListPropertyChangeLog($pid){
        $post[] = "<Pull_ListPropertyChangeLog_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <PropertyID>$pid</PropertyID>
              </Pull_ListPropertyChangeLog_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;
    }   

    /**
    * Get property blocks
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */

    function ListPropertyBlocks($pid, $dateFrom, $dateTo){
        $post[] = "<Pull_ListPropertyBlocks_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <PropertyID>$pid</PropertyID>
                <DateFrom>$dateFrom</DateFrom>
                <DateTo>$dateTo</DateTo>
              </Pull_ListPropertyBlocks_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;
    }      

    /**
    * Get property reviews
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */

    function ListPropertyReviews($pid){
        $post[] = "<Pull_ListPropertyReviews_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <PropertyID>$pid</PropertyID>
              </Pull_ListPropertyReviews_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;
    }   

    /**
    * Get property availablility
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */

    function ListPropertyAvailabilityCalendar($pid, $dateFrom, $dateTo){
        $post[] = "<Pull_ListPropertyAvailabilityCalendar_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <PropertyID>$pid</PropertyID>
                <DateFrom>$dateFrom</DateFrom>
                <DateTo>$dateTo</DateTo>
              </Pull_ListPropertyAvailabilityCalendar_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;
    }    

    /**
    * Get property availablility
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */

    function ListPropertyMinStay($pid, $dateFrom, $dateTo){
        $post[] = "<Pull_ListPropertyMinStay_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <PropertyID>$pid</PropertyID>
                <DateFrom>$dateFrom</DateFrom>
                <DateTo>$dateTo</DateTo>
              </Pull_ListPropertyMinStay_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;
    }      

    /**
    * Get property availablility
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */

    function GetPropertyAvbPrice($pid, $dateFrom, $dateTo){
        $post[] = "<Pull_GetPropertyAvbPrice_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <PropertyID>$pid</PropertyID>
                <DateFrom>$dateFrom</DateFrom>
                <DateTo>$dateTo</DateTo>
              </Pull_GetPropertyAvbPrice_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;
    }      

    /**
    * Get property availablility
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */

    function ListPropertyPrices($pid, $dateFrom, $dateTo){
        $post[] = "<Pull_ListPropertyPrices_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <PropertyID>$pid</PropertyID>
                <DateFrom>$dateFrom</DateFrom>
                <DateTo>$dateTo</DateTo>
              </Pull_ListPropertyPrices_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return $x;
    }   
    
    /**
    * Default Curl connection
    * 
    * @param mixed $url
    * @param mixed $post_fields
    * @param mixed $head
    * @param mixed $follow
    * @param mixed $header
    * @param mixed $referer
    * @param mixed $is_ssl
    * @param mixed $debug
    */         
                  
    function curlPushBack($url, $post_fields = "", $head = 0, $follow = 1, $header="", $referer="", $is_ssl = false, $debug = 0){

        $ch = curl_init ();

        $header[]="Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[]="Accept-Language: en-us";
        $header[]="Accept-Charset: SO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[]="Keep-Alive: 300";
        $header[]="Connection: keep-alive";

        curl_setopt ($ch, CURLOPT_HEADER, $head);
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, $follow);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.0; en; rv:1.8.0.4) Gecko/20060508 Firefox/1.5.0.4");
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array
              (
                  'Content-type: application/x-www-form-urlencoded; charset=utf-8',
                  'Set-Cookie: ASP.NET_SessionId='.uniqid().'; path: /; HttpOnly'
              ));
        curl_setopt ($ch, CURLOPT_REFERER,$referer);
        curl_setopt ($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $is_ssl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $is_ssl);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

        if ($post_fields != ""){
           if(is_array($post_fields)){
              $post_fields = implode("&",$post_fields);
           }
           curl_setopt ($ch, CURLOPT_POST,1);
           curl_setopt ($ch, CURLOPT_POSTFIELDS,$post_fields);
        }

        $result=curl_exec($ch);
        $err=curl_error($ch);

        $results["messages"] = $result;
        $results["errors"] = $err;
        curl_close($ch);
        return $results;
    } 

}
