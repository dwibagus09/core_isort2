<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecurityController extends Controller
{
    
    public function incident(){
        return view('pages.security.incident.index');
    }
    
    
    //Class Modus
    public function modus(){
        return view('pages.security.modus.index');
    }
    
    //Class floor
    public function floor(){
        return view('pages.security.floor.index');
    }
    
    //Class apm
    public function action_plan_module(){
        return view('pages.security.action_plan_module.index');
    }
    
    //Class apt
    public function action_plan_target(){
        return view('pages.security.action_plan_target.index');
    }
    
    //Class a_p_a
    public function action_plan_activity(){
        return view('pages.security.action_plan_activity.index');
    }
    
    //Class a_r_e
    public function action_reminder_email(){
        return view('pages.security.action_reminder_email.index');
    }

}