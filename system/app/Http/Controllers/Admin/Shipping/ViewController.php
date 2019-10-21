<?php

namespace App\Http\Controllers\Admin\Shipping;

use Illuminate\Http\Request;
use Gufy\PdfToHtml\Config;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use App\Http\Controllers\Controller;
use Validator;
use App\Form;
use App\FormType;
use App\Notifications;
use App\PageType;
use App\Menu;
use App\Donor;
use App\User;

class ViewController extends Controller
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

    public function list(Request $request)
    {
        $page = $this->getPage($request);

        $page['datasets']['list'] = [
            'columns' => [
                'First Name' => function($row){
                    return $row->donor()->first()->user_id->first_name;
                } ,

                'Last Name' => function($row){
                    return $row->donor()->first()->user_id->last_name;
                } ,

                'Home Phone' => function($row){
                    return $row->donor()->first()->user_id->home_phone;
                } ,

                'Cell Phone' => function($row){
                    return $row->donor()->first()->user_id->cell_phone;
                } ,

                'Address' => function($row){
                    if($row->donor()->first()->use_mailing_address){
                        return $row->donor()->first()->mailing_address;
                    }

                    return $row->donor()->first()->shipping_address;
                   
                } ,

                'Address Line 2' => function($row){
                    if($row->donor()->first()->use_mailing_address){
                        return $row->donor()->first()->mailing_address2;
                    }

                    return $row->donor()->first()->shipping_address2;
                } ,

                'City' => function($row){
                    if($row->donor()->first()->use_mailing_address){
                        return $row->donor()->first()->mailing_city;
                    }

                    return $row->donor()->first()->shipping_city;
                } ,

                'State' => function($row){
                    if($row->donor()->first()->use_mailing_address){
                        return $row->donor()->first()->mailing_state;
                    }

                    return $row->donor()->first()->shipping_state;
                } ,

                'Zip Code' => function($row){
                    if($row->donor()->first()->use_mailing_address){
                        return $row->donor()->first()->mailing_zipcode;
                    }

                    return $row->donor()->first()->shipping_zipcode;
                } ,
                'Created Date' => 'created_at'
            ],
            'rows' => \App\Shipping::all()
        ];

        $page['update_route'] = "";
        $page['create_route'] = Route('admin.shipping.create');
        $page['view_route'] = "/form?name=";

        return view($page['template'], $page);
    }

    public function create(Request $request)
    {
        $page = $this->getPage($request);

        $page['form_action_route'] = 'admin.shipping.create';
        

        $page['fields']['donors'] = [
            'name' => 'donors',
            'type' => 'select',
            'label' => "Donors",
            'helper' => 'Please select the donor you want to send to a milk kit to',
            'options' => []
        ];

        foreach(Donor::all() as $perm){
            $page['fields']['donors']['options'][] = [
                'name' => $perm->user_id->first_name . "," . $perm->user_id->last_name,
                'value' => $perm->id,
                'selected' => false
            ];
        }

       

        return view($page['template'], $page);
    }

    public function update(Request $request)
    {
       
        $menu = Form::where('id', $request->query('id'))->firstOrFail();
       
        $page = $this->getPage($request);

        $page['form_action_route'] = 'admin.form.update';

        $page['fields'][] = [
            'name' => 'id',
            'type' => 'hidden',
            'value' => $request->query('id'),
        ];

        $page['fields'][] = [
            'name' => 'name',
            'type' => 'text',
            'label' => "Name",
            'helper' => 'The name you want the menu to be referenced in the system',
            'value' => $menu->name
        ];

        $page['fields'][] = [
            'name' => 'description',
            'type' => 'textarea',
            'label' => "Description",
            'helper' => 'The name you want the menu to be referenced in the system',
            'value' => $menu->description
        ];

        $page['fields']['type'] = [
            'name' => 'type',
            'type' => 'select',
            'label' => "Type",
            'helper' => 'The name you want the page to be titled',
            'options' => []
        ];

        foreach(FormType::all() as $pageType){
            $page['fields']['type']['options'][] = [
                'name' => $pageType->name,
                'value' => $pageType->id,
                'selected' => $pageType->name === $menu->form_type_id ? true:false
            ];
        }

        $page['fields']['donors'] = [
            'name' => 'donors',
            'type' => 'checkbox-select',
            'label' => "Donors",
            'helper' => 'The name you want the page to be titled',
            'options' => []
        ];

        foreach(Donor::all() as $perm){
            $page['fields']['donors']['options'][] = [
                'name' => $perm->name,
                'value' => $perm->id,
                'selected' => false
            ];
        }

        $page['fields'][] = [
            'name' => 'approval',
            'type' => 'checkbox',
            'label' => "Requires Approval",
            'checked' => $menu->requires_approval == 1 ? true:false,
        ];

        $page['fields'][] = [
            'name' => 'status',
            'type' => 'checkbox',
            'label' => "Active",
             'checked' => $menu->active == 'Active' ? true:false
        ];

        return view($page['template'], $page);
    }
}