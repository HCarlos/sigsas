<?php

namespace App\Http\Controllers\SIGSAS\Dependencia;

use App\Classes\GeneralFunctions;
use App\Classes\RemoveItemSafe;
use App\Http\Requests\SIGSAS\Dependencia\DependenciaRequest;
use App\Models\SIGSAS\Estructura\Dependencia;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class DependenciaController extends Controller
{

    protected $tableName = "dependencias";
    protected $F = null;




// ***************** MUESTRA EL LISTADO DE USUARIOS ++++++++++++++++++++ //
    public function __construct()
    {
        $this->tableName = "dependencias";
        $this->F = new GeneralFunctions();
    }

    protected function index(Request $request)
    {
        ini_set('max_execution_time', 300);
        $filters = $request->all(['search']);
        $items = Dependencia::query()
            ->filterBy($filters)
            ->orderByDesc('id')
            ->paginate();
//        dd($items);
        $items->appends($filters)->fragment('table');
        $user = Auth::User();

//        dd($items);

        return view('SIGSAS.Dependencias.dependencia.dependencia_list',
            [
                'items'           => $items,
                'titulo_catalogo' => "Catálogo de " . ucwords($this->tableName),
                'titulo_header'   => '',
                'user'            => $user,
                'searchInList'    => 'listDependencias',
                'newWindow'       => true,
                'tableName'       => $this->tableName,
                'showEdit'        => 'editDependenciaV2',
                'newItem'         => 'newDependenciaV2',
                'removeItem'      => 'removeDependencia',
                'IsModal'         => true,
                'exportModel'     => 3,
            ]
        );
    }

    // ***************** CREAR NUEVO ++++++++++++++++++++ //
    protected function newItem()
    {
        $Jefes = User::all()->sortBy(function($item) {
            return $item->ap_paterno.' '.$item->ap_materno.' '.$item->nombre;
        });
        return view('SIGSAS.Dependencias.dependencia.dependencia_new',
            [
                'editItemTitle'   => 'Nuevo',
                'jefes'           => $Jefes,
                'postNew'         => 'createDependencia',
                'titulo_catalogo' => "Catálogo de " . ucwords($this->tableName),
                'titulo_header'   => 'Nuevo registro',
            ]
        );
    }

    protected function createItem(DependenciaRequest $request)
    {
//        dd( $request->all() );

        $item = $request->manage();
        if (!isset($item)) {
            abort(404);
        }
        return Redirect::to('listDependencias');
    }

    // ***************** CREAR NUEVO MODAL++++++++++++++++++++ //
    protected function newItemV2(){

        $Jefes = User::query()->whereHas('roles',function($q){
                    return $q->where('name','JEFE');
                })->orderByRaw("concat(ap_paterno,' ',ap_materno,' ',nombre) DESC")
                ->get();
        $user = Auth::user();
        return view('SIGSAS.Dependencias.dependencia.dependencia_modal',
        [
            'Titulo'          => 'Nueva',
            'Route'           => 'createDependenciaV2',
            'Method'          => 'POST',
            'items_forms'     => 'SIGSAS.Dependencias.dependencia.__dependencia.__dependencia_new',
            'IsNew'           => true,
            'user'            => $user,
            'jefes'           => $Jefes,
        ]
        );


    }

    protected function createItemV2(DependenciaRequest $request){
        $Obj = $request->manage();
        if (!is_object($Obj)) {
            $id = 0;
            return redirect('newDependenciaV2')
                ->withErrors($Obj)
                ->withInput();
        }else{
            $id = $Obj->id;
        }
        return Response::json(['mensaje' => 'Dato agregado con éxito', 'data' => 'OK', 'status' => '200'], 200);
    }



// ***************** EDITA LOS DATOS  ++++++++++++++++++++ //
    protected function editItem($Id)
    {
        $item = Dependencia::find($Id);
        $Jefes = User::all()->sortBy(function($item) {
            return $item->ap_paterno.' '.$item->ap_materno.' '.$item->nombre;
        });

        return view('SIGSAS.Dependencias.dependencia.dependencia_edit',
            [
                'user'            => Auth::user(),
                'jefes'           => $Jefes,
                'items'           => $item,
                'editItemTitle'   => $item->dependencia ?? 'Nuevo',
                'putEdit'         => 'updateDependencia',
                'titulo_catalogo' => "Catálogo de " . ucwords($this->tableName),
                'titulo_header'   => 'Editando el Folio '.$Id,
            ]
        );
    }

    protected function updateItem(DependenciaRequest $request)
    {
        $item = $request->manage();
        if (!isset($item)) {
            abort(404);
        }
        return Redirect::to('listDependencias');
    }


// ***************** EDITA LOS DATOS MODAL ++++++++++++++++++++ //
    protected function editItemV2($Id)
    {
        $item = Dependencia::find($Id);
        $Jefes = User::query()->whereHas('roles',function($q){
                    return $q->where('name','JEFE');
                    })->orderByRaw("concat(ap_paterno,' ',ap_materno,' ',nombre) DESC")
                ->get();
        $user = Auth::user();

        return view('SIGSAS.Dependencias.dependencia.dependencia_modal',
            [
                'Titulo'          => isset($item->dependencia) ? $item->dependencia : 'Nueva',
                'Route'           => 'updateDependenciaV2',
                'Method'          => 'POST',
                'items_forms'     => 'SIGSAS.Dependencias.dependencia.__dependencia.__dependencia_edit',
                'IsNew'           => false,
                'IsModal'         => true,
                'items'           => $item,
                'user'            => $user,
                'jefes'           => $Jefes,
            ]
        );

    }

    protected function updateItemV2(DependenciaRequest $request){
        return $this->F->setSaveItem($request);
    }


// ***************** ELIMINA EL ITEM VIA AJAX ++++++++++++++++++++ //
    protected function removeItem($id = 0)
    {
        $item = Dependencia::withTrashed()->findOrFail($id);
        if (isset($item)) {
            return RemoveItemSafe::RemoveItemObject($item,'dependencia_id',$id);

        } else {
            return Response::json(['mensaje' => 'Se ha producido un error.', 'data' => 'Error', 'status' => '200'], 200);
        }
    }

}
