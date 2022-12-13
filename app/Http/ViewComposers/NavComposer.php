<?php
namespace App\Http\ViewComposers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Route as Route;
use App\Section as Section;
use App\User as User;

class NavComposer
{

    /**
     * Bind data to the view
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        if (Auth::check()) {
           $profile = User::findProfile();
         //   $super = User::findUser()->super;
            // 22-11-2019
            // nueva lógica para cargar el menú
            $sections =  DB::table('permissions')
            ->select(
                'prin_sections.id',
                'prin_sections.section AS section',
                'prin_sections.icon',
                'prin_sections.description'
            )            
            ->join('sections as prin_sections',function($join){
                $join->on('prin_sections.id','=','permissions.section_id')
                ->where('prin_sections.type','=','SECTION');
            })
            ->where('permissions.profile_id','=',$profile)
            ->where('permissions.view','=',1)
            ->orderBy('prin_sections.order','asc')
            ->groupBy('prin_sections.id')
            ->get();
            $sections2 = DB::table('permissions')
                ->select(
                    'prin_sections.id',
                    'prin_sections.section AS section',
                    'prin_sections.icon',
                    'prin_sections.description'
                )
                ->join('sections as modules',function($join){
                    $join->on('modules.id','=','permissions.section_id')
                    ->where('modules.type','=','MODULE');
                })
                ->join('sections as sub_sections',function($join){
                    $join->on('sub_sections.id','=','modules.padre')
                    ->where('sub_sections.type','=','SUBSECTION');
                })
                ->join('sections as prin_sections',function($join){
                    $join->on('prin_sections.id','=','sub_sections.padre')
                    ->where('prin_sections.type','=','SECTION');
                })
                ->where('permissions.profile_id','=',$profile)
                ->where('permissions.view','=',1)
                ->orderBy('prin_sections.order','asc')
                ->groupBy('prin_sections.id')
                ->get();
            $subsections = DB::table('permissions')
            ->select(
                'prin_sections.order',
                'prin_sections.id AS padre_id',
                'prin_sections.section AS section',
                'sub_sections.order as sub_order',
                'sub_sections.id as sub_section_id',
                'sub_sections.section AS subsection',
                'modules.id AS module_id',
                'modules.section AS module',
                'modules.url',
                'permissions.profile_id'
            )
            ->join('sections as modules',function($join){
                $join->on('modules.id','=','permissions.section_id')
                ->where('modules.type','=','MODULE');
            })
            ->join('sections as sub_sections',function($join){
                $join->on('sub_sections.id','=','modules.padre')
                ->where('sub_sections.type','=','SUBSECTION');
            })
            ->join('sections as prin_sections',function($join){
                $join->on('prin_sections.id','=','sub_sections.padre')
                ->where('prin_sections.type','=','SECTION');
            })
            ->where('permissions.profile_id','=',$profile)
            ->where('permissions.view','=',1)
            ->groupBy('prin_sections.id','sub_sections.id')
            ->orderBy('prin_sections.order','asc')
            ->orderBy('sub_sections.order','asc')
            ->get();
                // dd($sections);
            $user_permissions = DB::table('permissions')
                ->select(
                    'modules.padre AS padre_id',
                    'modules.reference AS reference',
                    'modules.id AS module_id',
                    'modules.section AS module',
                    'modules.url',
                    'permissions.profile_id'
                )
                ->join('sections as modules',function($join){
                    $join->on('modules.id','=','permissions.section_id')
                    ->where('modules.type','=','MODULE');
                })               
                ->where('permissions.profile_id','=',$profile)
                ->where('permissions.view','=',1)
                ->orderBy('modules.order','asc')
                ->get();
                    // dd($sections,$user_permissions);
                $view->with('secciones', $sections)
                ->with('subsections',$subsections)
                ->with('user_permissions',$user_permissions);

      
        }

    }

}
