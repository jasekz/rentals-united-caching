<?php

namespace Jasekz\RentalsUnitedCaching\Lib;

set_time_limit(180); //set the execution time to 3 minutes, in case it takes longer to get results.

/**
* rentals united Class, to import properties, get availability an prices, including online booking.
* 
* @author     Symon Oevering
* @version    1.0.0  
* 
* NOTE: The connection method used is cURL, please make sure you the curl extension is enabled.
* 
* HOW TO USE:
*       
* To use this class please include the file in your code (include 'ru_class.php';), add the username and password and call the class:
* 
* e.g.
* $RU = new rentalsUnited(); 
* 
* PROPERTIES
* ----------
* To download all the properties, this can be done 2 ways, either by Location OR by Owner.
* 
* 1. By Location:
*   1. Get all locations: $RU->getLocations();
*   2. Based on the location IDs get the property list: $RU->getPropertiesList(4558);
*   3. Based on the property list property IDs get the property details: $RU->getProperty(123);
* 
* 2. By Owner:
*   1. Get the owners: $RU->getOwners();
*   2. Based on the owner ID get the owner list: $RU->getOwnerProperties(); 
*   3. Based on the owner list property IDs get the property details: $RU->getProperty(123);
*
* Each location has it's own currency, it is important to note that the rates show in the set currency and very depending on the location.
* A list of all locations with the corresponding currencies can be found here:
*   $RU->getLocationCurrencies(); 
*   
* In the property list ID numbers are found which refer to: 
*   1. Amenities, listed in: $RU->getAmenities() and $RU->getRoomAmenities() ;
*   2. Property type, listed in: $RU->getPropertyTypes();  
* 
* Based on the property ID the calendar, rates and minimum stays can be requested:
*   Calendar: $RU->getCalendar(123);
*   Rates: $RU->getRates(123);
*   Discounts (if set): $RU->getDiscounts(123);
*   Minimum stay: $RU->getMinstay(123);
* 
* BOOKINGS
* ----------
* 
* In order to make a booking it is advised to request the latest rate and availability in order to avoid price differences and double bookings:
* 
* e.g.
* $RU->getRealtimeRates(123,"2013-12-01","2013-12-04");
* 
* This will return a rate including a final availability check. These details are used to book the property:
* e.g.
* $RU->bookProperty(123,"2013-12-01","20123-12-04","300.00","300.00","0","John","Doe",john@doe.com,"001123456789","","Street 1, Apartment 2","123456","4558");
* 
* In case of a succesfull booking a reservation ID will be returned.
* 
* If the booking has to be cancelled (and the conditions allow it), the booking can be cancelled with the reservation ID:
* e.g.
* $RU->cancelBooking(123456));
* 
* All results are returned as a simple xml object.
* 
*/
  
class rentalsUnitedBase {  
  
    private $username = '[your username here]'; //Your username provided by Rentals United
    private $password = '[your password here]'; //Your password provided by Rentals United
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
    
    function getLocations(){
        $post[] = "<Pull_ListLocations_RQ>
                    <Authentication>
                      <UserName>".$this->username."</UserName>
                      <Password>".$this->password."</Password>
                    </Authentication>
                  </Pull_ListLocations_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($x['messages']);
    }

    /**
    * Get a list of all owners, including name, phonenumber and email
    * 
    * @return SimpleXMLElement
    */
    
    function getOwners(){
        $post[] = "<Pull_ListAllOwners_RQ>
            <Authentication>
              <UserName>".$this->username."</UserName>
              <Password>".$this->password."</Password>
            </Authentication>
          </Pull_ListAllOwners_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);    
        return simplexml_load_string($x['messages']);
    }

    /**
    * Get the details of a single owner, Email, phone number etc..
    * 
    * @param mixed $ownerid, Owner ID
    * @param mixed $extended
    */
    
    function getOwnerDetails($ownerid){
        $post[] = "<Pull_GetOwnerDetails_RQ>
                    <Authentication>
                     <UserName>".$this->username."</UserName>
                     <Password>".$this->password."</Password>
                    </Authentication>
                    <OwnerID>$ownerid</OwnerID>
                    </Pull_GetOwnerDetails_RQ>";
        
        $x = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($x['messages']);
    }    
    
    /**
    * Get a list of all properties in a location
    * 
    * @param mixed $loc_code, Location ID listed in getLocations()
    * @return SimpleXMLElement
    */
    function getPropertiesList($loc_code){
        $post[] = "<Pull_ListProp_RQ>
                    <Authentication>
                      <UserName>".$this->username."</UserName>
                      <Password>".$this->password."</Password>
                    </Authentication>
                    <LocationID>$loc_code</LocationID>
                  </Pull_ListProp_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($x['messages']);
    }

    /**
    * Get all property details based on a property ID from getPropertiesList()
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */

    function getProperty($pid){
        $post[] = "<Pull_ListSpecProp_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <PropertyID>$pid</PropertyID>
              </Pull_ListSpecProp_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($x['messages']);
    }    

    /**
    * Get properties from a specific owner from getOwners()
    * 
    * @param mixed $ownerid, owner ID
    * @return SimpleXMLElement
    */
    
    function getOwnerProperties($ownerid){
        $post[] = "<Pull_ListOwnerProp_RQ>
                  <Authentication>
                    <UserName>".$this->username."</UserName>
                    <Password>".$this->password."</Password>
                  </Authentication>
                  <OwnerID>$ownerid</OwnerID>
                  </Pull_ListOwnerProp_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($x['messages']);                  
    }

    /**
    * Get the details for the location from getLocations()
    * 
    * @param mixed $loc_id, location ID
    * @return SimpleXMLElement
    */
  
    function getLocationDetails($loc_id){
        $post[] = "<Pull_GetLocationDetails_RQ>
                    <Authentication>
                      <UserName>".$this->username."</UserName>
                      <Password>".$this->password."</Password>
                    </Authentication>
                    <LocationID>$loc_id</LocationID>
                  </Pull_GetLocationDetails_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($x['messages']);
    }

    /**
    * Get all amenities available per room
    * 
    * @return SimpleXMLElement
    */
    
    function getRoomAmenities(){
        $post[] = "<Pull_ListAmenitiesAvailableForRooms_RQ>
                    <Authentication>
                    <UserName>".$this->username."</UserName>
                    <Password>".$this->password."</Password>
                    </Authentication>
                    </Pull_ListAmenitiesAvailableForRooms_RQ>";
      
        $x = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($x['messages']);
    }
    
    
    /**
    * Get a list of all amenities available
    * 
    * @return SimpleXMLElement
    */
    
    function getAmenities(){
        $post[] = "<Pull_ListAmenities_RQ>
                    <Authentication>
                      <UserName>".$this->username."</UserName>
                      <Password>".$this->password."</Password>
                    </Authentication>
                  </Pull_ListAmenities_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($x['messages']);
    }
    
    /**
    * Get a list of property types supported, one bedroom, tho bedroom, etc
    * 
    * @return SimpleXMLElement
    */
    
    function getPropertyTypes(){
        $post[] = "<Pull_ListPropTypes_RQ>
                    <Authentication>
                      <UserName>".$this->username."</UserName>
                      <Password>".$this->password."</Password>
                    </Authentication>
                   </Pull_ListPropTypes_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($x['messages']);
    }

    
    /**
    * Get a list of all the currencies for each location
    * 
    * @return SimpleXMLElement
    */
    
    function getLocationCurrencies(){
        $post[] = "<Pull_ListCurrenciesWithCities_RQ>
                <Authentication>
                    <UserName>".$this->username."</UserName>
                    <Password>".$this->password."</Password>
                </Authentication>
             </Pull_ListCurrenciesWithCities_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($x['messages']);
    }
    
    /**
    * Get the blocked dates for a property
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */
  
    function getCalendar($pid){
        $post[] = "<Pull_ListPropertyBlocks_RQ>
                  <Authentication>
                    <UserName>".$this->username."</UserName>
                    <Password>".$this->password."</Password>
                  </Authentication>
                  <PropertyID>$pid</PropertyID>
                  <DateFrom>".date("Y-m-d")."</DateFrom>
                  <DateTo>".date("Y-m-d",strtotime("+ 1 year"))."</DateTo>
                </Pull_ListPropertyBlocks_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($x['messages']);
    }

    /**
    * Get the prices for a property
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */
    
    function getRates($pid){
        $post[] = "<Pull_ListPropertyPrices_RQ>
                  <Authentication>
                    <UserName>".$this->username."</UserName>
                    <Password>".$this->password."</Password>
                  </Authentication>
                  <PropertyID>$pid</PropertyID>
                  <DateFrom>".date("Y-m-d")."</DateFrom>
                  <DateTo>".date("Y-m-d",strtotime("+ 1 year"))."</DateTo>
                </Pull_ListPropertyPrices_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($x['messages']);
    }

    /**
    * Get disounts for a property in case set
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */
    
    function getDiscounts($pid){
        $post[] = "<Pull_ListPropertyDiscounts_RQ>
                  <Authentication>
                    <UserName>".$this->username."</UserName>
                    <Password>".$this->password."</Password>
                  </Authentication>
                  <PropertyID>$pid</PropertyID>
                </Pull_ListPropertyDiscounts_RQ>";
                
        $x = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($x['messages']);                         
    }
    
    /**
    * Get the realtime rate for a property
    * 
    * @param mixed $pid, property ID
    * @param mixed $from_date, From date (yyyy-mm-dd)
    * @param mixed $to_date, To date (yyyy-mm-dd)
    * @return SimpleXMLElement
    */
    
    function getRealtimeRates($pid,$from_date,$to_date){
        $post[] = "<Pull_GetPropertyAvbPrice_RQ>
                <Authentication>
                   <UserName>".$this->username."</UserName>
                   <Password>".$this->password."</Password>
                </Authentication>
                <PropertyID>$pid</PropertyID>
                <DateFrom>$from_date</DateFrom>
                <DateTo>$to_date</DateTo>
                </Pull_GetPropertyAvbPrice_RQ>";

        $xmlResults = $this->curlPushBack($this->server_url,$post);
        return simplexml_load_string($xmlResults['messages']);              
    }
    
    /**
    * Get the minimum stay for a property
    * 
    * @param mixed $pid, property ID
    * @return SimpleXMLElement
    */
    
    function getMinstay($pid){
        $post[] = "<Pull_ListPropertyMinStay_RQ>
                  <Authentication>
                    <UserName>".$this->username."</UserName>
                    <Password>".$this->password."</Password>
                  </Authentication>
                  <PropertyID>$pid</PropertyID>
                  <DateFrom>".date("Y-m-d")."</DateFrom>
                  <DateTo>".date("Y-m-d",strtotime("+ 5 year"))."</DateTo>
                </Pull_ListPropertyMinStay_RQ>";
        $x = $this->curlPushBack($this->server_url,$post);  
        return simplexml_load_string($x['messages']);
    }   
  
    /**
    * Make an online booking for a property, in case of success returns a reservation ID
    * 
    * @param mixed $pid, property ID
    * @param mixed $from_date, From date (yyyy-mm-dd) 
    * @param mixed $to_date, To date (yyyy-mm-dd)
    * @param mixed $pax, Number of people
    * @param mixed $RUPrice, Price by Rentals United
    * @param mixed $client_price, Price offered to client
    * @param mixed $already_paid, Amount already paid
    * @param mixed $name, Name of the client
    * @param mixed $sur_name, Sur name of the client
    * @param mixed $email, Email address of the client
    * @param mixed $phone, Phone number of the client
    * @param mixed $skype_id, Skype id/name (in case provided)
    * @param mixed $address, Address of the client
    * @param mixed $zipcode, Zip code of the client (in case provided)
    * @param mixed $city_id, Rentals United City ID of the client from getLocations()
    * @return SimpleXMLElement, reservation ID
    */
  
    function bookProperty($pid,$from_date,$to_date,$pax,$RUPrice,$client_price,$already_paid,$name,$sur_name,$email,$phone,$skype_id="",$address,$zipcode="",$city_id){
        $post[] = "<Push_PutConfirmedReservationMulti_RQ>
                <Authentication>
                  <UserName>".$this->username."</UserName>
                  <Password>".$this->password."</Password>
                </Authentication>
                <Reservation>
                  <StayInfos>
                    <StayInfo>
                      <PropertyID>$pid</PropertyID>
                      <DateFrom>$from_date</DateFrom>
                      <DateTo>$to_date</DateTo>
                      <NumberOfGuests>$pax</NumberOfGuests>
                      <Costs>
                        <RUPrice>$RUPrice</RUPrice>
                        <ClientPrice>$client_price</ClientPrice>
                        <AlreadyPaid>$already_paid</AlreadyPaid>
                      </Costs>
                    </StayInfo>
                  </StayInfos>
                  <CustomerInfo>
                    <Name>$name</Name>
                    <SurName>$sur_name</SurName>
                    <Email>$email</Email>
                    <Phone>$phone</Phone>
                    <SkypeID>$skype_id</SkypeID>
                    <Address>$address</Address>
                    <ZipCode>$zipcode</ZipCode>
                    <ContryID>$city_id</ContryID>
                  </CustomerInfo>
                </Reservation>
              </Push_PutConfirmedReservationMulti_RQ>";
               
        $x = $this->curlPushBack($this->server_url,$post);  
        return simplexml_load_string($x['messages']);
    }  

    /**
    * Cancel a booking
    * 
    * @param mixed $reservationID, reservation ID provided by bookProperty()
    * @return SimpleXMLElement, confirmation of cancellation
    */
    
    function cancelBooking($reservationID){
        $post[] = "<Push_CancelReservation_RQ>
              <Authentication>
                <UserName>".$this->username."</UserName>
                <Password>".$this->password."</Password>
              </Authentication>
              <ReservationID>$reservationID</ReservationID>
           </Push_CancelReservation_RQ>";   
        $x = $this->curlPushBack($this->server_url,$post);  
        return simplexml_load_string($x['messages']);            
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
