<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParkingController extends Controller
{
    
    public function incident(){
        return view('pages.parking.incident.index');
    }
    
    
    //Class Modus
    public function modus(){
        return view('pages.parking.modus.index');
    }
    
    //Class floor
    public function floor(){
        return view('pages.parking.floor.index');
    }
    
    //Class apm
    public function action_plan_module(){
        return view('pages.parking.action_plan_module.index');
    }
    
    //Class apt
    public function action_plan_target(){
        return view('pages.parking.action_plan_target.index');
    }
    
    //Class a_p_a
    public function action_plan_activity(){
        return view('pages.parking.action_plan_activity.index');
    }
    
    //Class a_r_e
    public function action_reminder_email(){
        return view('pages.parking.action_reminder_email.index');
    }

}