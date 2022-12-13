<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use App\User;
use App\TreeInventory;
use App\Nursery;
use App\Species;
use DB;

class TreesInventoryController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile,21);
        $perm_btn =Permission::permBtns($profile,21);
        $nursery = Nursery::pluck('name','id');
        // dd($nursery);
        $species = Species::pluck('name','id');
        if($perm==0) {
            return redirect()->route('home');
        } else {

            return view('admin.treeinventory', compact('perm_btn','nursery','species'));
        }
    }

    public function show()
    {
        $data = DB::table('tree_inventory')
        ->join('nurseries', 'tree_inventory.id_nurserie','=','nurseries.id')
        ->join('species','tree_inventory.id_species','=','species.id')
        ->select('tree_inventory.id', 'nurseries.name as nursery','species.name as species','tree_inventory.amount','tree_inventory.age')
        ->get();
        return response()->json($data);
    }
    public function Getinfo($id)
    {
        $id = TreeInventory::where('id',$id)->with(array('nurseries','species'))->first();
        // dd($id);
        return response()->json(["status"=>true, "data"=>$id]);
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $entry = new TreeInventory;
        $entry->id_nurserie = $request->id_nurserie;
        $entry->id_species = $request->id_species;
        $entry->amount = $request->amount;
        $entry->age = $request->age;
        $entry->save();
        return response()->json(["status"=>true, "message"=>"Creado Exitosamente"]);
    }
}
