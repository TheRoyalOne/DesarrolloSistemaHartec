<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use App\User;
use App\Profile;
use App\WorkshopType;
// use Illuminate\Queue\Worker;


class WorkshopsTypesController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile,15);
        $perm_btn =Permission::permBtns($profile,15);
        if($perm==0) {
            return redirect()->route('home');
        } else {

            return view('admin.workshopstypes', compact('perm_btn'));
        }
    }

    public function show($id)
    {
        $data = WorkshopType::get();
        // dd($data);
        return response()->json($data);
    }

    public function Getinfo($id){
        $id = WorkshopType::where('id',$id)->first();
        // dd($id);
        return response()->json(["status"=>true, "data"=>$id]);
    }

    public function store(Request $request)
    {
        $workshop = new WorkshopType;
        $workshop->name = $request->name;
        $workshop->description = $request->description;
        $workshop->save();
        return response()->json(["status"=>true, "message"=>"Creado Exitosamente"]);
    }

    public function actualizar(Request $request)
    {
        $workshop = WorkshopType::where('id', $request->id)->first();
        $workshop->name = $request->name;
        $workshop->description = $request->description;
        $workshop->save();
        return response()->json(["status"=>true, "message"=>"Actualizado Exitosamente"]);

    }

    public function destroy($id)
    {
        $workshop = WorkshopType::find($id);
        $workshop->delete();
        return response()->json(["status"=>true, "data"=>$id]);

    }
}
