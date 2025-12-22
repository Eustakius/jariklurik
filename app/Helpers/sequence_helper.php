<?php
use Config\Database;
function generate_sequence_simple(string $table, string $column, string $prefix = '', ?string $dateFormat = 'Ymd', int $digits = 4): string
{
    $db = Database::connect();

    $datePart = $dateFormat ? date($dateFormat) : '';
    $base = $prefix . $datePart;

    $builder = $db->table($table);
    $likePattern = $base . '%';

    $row = $builder
        ->select("MAX($column) as max_code", false)
        ->like($column, $base, 'after')
        ->get()
        ->getRow();

    $maxCode = $row ? $row->max_code : null;

    if (!$maxCode) {
        $nextNumber = 1;
    } else {
        $suffix = substr($maxCode, strlen($base));
        $suffix = preg_replace('/\D/', '', $suffix);
        $nextNumber = (int)$suffix + 1;
    }

    $numberPart = str_pad((string)$nextNumber, $digits, '0', STR_PAD_LEFT);
    return $base . $numberPart;
}

function generate_sequence(string $key, string $prefix = '', ?string $dateFormat = 'Ymd', int $digits = 4, string $reset = '')
{
    $db = Database::connect();
    $datePart = $dateFormat ? date($dateFormat) : '';
    $period = '';

    if ($reset === 'daily') {
        $period = date('Y-m-d');
    } elseif ($reset === 'monthly') {
        $period = date('Y-m');
    } elseif ($reset === 'yearly') {
        $period = date('Y');
    }

    $fullKey = $key . ($period ? '_' . $period : '');

    $db->transStart();

    $updated = $db->table('sequences')
        ->set('seq', 'seq + 1', false)
        ->set('updated_at', date('Y-m-d H:i:s'))
        ->where('name', $fullKey)
        ->update();

    if ($db->affectedRows() === 0) {
        $inserted = $db->table('sequences')->insert([
            'name' => $fullKey,
            'seq' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        if (!$inserted) {
            $db->transRollback();
            throw new RuntimeException('Failed to insert sequence row');
        }
        $currentSeq = 1;
    } else {
        $row = $db->table('sequences')->select('seq')->where('name', $fullKey)->get()->getRow();
        $currentSeq = $row ? (int)$row->seq : 0;
    }

    $db->transComplete();

    if ($db->transStatus() === false) {
        throw new RuntimeException('Transaction failed when generating sequence');
    }

    $numberPart = str_pad((string)$currentSeq, $digits, '0', STR_PAD_LEFT);
    $datePartRendered = $dateFormat ? date($dateFormat) : '';

    return $prefix . $datePartRendered . $numberPart;
}
