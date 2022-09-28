<?php 

namespace App\Helps;

use Illuminate\Support\Facades\Http;
use App\Models\Clima;
use App\Models\Cidade;

class PrevisaoHelps {

    private $model_clima;
    
    const BASE_URL  = 'https://api.openweathermap.org/data/2.5/forecast?q=';
    const PARAMS    = '&mode=json&units=metric&lang=pt_br&appid=';
    const apiKey    = '6e8b6b4b7327168589ef662585925448';

    public function __construct()
    {
        $this->model_clima = new Clima();
    }

    public function save($climas)
    {
        $results = [];
        foreach($climas as $clima){

            $cidade = match($clima['cidade']){
                'Berlin'   => 1,
                'London'   => 2,
                'Paris'    => 3,
                'Tokyo'    => 4,
                'New York' => 5,
            };
            
            $results[]= [
                'cidade_id'           => $cidade,
                'temperatura'      => $clima['temperatura'],
                'sensacao_termica' => $clima['sensacao_termica'],
                'data'             => $clima['data'],
                'descricao'        => $clima['descricao'] 
            ];
        }
        $this->model_clima::insert($results);
    }

    public function findAll()
    {
        $results = $this->model_clima->all();
        return $this->formatDataReturn($results);
    }

    public function findApi($cidade)
    {
        return $this->findPrevisaoTempo($cidade);
    }

    public function findDataAll($data){
        return $this->formatDataReturn($this->findData($data));
    }

    private function formatDataReturn($climas)
    {
        $results = [];

        if(count($climas) > 0){
            
            foreach($climas as $clima){

                $cidade = Cidade::find($clima->cidade_id);

                $results[]= [
                    'cidade'           => $cidade->cidade,
                    'temperatura'      => $clima->temperatura,
                    'sensacao_termica' => $clima->sensacao_termica,
                    'data'             => $clima->data,
                    'descricao'        => $clima->descricao 
                ];
            }
        }
        return $results;
    }

    private function findData($data)
    {
        $climas= $this->model_clima::where('data', $data)->orderBy('id','desc')->get();
        return $climas;
    }

    private function findPrevisaoTempo($cidade)
    {
        $results = Http::timeout(20)->get(
            self::BASE_URL.$cidade.self::PARAMS.self::apiKey
        );
            
        if($results->clientError() || $results->serverError())
            return [];

        return $results;
    }

    public function validateData($data)
    {
        $dataAtual = date('d-m-Y');

        $date1=date_create($dataAtual);
        $date2=date_create($data);
        $diff=date_diff($date1,$date2);
        
        $valor = intval($diff->format("%R%a"));

        if($valor < 0 || $valor > 5){
            return false;
        }

        return true;
    }

    public function findDataPrevisao($climas, $data)
    {
        $cidade = $climas['city']['name'];
        $lists = $climas['list'];

        $result = [];
        foreach($lists as $list){

            //pegando só a parte da data
            $d = date('d-m-Y', strtotime($list['dt_txt']));

            if($d == $data){
               
                return [
                    'cidade'           => $cidade,
                    'temperatura'      => $list['main']['temp'],
                    'sensacao_termica' => $list['main']['feels_like'],
                    'data'             => $data,
                    'descricao'        => $list['weather'][0]['description']
                ]; 
            }
        }
        return $result;
    }
}