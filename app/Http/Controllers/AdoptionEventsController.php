<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use App\User;
use App\AdoptionEvent;

class AdoptionEventsController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile,8);
        $perm_btn =Permission::permBtns($profile,8);
        if($perm==0) {
            return redirect()->route('home');
        } else {

            return view('admin.adoptionevents', compact('perm_btn'));
        }
    }

    public function show($id)
    {
        $data = AdoptionEvent::orderBy('name','asc')->get();
        // dd($data);
        return response()->json($data);
    }

    public function Getinfo($id){
        $id = AdoptionEvent::where('id',$id)->first();
        // dd($id);
        return response()->json(["status"=>true, "data"=>$id]);
    }

    public function store (Request $request)
    {
        $adoption = new AdoptionEvent;
        $adoption->name = $request->name;
        $adoption->prefix_code = $request->prefix_code;
        $adoption->trees = $request->trees;
        $adoption->recovery_fee = $request->recovery_fee;
        $adoption->description = $request->description;
        $adoption->save();
        return response()->json(["status"=>true, "message"=>"Creado Exitosamente"]);

    }

    public function actualizar (Request $request)
    {
        $adoption = AdoptionEvent::where('id', $request->id)->first();
        $adoption->name = $request->name;
        $adoption->prefix_code = $request->prefix_code;
        $adoption->trees = $request->trees;
        $adoption->recovery_fee = $request->recovery_fee;
        $adoption->description = $request->description;
        $adoption->save();
        return response()->json(["status"=>true, "message"=>"Actualizado Exitosamente"]);

    }

    public function destroy($id, Request $request)
    {
        $user = AdoptionEvent::find($id);
        $user->delete();
        return response()->json(["status"=>true, "data"=>$id]);

    }
}
