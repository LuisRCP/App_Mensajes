<?php

namespace App\Libraries;

class OpenAIService
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = getenv('OPENAI_API_KEY');
    }

    public function preguntar($mensaje)
    {
        $data = [
            "model" => "gpt-4.1-mini",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "Eres un asistente dentro de un chat tipo messenger. 
                    Responde breve, claro y natural.
                    Usa máximo 6 o 7  oraciones.
                    Evita textos largos o explicaciones académicas."
                ],
                [
                    "role" => "user",
                    "content" => $mensaje
                ]
            ]
            
        ];

        $ch = curl_init("https://api.openai.com/v1/chat/completions");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->apiKey
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        curl_close($ch);

        $result = json_decode($response, true);

        return $result["choices"][0]["message"]["content"] ?? "No pude responder.";
    }
}