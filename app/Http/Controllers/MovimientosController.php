<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movimientos;
use App\Models\Cuentas;
use App\Models\Bancos;
use App\Models\Generarqr;

class MovimientosController extends Controller
{
    //
    public function index(Request $request){
        
        //return $request['usuario'];
        $RESULT = Movimientos::where([['cuenta_numero',$request['cuenta']]])->orderByDesc('fecha_reg')->get();
        if($RESULT){
           
            return array('status'=>true, 'datos'=>$RESULT);
        }else{
            return array('status'=>false,'msj'=>'No se encontro ningun movimiento.');
        }

    }

    public function bancos(){
        
        $RESULT = Bancos::all();
        return array('status'=>true, 'datos'=>$RESULT);
      

    }
    public function generarqr(Request $request){

        
        $cuentas = Cuentas::where([['cliente_id',$request['id']]])->get();

        $link = Generarqr::where('cliente_id',$request['id'])->get()->first();
        if($link){
            $link->delete();
        }
       

        $RESULT = new Generarqr();
        $RESULT->cliente_id = $request['id'];
        $RESULT->valor_referencia = $request['pre'];
        $RESULT->referencia = $cuentas[0]->numero;
        $RESULT->estado = 0;
        $RESULT->save();
       
            return array('status'=>true, 'datos'=>($RESULT->id));
        

    }

    public function pagarconqr(Request $request){

        //se consulta el numero de la cuenta y el valor a pagar
        $referencia = str_replace("Referencia:","",$request['ref']);
        $destino = Generarqr::find($referencia);

        $cuenta_origen = Cuentas::where([['cliente_id',$request['id']]])->get()->first();

        if($destino){
            //se valida el numero de cuenta
            $cuenta = Cuentas::where([['numero',$cuenta_origen->numero]])->get()->first();
            $saldo = $cuenta->saldo;
            $valor = $destino->valor_referencia;
            if($valor > $saldo){
                return array('status'=>false,'msj'=>'Saldo Insuficiete de la cuenta '.$cuenta_origen->numero);
            }
            //se paga a la cuenta destino
            $movimientos = new Movimientos();
            $movimientos->cuenta_numero = $cuenta_origen->numero;
            $movimientos->fecha_reg = date("Y-m-d H:i:s");
            $movimientos->cuenta_destino = $destino->referencia;
            $movimientos->valor_mov=$valor;
            $movimientos->saldo_actual = $saldo;
            $movimientos->banco_id = $cuenta->banco_id;
            $movimientos->tipo_mov = 'Out';
            if($movimientos->save()){

               // se descuenta de la cuenta que paga
            $cuenta->saldo = $cuenta->saldo - $valor;
            $cuenta->ultimo_reg =  date("Y-m-d H:i:s");
            $cuenta->save();

                // se hace ingreso a la cuenta destino
                $validar_destino = Cuentas::where('numero',$destino->referencia)->get();
            if($validar_destino->count() > 0){


                $movimientos_destino = new Movimientos();
                $movimientos_destino->cuenta_numero = $destino->referencia;
                $movimientos_destino->fecha_reg = date("Y-m-d H:i:s");
                $movimientos_destino->cuenta_destino = $cuenta_origen->numero;
                $movimientos_destino->valor_mov=$valor;
                $movimientos_destino->saldo_actual = $validar_destino[0]->saldo;
                $movimientos_destino->banco_id = $validar_destino[0]->banco_id;
                $movimientos_destino->tipo_mov = 'In';
             
                if($movimientos_destino->save()){
                    $cuenta_des = Cuentas::find($validar_destino[0]->id);
                    $cuenta_des->saldo = $cuenta_des->saldo + $valor;
                    $cuenta_des->ultimo_reg =  date("Y-m-d H:i:s");
                    $cuenta_des->save();

                }
            }
            return array('status'=>true,'msj'=>'Transacion exitosa No. '.$movimientos->id);
            }




        }else{
            return array('status'=>false, 'msj'=>'No se pudo generar el cobro');
        }


    }

    public function movimiento(Request $request){

        $RESULT = Cuentas::where([['cliente_id',$request['cliente']]])->get();
        if($RESULT->count() > 0){
            $saldo = $RESULT[0]->saldo;
            $tipo = $request['tipo'];
            $valor = $request['valor'];

                if($valor > $saldo){
                    return array('status'=>false,'msj'=>'Saldo Insuficiete');
                }

            $movimientos = new Movimientos();
            $movimientos->cuenta_numero = $RESULT[0]->numero;
            $movimientos->fecha_reg = date("Y-m-d H:i:s");
            $movimientos->cuenta_destino = $request['destino'];
            $movimientos->valor_mov=$valor;
            $movimientos->saldo_actual = $saldo;
            $movimientos->banco_id = $RESULT[0]->banco_id;
            $movimientos->tipo_mov = $tipo;
            $movimientos->save();

            $validar_destino = Cuentas::where('numero',$RESULT[0]->numero)->get();
            if($validar_destino->count() > 0){


                $movimientos_destino = new Movimientos();
                $movimientos_destino->cuenta_numero = $request['destino'];
                $movimientos_destino->fecha_reg = date("Y-m-d H:i:s");
                $movimientos_destino->cuenta_destino = $RESULT[0]->numero;
                $movimientos_destino->valor_mov=$valor;
                $movimientos_destino->saldo_actual = $validar_destino[0]->saldo;
                $movimientos_destino->banco_id = $validar_destino[0]->banco_id;
                $movimientos_destino->tipo_mov = 'In';
                $movimientos_destino->save();
                if($movimientos_destino->save()){
                    $cuenta_des = Cuentas::find($validar_destino[0]->id);
                    $cuenta_des->saldo = $cuenta_des->saldo + $valor;
                    $cuenta_des->ultimo_reg =  date("Y-m-d H:i:s");
                    $cuenta_des->save();

                }
            }
            



            $cuenta = Cuentas::find($RESULT[0]->id);
            $cuenta->saldo = $cuenta->saldo - $valor;
            $cuenta->ultimo_reg =  date("Y-m-d H:i:s");
            $cuenta->save();

            return array('status'=>true,'msj'=>'Transacion exitosa No. '.$movimientos->id);


        }else{
            return array('status'=>false,'msj'=>'No se encontro ninguna cuenta.');
        }

    }
}
