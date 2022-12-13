<?php

namespace App\Http\Controllers;
use App\Permission;
use App\User;
use App\TypeEvent;

use Illuminate\Http\Request;
use Mockery\Matcher\Type;

class TypeEventController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile,13);
        $perm_btn =Permission::permBtns($profile,13);
        if($perm==0) {
            return redirect()->route('home');
        } else {

            return view('admin.typeevent', compact('perm_btn'));
        }
    }
    public function show($id)
    {
        $data = TypeEvent::orderBy('name','asc')->get();
        // dd($data);
        return response()->json($data);
    }

    public function Getinfo($id)
    {
        $id = TypeEvent::where('id',$id)->first();
        return response()->json(["status"=>true, "data"=>$id]);

    }

    public function store(Request $request){
        $type = new TypeEvent;
        $type->name = $request->name;
        $type->description = $request->description;
        $type->save();
        return response()->json(["status"=>true, "message"=>"Creado Exitosamente"]);
    }

    public function actualizar(Request $request)
    {
        $type = TypeEvent::where('id', $request->id)->first();
        $type->name = $request->name;
        $type->description = $request->description;
        $type->save();
        return response()->json(["status"=>true, "message"=>"Actualizado Exitosamente"]);
    }

    public function destroy($id){
        $type= TypeEvent::find($id);
        $type->delete();
        return response()->json(["status"=>true, "data"=>$id]);
    }
}
