<?php

namespace App\Models;

use CodeIgniter\Model;

class ShortUrlModel extends Model
{
    protected $table = 'short_urls';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['short_code', 'full_url', 'clicks', 'created_at'];
    
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    
    /**
     * Check if short_urls table exists
     */
    protected function tableExists()
    {
        return $this->db->tableExists($this->table);
    }
    
    /**
     * Generate a unique short code
     */
    public function generateShortCode($length = 6)
    {
        if (!$this->tableExists()) {
            throw new \Exception('Short URLs table does not exist. Please run migrations.');
        }
        
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        
        do {
            $shortCode = '';
            for ($i = 0; $i < $length; $i++) {
                $shortCode .= $characters[rand(0, $charactersLength - 1)];
            }
            
            // Check if code already exists
            $exists = $this->where('short_code', $shortCode)->first();
        } while ($exists);
        
        return $shortCode;
    }
    
    /**
     * Create a short URL
     */
    public function createShortUrl($fullUrl)
    {
        if (!$this->tableExists()) {
            throw new \Exception('Short URLs table does not exist. Please run migrations.');
        }
        
        // Check if URL already has a short code
        $existing = $this->where('full_url', $fullUrl)->first();
        if ($existing) {
            return $existing['short_code'];
        }
        
        $shortCode = $this->generateShortCode();
        
        $this->insert([
            'short_code' => $shortCode,
            'full_url' => $fullUrl,
            'clicks' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $shortCode;
    }
    
    /**
     * Get full URL from short code and increment click counter
     */
    public function getFullUrl($shortCode)
    {
        $record = $this->where('short_code', $shortCode)->first();
        
        if ($record) {
            // Increment click counter
            $this->where('short_code', $shortCode)
                 ->set('clicks', 'clicks + 1', false)
                 ->update();
            
            return $record['full_url'];
        }
        
        return null;
    }
}
