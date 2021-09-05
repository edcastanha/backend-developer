<?php

namespace App\Http\Controllers;

class TestController
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

}
