<?php

namespace App\Http\Controllers\Admin\Donor;

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


    public function create(Request $request){
        
        $validator = Validator::make($request->all(), [
            'user' => ['required', 'integer'],
            'date_of_birth' => ['required', 'date'],
            'mailing_address' => ['required', 'string'],
            'mailing_city' => ['required', 'string'],
            'mailing_state' => ['required', 'string'],
            'mailing_zipcode' => ['required', 'numeric'],
            'active' => ['required'],
            'shipping_address' => ['sometimes', 'nullable', 'string'],
            'shipping_city' => ['sometimes', 'nullable', 'string'],
            'shipping_state' => ['sometimes','nullable', 'string'],
            'shipping_zipcode' => ['sometimes', 'nullable', 'numeric'],
            'consent_form' => ['sometimes', 'nullable', 'mimes:doc,docx'],
        ]);

        

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        else{
            $user = new \App\Donor();
            $user->user_id = $request->input('user');
            $user->date_of_birth = $request->input('date_of_birth');
            $user->mailing_address= $request->input('mailing_address');
            $user->mailing_address2= $request->input('mailing_address2');
            $user->mailing_city= $request->input('mailing_city');
            $user->mailing_state= $request->input('mailing_state');
            $user->mailing_zipcode= $request->input('mailing_zipcode');
            if($request->input('use_mailing_address') == "on"){
                $user->shipping_address= $request->input('mailing_address');
                $user->shipping_address2= $request->input('mailing_address2');
                $user->shipping_city= $request->input('mailing_city');
                $user->shipping_state= $request->input('mailing_state');
                $user->shipping_zipcode= $request->input('mailing_zipcode');
            }

            else{
                $user->shipping_address= $request->input('shipping_address');
                $user->shipping_address2= $request->input('shipping_address2');
                $user->shipping_city= $request->input('shipping_city');
                $user->shipping_state= $request->input('shipping_state');
                $user->shipping_zipcode= $request->input('shipping_zipcode');
            }

            $user->active = $request->input('active') == "on" ? true:false;

            if(isset($request->consent_form)){
                $user->consent_form = $request->consent_form->store('form');
                $user->recieved_consent_form = true;
            }
            
          
            $user->save();
            
            return redirect()->route('admin.donors')->with('success','Donor created successfully!');
        }

    }


    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'id'   => ['required'],
            'user' => ['required', 'integer'],
            'date_of_birth' => ['required', 'date'],
            'mailing_address' => ['required', 'string'],
            'mailing_city' => ['required', 'string'],
            'mailing_state' => ['required', 'string'],
            'mailing_zipcode' => ['required', 'numeric'],
            'active' => ['required'],
            'shipping_address' => ['string'],
            'shipping_city' => [ 'string'],
            'shipping_state' => [ 'string'],
            'shipping_zipcode' => [ 'numeric'],
            'consent_form' => [ 'mimes:doc, docx'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        else{
            $user = \App\Donor::where('id', $request->input('id'))->first();
            $user->user_id = $request->input('user');
            $user->date_of_birth = $request->input('date_of_birth');
            $user->mailing_address= $request->input('mailing_address');
            $user->mailing_address2= $request->input('mailing_address2');
            $user->mailing_city= $request->input('mailing_city');
            $user->mailing_state= $request->input('mailing_state');
            $user->mailing_zipcode= $request->input('mailing_zipcode');
            $user->shipping_address= $request->input('shipping_address');
            $user->shipping_address2= $request->input('shipping_address2');
            $user->shipping_city= $request->input('shipping_city');
            $user->shipping_state= $request->input('shipping_state');
            $user->shipping_zipcode= $request->input('shipping_zipcode');
            $user->active = $request->input('active') == "on" ? true:false;
            $user->update();
            
            return redirect()->route('admin.donor', ['id' => $user->id])->with('success','Donor updated successfully!');
        }

    }

    public function delete(Request $request){
        $form = Donor::where('id', $request->query('id'))->firstOrFail();
        $form->delete();
        return redirect()->route('admin.donors')->with('success', 'Successfully deleted Donor', $form->id);
     }


  
}