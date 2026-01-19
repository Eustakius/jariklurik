<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class ForensicsController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('security_fingerprints');
        
        $data = $builder->orderBy('created_at', 'DESC')
                        ->limit(100)
                        ->get()
                        ->getResultArray();
                        
        // Decode JSON fields for frontend
        foreach ($data as &$row) {
            $row['local_ips'] = json_decode($row['local_ips']) ?? [];
            $row['raw_data'] = json_decode($row['raw_data']) ?? (object)[];
        }
        
        return $this->respond($data);
    }

    public function collect()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('security_fingerprints');

        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->fail('No data provided');
        }

        // Calculate a consistent device hash using key parameters
        // Note: Real fingerprinting is complex; this is a simplified version using Canvas+WebGL
        $fingerprintSource = ($data['canvas_hash'] ?? '') . 
                             json_encode($data['webgl'] ?? []) . 
                             json_encode($data['screen'] ?? []) .
                             ($data['timezone'] ?? '');
                             
        $deviceHash = hash('sha256', $fingerprintSource);

        $insertData = [
            'canvas_hash' => $data['canvas_hash'] ?? null,
            'device_hash' => $deviceHash,
            'ip_address' => $this->request->getIPAddress(),
            'local_ips' => json_encode($data['local_ips'] ?? []),
            'screen_resolution' => isset($data['screen']) ? ($data['screen']['width'] . 'x' . $data['screen']['height']) : null,
            'timezone' => $data['timezone'] ?? null,
            'raw_data' => json_encode($data),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $builder->insert($insertData);
            
            // Check if this device has been seen with other IPs (VPN Detection)
            $history = $builder->where('device_hash', $deviceHash)
                               ->select('ip_address')
                               ->groupBy('ip_address')
                               ->get()->getResultArray();
            
            $knownIps = array_column($history, 'ip_address');
            $isNewIp = !in_array($this->request->getIPAddress(), $knownIps);
            
            return $this->respond([
                'status' => 'success', 
                'device_id' => $deviceHash,
                'vpn_suspected' => count($knownIps) > 1 && $isNewIp
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Forensics Error: ' . $e->getMessage());
            return $this->failServerError('Failed to save forensics data');
        }
    }
}
