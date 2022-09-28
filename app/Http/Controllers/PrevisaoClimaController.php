<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Helps\PrevisaoHelps;


class PrevisaoClimaController extends Controller
{
    private $helpsPrevisaoClina;

    public function __construct(PrevisaoHelps $helpsPrevisaoClina)
    {
        $this->helpsPrevisaoClina = $helpsPrevisaoClina;
    }

    public function getAll()
    {
        try{
            $climas = $this->helpsPrevisaoClina->findAll();
            return response()->json($climas);
        }catch(\Exception $e) { return response()->json(['mensagem'=>$e->getMessage()]);}
    }

    public function search($data)
    {

        $request['data'] = $data;

        try{

            $validator=Validator::make($request,[
                'data' => 'required|date_format:d-m-Y',
            ]);
            if($validator->fails())
               return response()->json(['mensagem'=> 'erro ao preencher inputs', 'data'=>$validator->errors()], 400);

            $results = $this->helpsPrevisaoClina->findDataAll($data);

            if(count($results) > 0) 
                return response()->json($results);
            
            
            if(!$this->helpsPrevisaoClina->validateData($data))
                return response()->json('erro',400);
            
            $results = [];

            $newYork = $this->helpsPrevisaoClina->findApi('New York');
            $london  = $this->helpsPrevisaoClina->findApi('London');
            $paris   = $this->helpsPrevisaoClina->findApi('Paris');
            $berlin  = $this->helpsPrevisaoClina->findApi('Berlin');
            $tokyo   = $this->helpsPrevisaoClina->findApi('Tokyo');

            $results[] = $this->helpsPrevisaoClina->findDataPrevisao($newYork, $data);
            $results[] = $this->helpsPrevisaoClina->findDataPrevisao($london, $data);
            $results[] = $this->helpsPrevisaoClina->findDataPrevisao($paris, $data);
            $results[] = $this->helpsPrevisaoClina->findDataPrevisao($berlin, $data);
            $results[] = $this->helpsPrevisaoClina->findDataPrevisao($tokyo, $data);
            
            $this->helpsPrevisaoClina->save($results);

            return response()->json($results);

        }catch(\Exception $e) { return response()->json(['mensagem'=>$e->getMessage()]);}
    }
}
