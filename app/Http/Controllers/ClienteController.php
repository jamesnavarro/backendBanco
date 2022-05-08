<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    //
    public function index(Request $request){
        
        //return $request['usuario'];
        $RESULT = Cliente::where([['cedula',$request['usuario']],['pass',md5($request['pass'])]])->get();
        if($RESULT->count() == 1){
            $validar = Cliente::find($RESULT[0]->id);
            $validar->token = $RESULT[0]->id.date("YmdHis");
            $validar->ultimo_ingreso = date("Y-m-d H:i:s");
            $validar->save();
            return array('status'=>true, 'datos'=>$validar,'msj'=>'Bienvenido '.$RESULT[0]->nombre);
        }else{
            return array('status'=>false,'msj'=>'Clave o Usuario invalido');
        }

    }


}
