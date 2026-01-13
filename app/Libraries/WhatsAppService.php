<?php

namespace App\Libraries;

class WhatsAppService
{
    private $token;
    private $phoneNumberId;
    private $businessAccountId;
    private $version = 'v20.0';

    public function __construct()
    {
        $settingModel = new \App\Models\SettingModel();
        // Retrieve settings directly or cache them. Ideally accessing via a helper if available, but model is fine.
        // Assuming settings are stored as key => value in a way we can access.
        // Based on SettingModel, it seems to store rows. 
        // Let's check how settings are usually accessed. Helper function 'getSetting'?
        // If not, I'll fetch them manually.
        
        $settings = $settingModel->select('key, values')->whereIn('key', ['whatsapp_token', 'whatsapp_phone_number_id', 'whatsapp_business_account_id'])->findAll();
        $config = [];
        foreach($settings as $s) {
            $config[$s->key] = $s->values;
        }

        $backendConfig = config('Backend');
        $defaultSettings = [];
        if (isset($backendConfig->settings)) {
            foreach ($backendConfig->settings as $setting) {
                if (in_array($setting['key'], ['whatsapp_token', 'whatsapp_phone_number_id', 'whatsapp_business_account_id'])) {
                     $defaultSettings[$setting['key']] = $setting['values'] ?? '';
                }
            }
        }

        $this->token = !empty($config['whatsapp_token']) ? $config['whatsapp_token'] : ($defaultSettings['whatsapp_token'] ?? '');
        $this->phoneNumberId = !empty($config['whatsapp_phone_number_id']) ? $config['whatsapp_phone_number_id'] : ($defaultSettings['whatsapp_phone_number_id'] ?? '');
        $this->businessAccountId = !empty($config['whatsapp_business_account_id']) ? $config['whatsapp_business_account_id'] : ($defaultSettings['whatsapp_business_account_id'] ?? '');
    }

    public function sendMessage(string $to, string $messageBody)
    {
        if (empty($this->token)) {
             return [
                'status' => false,
                'message' => 'WhatsApp Config Error: Token is missing.'
            ];
        }
        if (empty($this->phoneNumberId)) {
             return [
                'status' => false,
                'message' => 'WhatsApp Config Error: Phone Number ID is missing.'
            ];
        }

        $url = "https://graph.facebook.com/{$this->version}/{$this->phoneNumberId}/messages";

        $data = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'body' => $messageBody
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Log request for debugging (optional)
        // log_message('info', 'WhatsApp request to ' . $to);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300) {
            return [
                'status' => true,
                'data' => $result
            ];
        } else {
            return [
                'status' => false,
                'message' => $result['error']['message'] ?? 'Unknown error',
                'raw' => $result
            ];
        }
    }
}
