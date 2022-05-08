<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuentas;
use App\Models\CuentasAsociadas;

class CuentasController extends Controller
{
    //
    public function index(Request $request){
        
        //return $request['usuario'];
        $RESULT = Cuentas::where([['cliente_id',$request['cliente']]])->get();
        if($RESULT){
           
            return array('status'=>true, 'datos'=>$RESULT);
        }else{
            return array('status'=>false,'msj'=>'No se encontro ninguna cuenta.');
        }

    }

    public function RelacionarCuenta(Request $request){

          $validar = CuentasAsociadas::where('cliente_id',$request['cuenta'])->where('cuenta_tercero',$request['tercero'])->where('banco_id',$request['banco'])->get();
          if($validar->count() == 1){
              return array('msj'=>'Ya esta cuenta se encuentra asociada', 'status'=>false);
          }
          $cuenta = new CuentasAsociadas();
          $cuenta->cliente_id = $request['cuenta'];
          $cuenta->alias = $request['alias'];
          $cuenta->cuenta_tercero = $request['tercero'];
          $cuenta->banco_id = $request['banco'];
          if($cuenta->save()){
            return array('status'=>true,'msj'=>'Se registro con exito');
          }else{
            return array('status'=>false,'msj'=>'Error al guardar el numero de cuenta');
          }
          
    }

    public function cuentasterceros(Request $request){
        

        $RESULT = CuentasAsociadas::where([['cliente_id',$request['cliente']]])->get();
        if($RESULT){
           
            return array('status'=>true, 'datos'=>$RESULT);
        }else{
            return array('status'=>false,'msj'=>'No se encontraron cuentas asociadas.');
        }

    }


}
