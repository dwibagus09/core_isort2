<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SafetyController extends Controller
{
    //Building Protection Equipment Type
    public function bpe_type(){
        return view('pages.safety.bpe_type.index');
    }
    
    //Building Protection Equipment
    public function bpe(){
        return view('pages.safety.bpe.index');
    }
    
    //Building Accident & Fire Fighting Equipment
    public function baff_equipment(){
        return view('pages.safety.baff_equipment.index');
    }
    
    
    
    public function incident(){
        return view('pages.safety.incident.index');
    }
    
    
    //Class Modus
    public function modus(){
        return view('pages.safety.modus.index');
    }
    
    //Class floor
    public function floor(){
        return view('pages.safety.floor.index');
    }
    
    //Class apm
    public function action_plan_module(){
        return view('pages.safety.action_plan_module.index');
    }
    
    //Class apt
    public function action_plan_target(){
        return view('pages.safety.action_plan_target.index');
    }
    
    //Class a_p_a
    public function action_plan_activity(){
        return view('pages.safety.action_plan_activity.index');
    }
    
    //Class a_r_e
    public function action_reminder_email(){
        return view('pages.safety.action_reminder_email.index');
    }

}