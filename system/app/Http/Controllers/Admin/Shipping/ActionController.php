<?php

namespace App\Http\Controllers\Admin\Shipping;

use Illuminate\Http\Request;
use Gufy\PdfToHtml\Config;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use App\Http\Controllers\Controller;
use Validator;
use App\Form;
use App\Notifications;
use App\PageType;
use App\Menu;
use App\NotificationTypes;
use App\Donor;

class ActionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'donors.*' => ['required'],
           
            
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        else{
         

            $user = new \App\Shipping();
            $user->donor_id  = $request->input('donors');
            $user->save();
            
            
         
            return redirect()->route('admin.shipping');
        }

    }


    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required'],
            'id' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        else{
            $user = \App\Form::where('id', $request->input('id'))->first();
            $user->name = $request->input('name');
            $user->description = $request->input('description');
            $user->form_type_id = $request->input('type');
            $user->requires_approval = !is_null($request->input('approval')) && $request->input('approval') == 'on' ? true:false; 
            $user->active = !is_null($request->input('status')) && $request->input('status') == 'on' ? true:false; 
            $user->update();

           
            return redirect()->route('admin.form');
        }

    }


    public function export(Request $request){
        $validator = Validator::make($request->all(), [
            'exports' => ['required'],
           
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $csv = [];
       
        foreach($request->input('exports') as $donor){
            $d = Donor::where('id', \App\Shipping::where('id', $donor)->first()->donor_id)->first();
            $csv[] = [
                $d->id,
                $d->user_id->first_name,
                $d->user_id->last_name,
                $d->user_id->home_phone,
                $d->user_id->cell_phone,
                $d->shipping_address,
                $d->shipping_address2,
                $d->shipping_city,
                $d->shipping_state,
                $d->shipping_zipcode
                
            ];

            \App\Shipping::where('id', $donor)->delete();
        }

        $name = \uniqid();

        $fp = fopen(storage_path() . '/app/public/'.$name.'.csv', 'w');

        foreach ($csv as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);

        
        session('refresh', true);
        return response()->download(storage_path() . '/app/public/'.$name.'.csv');
    }

  
}
