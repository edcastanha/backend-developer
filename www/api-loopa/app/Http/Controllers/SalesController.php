<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SalesController extends Controller
{
    public function index(){
        return [
            'status' => 'sucess',
            'message' => 'Bem Vindo API Loopa',
            'data' => [
                'Version' => '1.0.0',
                'Author' => 'Edson Lourenço B. Filho',
                'Contato'=>[
                    'email' => 'edcastanha@gmail.com',
                    'Github' => 'edcastanha',
                    'Twitter' => '@EdLourenzo',
                    'Linkedin' => 'edlourenzo',
                    'Whatsapp' => '+5511950496341',
                    'Location' => 'Jarinu, SP' 
                ]
            ]
        ];
    }

    public function decodedFile(Request $request){
            $sales = [];
            if(!Validator::make($request->all(), [
                    'file'  => 'required|file|mimetypes:text/plain'
                ])) {
                    return response()->json(['error' => 'File is not valid'], 400);
            }

        
        foreach (file($request->file) as $row) {
            $row = trim($row);
            if (!empty($row)) {
                $cep = substr($row, 43, 8);
                if(!strlen($cep) == 8 ){
                    return response()->json(['error' => 'File is not valid'], 400);
                }else{
                    
                    $id        = substr($row, 0, 3);
                    $date         = date("Y-m-d", strtotime(substr($row, 3, 8)));
                    $amount       = number_format(substr($row, 11, 10)/100,2,".","");
                    $customer     =[
                        "name"      => trim(substr($row, 23, 20)),
                        "address"   => $this->getAddress($cep)
                    ];
                    
                    $installments  = $this->getInstallments(substr($row, 21, 2), $amount, $date);
                
                }//end if
            }//end if

            $sales= ['sales'=>[
                            "id"=> $id,
                            "date"=> $date, 
                            "amount"=>$amount, 
                            "customer"=>$customer, 
                            "installments"=>$installments]
                        ]; 
            
        }//end foreach
            
        return response()->json($sales,200);
    
    }//end of DecodedFile
    
    public function getInstallments($installment,$amount_original,$date)
    {   
        $data = [];
        $amount_total = 0;
        for ($i=1; $i <= $installment; $i++)
        {

            $amount = number_format($amount_original / $installment,2,".","");
            $date = $this->nextBusiness(date('Y-m-d', strtotime('+30 days', strtotime($date))));

            $data[] = [
                "installment"   => $i,
                "amount"        => $amount,
                "date"          => $date
            ];

            $amount_total += $amount;
        }

        $amount_total = number_format($amount_total,2,".","");
        $diff = $amount_original - $amount_total;
        $data[0]['amount'] = number_format($data[0]['amount'] + $diff,2,".","");
        

        return $data;
    }//end getInstallments

    public function nextBusiness($date)
    {
        $dayweek = date('w', strtotime($date));
        if ($dayweek == "0"){
            $add_day = 1;
        }else if ($dayweek == "6"){
            $add_day = 2;
        }else{
            $add_day = 0;
        }
        return date("Y-m-d", strtotime("+{$add_day} days", strtotime($date)));
    }//end nextBusiness

    public function getAddress($postal_code)
    {
         //API externas ,
        try {
            $response = $this->request('GET', "https://viacep.com.br/ws/{$postal_code}/json");
            $content = json_decode($response->getBody(),true);
            return [
                "street"        => $content['logradouro'],
                "neighborhood"  => $content['bairro'],
                "city"          => $content['localidade'],
                "state"         => $content['uf'],
                "postal_code"   => $content['cep']
            ]; 
            
        } catch (\Throwable $th) {
            $code = 400;
            return response()->json(['error' => 'Error on get address'], $code);
        }
        // finally{
        //     if($code <> 200){
        //         try {
        //             $response = $this->request('GET', "https://brasilapi.com.br/api/cep/v2/{$postal_code}");
        //             $content = json_decode($response->getBody(),true);
        //             return [
        //                "street"        => $content['street'],
        //                "neighborhood"  => $content['neighborhood'],
        //                "city"          => $content['city'],
        //                "state"         => $content['state'],
        //                "postal_code"   => $content['cep']
        //             ];
        //         } catch (\Throwable $e) {
                        
        //             return response()->json([
        //                 'error' => 'Invalid External Service Portal Code']
        //                 , 400);
        //         }
        //     }//end if
        // }//finally

    }//end getAddress


}//end class
