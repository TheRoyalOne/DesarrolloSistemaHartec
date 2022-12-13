<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use App\WorkshopEvent;
use App\User;

class WorkshopsEventsController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile,16);
        $perm_btn =Permission::permBtns($profile,16);
        if($perm==0) {
            return redirect()->route('home');
        } else {

            return view('admin.workshopsevents', compact('perm_btn'));
        }
    }
    public function show($id)
    {
        $data = WorkshopEvent::orderBy('name','asc')->get();
        // dd($data);
        return response()->json($data);
    }

    public function Getinfo($id){
        $id = WorkshopEvent::where('id',$id)->first();
        // dd($id);
        return response()->json(["status"=>true, "data"=>$id]);
    }

    public function store(Request $request)
    {
        $workshop = new WorkshopEvent;
        $workshop->name = $request->name;
        $workshop->prefix_code = $request->prefix_code;
        $workshop->workshop_practice = $request->workshop_practice;
        $workshop->type_workshop_id = $request->type_workshop_id;
        $workshop->type_event_id = $request->type_event_id;
        $workshop->required_material = $request->required_material;
        $workshop->rec_fee_online = $request->rec_fee_online;
        $workshop->rec_fee_presencial = $request->rec_fee_presencial;
        $workshop->rec_fee_business = $request->rec_fee_business;
        $workshop->rec_fee_online_kits = $request->rec_fee_online_kits;
        $workshop->description = $request->description;
        $workshop->save();
        return response()->json(["status"=>true, "message"=>"Creado Exitosamente"]);
    }

    public function actualizar(Request $request)
    {
        $workshop = WorkshopEvent::where('id', $request->id)->first();
        $workshop->name = $request->name;
        $workshop->prefix_code = $request->prefix_code;
        $workshop->workshop_practice = $request->workshop_practice;
        $workshop->type_workshop_id = $request->type_workshop_id;
        $workshop->type_event_id = $request->type_event_id;
        $workshop->required_material = $request->required_material;
        $workshop->rec_fee_online = $request->rec_fee_online;
        $workshop->rec_fee_presencial = $request->rec_fee_presencial;
        $workshop->rec_fee_business = $request->rec_fee_business;
        $workshop->rec_fee_online_kits = $request->rec_fee_online_kits;
        $workshop->description = $request->description;
        $workshop->save();
        return response()->json(["status"=>true, "message"=>"Actualizado Exitosamente"]);
    }

    public function destroy($id, Request $request)
    {
        $user = WorkshopEvent::find($id);
        $user->delete();
        return response()->json(["status"=>true, "data"=>$id]);

    }
}
