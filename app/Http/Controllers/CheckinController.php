<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Session;

class CheckinController extends Controller
{
    public function Matkul()
    {
      $date = date("l");
      $matkul = "weekend";

      if($date == "Monday"){
        $matkul = '[{"type":"Online","course":"Struktur Data","time":"09:30 - 12:30"}, {"type":"Online","course":"Model Bisnis Digital","time":"13:30 - 16:30"}]';
      } else if($date == "Tuesday"){
        $matkul = '[{"type":"Online","course":"Matriks Dan Vektor","time":"08:30 - 10:30"}]';
      } else if($date == "Wednesday"){
        $matkul = '[{"type":"Online","course":"Matriks Dan Vektor","time":"06:30 - 08:30"}, {"type":"Online","course":"Struktur Data","time":"13:30 - 15:30"},{"type":"Online","course":"Bahasa Indonesia","time":"15:30 - 17:30"}]';
      } else if($date == "Thursday"){
        $matkul = '[{"type":"Online","course":"Kalkulus IIB","time":"12:30 - 14:30"}]';
      } else if($date == "Friday"){
        $matkul = '[{"type":"Online","course":"Pendidikan Agama","time":"06:30 - 08:30"}, {"type":"Online","course":"Struktur Data","time":"09:30 - 11:30"},{"type":"Online","course":"Pendidikan Kewarganegaraan","time":"12:30 - 15:30"},{"type":"Online","course":"Matematika Diskrit","time":"15:30 - 17:30"}]';
      } else if($date == "Saturday"){
        $matkul = '[{"type":"Online","course":"Kalkulus IIB","time":"10:30 - 12:30"}, {"type":"Online","course":"Matematika Diskrit","time":"13:30 - 15:30"}]';
      }

      return $matkul;
    }

    public function index()
    {
      $Checkin = "initiate";
      $GetStatus = "Initiate";
      return view('home', compact('GetStatus','Checkin'));
    }

    public function getToken($username, $password){
      $client = new Client(['http_errors' => false]);
      $GetToken = $client->request('POST', "https://gateway.telkomuniversity.ac.id/issueauth?username=". $username ."&password=". $password);
      if(!empty(json_decode($GetToken->getBody())->status)){
          $GetToken = "Invalid";
      } else {
        $GetToken = json_decode($GetToken->getBody())->token;
      }

      return $GetToken;
    }

    public function get_status_checkin($username, $password)
    {
      $Checkin = "initiate";
      $GetStatus = "initiate";

      $token = $this->getToken($username, $password);
      if($token != "Invalid"){
        $client = new Client();
        $GetStatus = $client->request('GET', 'https://gateway.telkomuniversity.ac.id/6963684dc52822cbea00f55712020cb7', [
             'headers' => [
                 'Authorization' => 'Bearer '.$token
             ]
        ]);
        $GetStatus = json_decode($GetStatus->getBody())->status;
      } else {
        $GetStatus ="Invalid";
      }

      return view('home', compact('GetStatus','Checkin'));
    }

    public function checkin($username, $password, $city)
    {
      $matkul = $this->Matkul();
      $token = $this->getToken($username, $password);

      $client = new Client();
      $GetData = $client->request('GET', 'https://gateway.telkomuniversity.ac.id/issueprofile', [
           'headers' => [
               'Authorization' => 'Bearer '.$token
           ]
      ]);
      $GetData = json_decode($GetData->getBody());
      $client = new Client();
      $Checkin = $client->request('POST', 'https://gateway.telkomuniversity.ac.id/66d72fbbf2ada6ef1fc88bf67e8e50db', [
           'headers' => [
               'Authorization' => 'Bearer '.$token
           ],
           'form_params' => [
             'activities'             => $matkul,
             'studentid'              => $GetData->numberid,
             'studyprogramid'         => $GetData->studyprogramid,
             'facultyid'              => $GetData->facultyid,
             'studentclass'           => $GetData->studentclass,
             'is_dorm'                => 0,
             'dorm_building'          => null,
             'dorm_floor'             => null,
             'dorm_room'              => null,
             'is_healthy'             => 1,
             'symptoms'               => '{"health_degre_38":0,"cough":0,"flue":0,"throat_pain":0, "blown":0,"other":0}',
             'category'               => null,
             'is_14travel'            => null,
             'is_abroad'              => null,
             'abroad_country'         => null,
             'domestic_city'          => null,
             'livetype'               => 2,
             'boarding_area'          => null,
             'boarding_name'          => null,
             'farmhouse_destination'  => 1,
             'farmhouse_city'         => $city,
             'is_stay'                => null,
             'stay_reason'            => null,
             'leave_plan'             => null,
             'foods_need'             => '{"online_service":0, "nearest_food_stall":0, "other":0}',
             'foods_difficulty'       => null,
             'is_certificate'         => null,
             'certificate_destination'=> null,
             'curenttemperature'      => null,
             'schoolyear'             => $GetData->schoolyear
           ]
      ]);
      $Checkin = json_decode($Checkin->getBody())->status;
      $GetStatus = null;
      return view('home', compact('Checkin', 'GetStatus'));
    }
}
