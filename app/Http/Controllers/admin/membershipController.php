<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\MessageEnum;
use App\Models\membership;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class membershipController extends Controller
{
    //
    function showMembership(){
        $do = isset($_GET['do']) ? $do = $_GET['do'] : 'Manage';
        $member = membership::select()->orderBy('id', 'DESC')->get();
        return view('admin.adminManagemembership', [
            'memberships' => $member,
            'do'     => $do
        ]);
    }


        function addMembership(Request $request){
      
        Validator::validate($request->all(),[
            'name'              =>'required',
            'email'             => ['required', 'email', 'unique:users,email'],
            'image'             =>['required'],
            'address'           =>'required',
            'phone'             =>'required',
        ],[
            'required'          =>MessageEnum::REQUIRED,
            'email.unique'      => 'هذا الايميل غير متاح',
            'email.email'       => 'هناك خطأ في كتابة الايميل يرجى التاكد منه',
        ]);

        $member = new membership();
        $member->name           = $request->name;
        $member->email          = $request->email;
        $member->address        = $request->address;
        $member->phone          = $request->phone;
        $member->description    = $request->description;
        if($request->hasFile('image'))
            $member->image=$this->uploadFile($request->file('image'));
        if($request->active != null){
            $member->is_active=1;
        }
        return $this->messageRedirectAdd($member->save(), 'membership');
    }

    function editMembership(Request $request,$id){
            Validator::validate($request->all(),[
            'name'          => 'required',
            'email'         => ['required', 'email'],
            'image'         => ['required'],
            'address'       => 'required',
            'phone'         => 'required',
        ],[
            'required'      => MessageEnum::REQUIRED,
            'email.email'   => 'هناك خطأ في كتابة الايميل يرجى التاكد منه',
        ]);

        $member=membership::find($id);
        $old=$request->image_old;

        if($request->active != null)
            $member->is_active = 1;
        
        $member->name           = $request->name;
        $member->email          = $request->email;
        $member->address        = $request->address;
        $member->phone          = $request->phone;
        $member->description    = $request->description;
        if($request->hasFile('image'))
            $member->image=$this->uploadFile($request->file('image'));
        else
            $member->image=$old;
        return $this->messageRedirectUpdate($member->save(), 'membership');
    }

    function activeMembership($id){
        $member=membership::find($id);
        if($member->is_active==0)
            $member->is_active=1;
        else 
            $member->is_active=0;
        return $this->messageRedirectUpdate($member->save(), 'membership');
    }
}
