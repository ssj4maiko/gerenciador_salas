<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Usuarios;
use App\Salas;
use App\Reservas;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $salas = Salas::get();
        return view('home',array(
                             'title'    => 'Dashboard'
                            ,'inner'    => 'reserva'
                            ,'salas'    => $salas
                            ,'request'  => $request
                    ));
    }

    public function getData(Request $request, $id_reserva = null){
        $reservas = DB::table('reservas as r')
                      ->join('usuarios as u','r.id_usuario', '=', 'u.id_usuario')
                      //->join('salas as s','r.id_sala', '=', 's.id_sala')

                      ->select(DB::raw('r.id_usuario, r.id_sala, r.id_reserva, TIME_FORMAT(dt_start,"%H:%i") as hr_start, TIME_FORMAT(dt_end,"%H:%i") as hr_end'))
                      ->addSelect('u.realname as usuario')
                      //->addSelect('s.descricao as sala')
                      ;

        if($id_reserva == null){
            $reservas = $reservas->whereRaw('DATE(dt_start) = "'.$request->date.'"')
                                 ->get();

            return json_encode($reservas);
        }
        else{
            $reservas = $reservas->where('id_reserva',$id_reserva)
                                 ->get();
            return $reservas;
        }
    }

    private function _validation($request){
        $alert = array(
             'type'  =>  'success'
            ,'message' => array()
        );
        if(!preg_match("/((2[0-2]|[01][0-9]):([0-5][0-9]))|23:00/", $request->hr_start)){
            $alert['type'] = 'error';
            $alert['message'][] = 'Horário início inválido. Por favor, escreva no formato (H)H:MM. Mínimo 0:00 / Máximo 23:00';
        }

        if($alert['type'] == 'success'){

            sscanf($request->hr_start, "%d:%d", $hours, $minutes);
            sscanf($request->dt_reserva, "%d-%d-%d", $year, $month, $day);
            $sec_start = $hours * 3600 + $minutes * 60;
            $sec_end = $sec_start + 3600;

            $date_end = mktime($hours+1,$minutes,0, $month, $day, $year);
            $request->hr_end = date('H:i', $date_end);

            $dt_start = $request->dt_reserva.' '.$request->hr_start.':00';
            $dt_end = date('Y-m-d H:i:s', $date_end);

            // Aproveitando a conversão, útil quando a data é para o dia seguinte.
            $request->dt_end = $dt_end;

            //DB::enableQueryLog();
            $reservas = DB::table('reservas as r')
                          ->join('usuarios as u','r.id_usuario', '=', 'u.id_usuario')
                          //->join('salas as s','r.id_sala', '=', 's.id_sala')
                          ->whereRaw('(r.id_usuario = '.$request->id_usuario.' OR r.id_sala = '.$request->id_sala.')')

                          ->whereRaw('((r.dt_start > "'.$dt_end.'" '
                                 .'AND r.dt_end < "'.$dt_start.'")'
                                 .' OR '
                                 .'(r.dt_start = "'.$dt_start.'" '
                                 .'AND r.dt_end = "'.$dt_end.'")'
                            .')')

                          ->select(DB::raw('r.id_usuario, r.id_sala, r.id_reserva, TIME_FORMAT(dt_start,"%H:%i") as hr_start, TIME_FORMAT(dt_end,"%H:%i") as hr_end'))
                          ->addSelect('u.realname as usuario');

            if($request->id_reserva != '')
                $reservas->where('r.id_reserva','<>',$request->id_reserva);

            $reservas = $reservas->get();
            if(count($reservas) > 0){
                $alert['type'] = 'error';
                if($reservas[0]->id_usuario == $request->id_usuario){
                    $alert['message'][] = 'O usuário já possui reserva neste dia entre as '.$reservas[0]->hr_start.' e as '.$reservas[0]->hr_end;
                } else {
                    $alert['message'][] = 'O sala já possui reserva neste dia entre as '.$reservas[0]->hr_start.' e as '.$reservas[0]->hr_end.' para o usuário '.$reservas[0]->usuario;
                }
            }
            //var_dump(DB::getQueryLog());

        }


        if($alert['type'] == 'success'){
            $alert['message'] = 'OK';
        } else {
            $alert['message'] = implode("\n", $alert['message']);
        }
        
        return $alert;
    }
    public function save($id_reserva = null, Request $request){
        
        $alert = $this->_validation($request);
        if($alert['type'] != 'error'){
            if(!$id_reserva){
                $reserva = new Reservas;
                $reserva->id_usuario = $request->id_usuario;
            }
            else{
                $reserva = Reservas::find($id_reserva);
            }
            $reserva->id_sala = $request->id_sala;

            $dt_start = $request->dt_reserva.' '.$request->hr_start.':00';
            // Aproveita a conversão já feita na validação
            $dt_end   = $request->dt_end;

            $reserva->dt_start = $dt_start;
            $reserva->dt_end = $dt_end;

            $reserva->save();

            $alert = array(
                 'type'     => 'success'
                ,'message'  => $id_reserva ? 'Reserva atualizada com sucesso' : 'Sala reservada com sucesso'
                ,'update'   => $this->getData($request,$reserva->id_reserva)
            );
        }

        return json_encode($alert);
    }

    public function del($id_reserva, Request $request){
        $reserva = Reservas::find($id_reserva);
        $usuario = Usuarios::currentUser();

        if($reserva->id_usuario == $usuario->id_usuario){
            $reserva->delete();
            $alert = array(
                 'type'     => 'success'
                ,'message'  => 'Reserva excluída com sucesso'
                );
        } else {
            $alert = array(
                 'type'     => 'error'
                ,'message'  => 'Não é possível excluir a reserva de outra pessoa'
                );
        }


        return json_encode($alert);
    }
}