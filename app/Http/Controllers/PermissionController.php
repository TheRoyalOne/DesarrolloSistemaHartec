<?php

namespace App\Http\Controllers;

use App\User;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Permission;
use App\Section;
use App\Profile;

class PermissionController extends Controller
{
    public function index(){
        // $profile = User::findProfile();
        $prof = Profile::pluck('name','id');
        $profile = User::findProfile();
        $perm = Permission::permView($profile,9);
        $perm_btn =Permission::permBtns($profile,9);
        $padre =Section::whereRaw('id = reference')->orderBy('order','ASC')->get();
        // dd($padre);
        $hijos = Section::whereRaw('id!=reference')->orderBy('order','ASC')->get();
        // dd($hijos);
        if($perm==0) {
            return redirect()->route('home');
        } else {

            return view('admin.permission', compact('perm_btn','profile','padre','hijos','prof'));
        }
        
    }
    public function edit($id){
        $per = Permission::where('profile_id','=',$id)->get();
        return response()->json($per);
    }
    public function update_store(Request $request, $profile_id, $id_seccion,$btn,$reference){
        // dd($id_seccion);
        $perm = Permission::where('profile_id','=',$profile_id)->where('section_id','=',$id_seccion)->count();
        // dd($perm);
        if($perm>0){
            // dd($profile_id, $id_seccion,$btn,$reference);
            Permission::updatePermission($profile_id, $id_seccion, $btn, $reference);
        }else{//no existe-crear permiso
            switch ($btn) {
                case 0://VER
                    $per_ = new Permission();
                    $per_ ->profile_id=$profile_id;
                    $per_ ->section_id=$id_seccion;
                    $per_ ->view=1;
                    $per_->save();
                    $permisions =   DB::table('permissions')
                        ->join('sections','sections.id','=','permissions.section_id')
                        ->where(['sections.reference'=>$reference,'view'=>1,'profile_id'=>$profile_id])
                        ->count();
                    if($permisions>0){
                        $findPermisions = Permission::where('section_id','=',$reference)->where('profile_id','=',$profile_id)->get();
                        if($findPermisions->count() == 0){
                            $createPermision = new Permission();
                            $createPermision->profile_id=$profile_id;
                            $createPermision->section_id=$reference;
                            $createPermision->view=1;
                            $createPermision->save();
                        }
                    }
                    break;

                case 1://AGREGAR
                    // dd("entre");
                    $per_ = new Permission();
                    $per_ ->profile_id=$profile_id;
                    $per_ ->section_id=$id_seccion;
                    $per_ ->view=1;
                    $per_ ->add=1;
                    $per_->save();
                    $perms = Permission::where('profile_id','=',$profile_id)->where('section_id','=',$id_seccion)->pluck('section_id');
                    // dd($perms);
                    $permisions =   DB::table('permissions')
                        ->join('sections','sections.id','=','permissions.section_id')
                        ->where(['sections.reference'=>$reference,'view'=>1,'profile_id'=>$profile_id])
                        ->count();
                    if($permisions>0){
                        $findPermisions = Permission::where('section_id','=',$reference)->where('profile_id','=',$profile_id)->get();
                        if($findPermisions->count() == 0){
                            $createPermision = new Permission();
                            $createPermision->profile_id=$profile_id;
                            $createPermision->section_id=$reference;
                            $createPermision->view=1;
                            $createPermision->save();
                        }
                    }
                    return response()->json($perms);
                    break;
                case 2://EDITAR
                    // dd($btn);
                    $per_ = new Permission();
                    $per_ ->profile_id=$profile_id;
                    $per_ ->section_id=$id_seccion;
                    $per_ ->view=1;
                    $per_ ->update=1;
                    $per_->save();
                    $perms = Permission::where('profile_id','=',$profile_id)->where('section_id','=',$id_seccion)->pluck('section_id');

                    $permisions =   DB::table('permissions')
                        ->join('sections','sections.id','=','permissions.section_id')
                        ->where(['sections.reference'=>$reference,'view'=>1,'profile_id'=>$profile_id])
                        ->count();
                    if($permisions>0){
                        $findPermisions = Permission::where('section_id','=',$reference)->where('profile_id','=',$profile_id)->get();
                        if($findPermisions->count() == 0){
                            $createPermision = new Permission();
                            $createPermision->profile_id=$profile_id;
                            $createPermision->section_id=$reference;
                            $createPermision->view=1;
                            $createPermision->save();
                        }
                    }

                    return response()->json($perms);
                    break;
                case 3://ELIMINAR
                    $per_ = new Permission();
                    $per_ ->profile_id=$profile_id;
                    $per_ ->section_id=$id_seccion;
                    $per_ ->view=1;
                    $per_ ->delete=1;
                    $per_->save();
                    $perms = Permission::where('profile_id','=',$profile_id)->where('section_id','=',$id_seccion)->pluck('section_id');

                    $permisions =   DB::table('permissions')
                        ->join('sections','sections.id','=','permissions.section_id')
                        ->where(['sections.reference'=>$reference,'view'=>1,'profile_id'=>$profile_id])
                        ->count();
                    if($permisions>0){
                        $findPermisions = Permission::where('section_id','=',$reference)->where('profile_id','=',$profile_id)->get();
                        if($findPermisions->count() == 0){
                            $createPermision = new Permission();
                            $createPermision->profile_id=$profile_id;
                            $createPermision->section_id=$reference;
                            $createPermision->view=1;
                            $createPermision->save();
                        }
                    }
                    return response()->json($perms);
                    break;
            }
        }

    }
}