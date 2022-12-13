<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Permission extends Model
{
    protected $table = "permissions";
    protected $fillable=['profile_id','section_id','view','add','update','delete'];

    public function section()
    {
        return $this->belongsTo('App\Section');
    }
    public function profile()
    {
        return $this->hasMany('App\Profile');
    }
    public static function permView($profile_id, $section_id){
        $perm = Permission::where('profile_id','=',$profile_id)->where('section_id','=',$section_id)->where('view','=',1)->count();
        return $perm;
    }

    public static function permBtns($profile_id, $section_id){
      
        $perm_btn = Permission::where('profile_id','=',$profile_id)->where('section_id','=',$section_id)->select('add','update','delete')->get()->first();
        return $perm_btn;
    }
    public static function findProfile(){
        $user = self::findUser();
        $profile_id = $user->profile_id;
        return $profile_id;
    }

    public static function  updatePermission($profile_id,$id_seccion,$btn,$reference){
        $perm = Permission::where('profile_id','=',$profile_id)->where('section_id','=',$id_seccion)->count();
        //existe-editar permiso
        if($perm>0){
              $per = Permission::where('profile_id','=',$profile_id)->where('section_id','=',$id_seccion)->pluck('id');
            //   dd($per);
            switch ($btn) {
                case 0://VER
                    $search_id = Permission::find($per[0]);
                    if($search_id->view==0){
                        $search_id->view=1;
                    }else if($search_id->view==1){
                        $search_id->view=0;
                    }
                    $search_id->save();
                    if($reference!="undefined"){
                        $permisions =   DB::table('permissions')
                            ->join('sections','sections.id','=','permissions.section_id')
                            ->where(['sections.reference'=>$reference,'view'=>1,'profile_id'=>$profile_id])
                            ->count();
                        if($permisions>0){
                            $findPermisions = Permission::where('section_id','=',$id_seccion)->where('profile_id','=',$profile_id)->pluck('id');
                            $update = Permission::find($findPermisions[0]);
                            $update->view=1;
                            $update->save();
                        }
                        else{
                            $findPermisions = Permission::where('section_id','=',$id_seccion)->where('profile_id','=',$profile_id)->pluck('id');
                            $update = Permission::find($findPermisions[0]);
                            $update->view=0;
                            $update->save();
                        }
                    }

                    break;

                case 1://AGREGAR
                    // dd($per);                    
                    $search_id = Permission::find($per[0]);
                    // dd($search_id->add);
                    if($search_id->add==0){
                        $search_id->add=1;
                    }else if($search_id->add==1){
                        $search_id->add=0;
                    }
                    $search_id->save();
                    if($reference!="undefined"){
                        $permisions =   DB::table('permissions')
                            ->join('sections','sections.id','=','permissions.section_id')
                            ->where(['sections.reference'=>$reference,'view'=>1,'profile_id'=>$profile_id])
                            ->count();
                        if($permisions>0){
                            $findPermisions = Permission::where('section_id','=',$id_seccion)->where('profile_id','=',$profile_id)->pluck('id');
                            $update = Permission::find($findPermisions[0]);
                            $update->view=1;
                            $update->save();
                        }
                        else{
                            $findPermisions = Permission::where('section_id','=',$id_seccion)->where('profile_id','=',$profile_id)->pluck('id');
                            $update = Permission::find($findPermisions[0]);
                            $update->view=0;
                            $update->save();
                        }
                    }

                    break;
                case 2://EDITAR
                    $search_id = Permission::find($per[0]);

                    if($search_id->update==0){
                        $search_id->update=1;
                    }else if($search_id->update==1){
                        $search_id->update=0;
                    }
                    $search_id->save();
                    if($reference!="undefined"){
                        $permisions =   DB::table('permissions')
                            ->join('sections','sections.id','=','permissions.section_id')
                            ->where(['sections.reference'=>$reference,'view'=>1,'profile_id'=>$profile_id])
                            ->count();
                        if($permisions>0){
                            $findPermisions = Permission::where('section_id','=',$id_seccion)->where('profile_id','=',$profile_id)->pluck('id');
                            $update = Permission::find($findPermisions[0]);
                            $update->view=1;
                            $update->save();
                        }
                        else{
                            // dd($reference, $profile_id);
                            $findPermisions = Permission::where('section_id','=',$id_seccion)->where('profile_id','=',$profile_id)->pluck('id');
                            // dd($findPermisions);
                            $update = Permission::find($findPermisions[0]);
                            // dd($update);
                            $update->view=0;
                            $update->save();
                        }
                    }

                    break;
                case 3://ELIMINAR
                    $search_id = Permission::find($per[0]);
                    if($search_id->delete==0){
                        $search_id->delete=1;
                    }else if($search_id->delete==1){
                        $search_id->delete=0;
                    }
                    $search_id->save();
                    if($reference!="undefined"){
                        $permisions =   DB::table('permissions')
                            ->join('sections','sections.id','=','permissions.section_id')
                            ->where(['sections.reference'=>$reference,'view'=>1,'profile_id'=>$profile_id])
                            ->count();
                        if($permisions>0){
                            $findPermisions = Permission::where('section_id','=',$id_seccion)->where('profile_id','=',$profile_id)->pluck('id');
                            $update = Permission::find($findPermisions[0]);
                            $update->view=1;
                            $update->save();
                        }
                        else{
                            $findPermisions = Permission::where('section_id','=',$id_seccion)->where('profile_id','=',$profile_id)->pluck('id');
                            $update = Permission::find($findPermisions[0]);
                            $update->view=0;
                            $update->save();
                        }
                    }

                    break;
            }
        }
       
    }
}
