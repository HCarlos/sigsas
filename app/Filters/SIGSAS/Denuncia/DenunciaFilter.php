<?php
/**
 * Created by PhpStorm.
 * User: devch
 * Date: 30/11/18
 * Time: 01:03 PM
 */

namespace App\Filters\SIGSAS\Denuncia;


use App\Classes\GeneralFunctions;
use App\Filters\Common\QueryFilter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class DenunciaFilter extends QueryFilter
{


    public function rules(): array{
        return [
            'search'         => '',
            'curp'           => '',
            'ciudadano'      => '',
            'id'             => '',
            'desde'          => '',
            'hasta'          => '',
            'dependencia_id' => '',
            'servicio_id'    => '',
            'origen_id'      => '',
            'estatus_id'     => '',
            'ciudadano_id'   => '',
            'creadopor_id'   => '',
            'dependencia'    => '',
            'conrespuesta'   => '',
            'cerrado'        => '',
        ];
    }

    public function search($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "") {return $query;}
        $search = strtoupper($search);
        $filters  = $search;
        $F        = new GeneralFunctions();
        $tsString = $F->string_to_tsQuery( strtoupper($filters),' & ');

        return $query->whereRaw("searchtextdenuncia @@ to_tsquery('spanish', ?)", [$tsString])
            ->orderByRaw("calle, num_ext, num_int, colonia, descripcion, referencia ASC");

    }

    public function curp($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "") {return $query;}
        $search = strtoupper($search);
        return $query->orWhereHas('ciudadanos', function ($q) use ($search) {
            return $q->where("curp",strtoupper(trim($search)));
        });
    }

    public function ciudadano($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "") {return $query;}
        $search = strtoupper($search);
        return $query->orWhereHas('ciudadanos', function ($q) use ($search) {
            $filters  = $search;
            $F        = new GeneralFunctions();
            $tsString = $F->string_to_tsQuery( strtoupper($filters),' & ');
            $q->whereRaw("searchtext @@ to_tsquery('spanish', ?)", [$tsString]);
        });
    }

    public function id($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "") {return $query;}
        return $query->where('id', $search);
    }

    public function desde($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "") {return $query;}
        $date = Carbon::createFromFormat('Y-m-d', $search)->toDateString();
        $date = $date.' 00:00:00';
        return $query->whereDate('fecha_ingreso', '>=', $date);
    }

    public function hasta($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "") {return $query;}
        $date = Carbon::createFromFormat('Y-m-d', $search)->toDateString();
        $date = $date.' 23:59:59';
        return $query->whereDate('fecha_ingreso', '<=', $date);
    }

    public function dependencia_id($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "0") {return $query;}

        return $query->whereHas('dependencias', function ($q) use ($query, $search) {
                return $q->where('dependencia_id', intval($search));
        });

    }

    public function servicio_id($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "0") {return $query;}
        return $query->whereHas('denuncia_servicios', function ($q) use ($query, $search) {
            return $q->where('servicio_id', intval($search));
        });
    }

    public function estatus_id($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "0") {return $query;}

        return $query->whereHas('denuncia_estatus', function ($q) use ($query, $search) {
            return $q->where('estatu_id', intval($search));
        });
    }

    public function origen_id($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "0") {return $query;}
        return $query->where('origen_id', $search);
    }

    public function ciudadano_id($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "0") {return $query;}
        return $query->where('ciudadano_id', $search);
    }

    public function creadopor_id($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "0") {return $query;}
        return $query->where('creadopor_id', $search);
    }

    public function dependencia($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "0") {return $query;}
        $search = explode('|',$search);
        return $query->orWhereHas('dependencia', function ($q) use ($search) {
            return $q->whereIn('dependencia',$search);
        });
    }

    public function conrespuesta($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "0") {return $query;}
        $search = explode('|',$search);
        if ($search==true)
            return $query->has('denuncia_estatus','>',1)->withCount('denuncia_estatus');
    }

    public function cerrado($query, $search){
        if (is_null($search) || empty ($search) || trim($search) == "0") {return $query;}
        return $query->orWhere('cerrado',settype($search, 'boolean'));
    }

    function IsEnlace(){
        return Session::get('IsEnlace');
    }

    function getDependencia(){
            return $DependenciaArray = explode('|',Session::get('DependenciaArray'));
    }

    function getDependenciaId(){
        return $DependenciaIdArray = explode('|',Session::get('DependenciaIdArray'));
    }


}
