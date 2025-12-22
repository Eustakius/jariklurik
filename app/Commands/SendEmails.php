<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\EmailQueueModel;
use Config\Services;

class SendEmails extends BaseCommand
{
    protected $group       = 'cron';
    protected $name        = 'send:emails';
    protected $description = 'Kirim email terjadwal dari antrian email.';

    public function run(array $params = [])
    {
        CLI::write('Starting send emails job: ' . date('Y-m-d H:i:s'), 'yellow');

        $emailModel = new EmailQueueModel();

        // Ambil misal 50 pesan yang belum dikirim
        $toSend = $emailModel->where('status', 'pending')
                             ->orderBy('created_at', 'ASC')
                             ->findAll(50);

        if (empty($toSend)) {
            CLI::write('No emails to send.', 'green');
            return;
        }

        $emailService = Services::email();

        foreach ($toSend as $row) {
            try {
                // Atur konfigurasi email dari env atau global config
                $emailService->clear();
                $emailService->setFrom($row['from_email'] ?? env('email.fromEmail'));
                $emailService->setTo($row['to_email']);
                $emailService->setSubject($row['subject']);
                $emailService->setMessage($row['body']);

                // Kirim
                if ($emailService->send()) {
                    $emailModel->update($row['id'], [
                        'status' => 'sent',
                        'sent_at' => date('Y-m-d H:i:s'),
                        'error' => null,
                        'id'=> $row['id']
                    ]);
                    CLI::write("Sent: {$row['to_email']}", 'green');
                } else {
                    $err = $emailService->printDebugger(['headers']);
                    $emailModel->update($row['id'], [
                        'status' => 'failed',
                        'error' => substr($err, 0, 1000),
                        'id'=> $row['id']
                    ]);
                    CLI::write("Failed: {$row['to_email']} — " . trim($err), 'red');
                }

                sleep(1); // sesuaikan jika perlu
            } catch (\Exception $e) {
                $emailModel->update($row['id'], [
                    'status' => 'failed',
                    'error' => $e->getMessage()
                ]);
                CLI::write("Exception: {$e->getMessage()}", 'red');
            }
        }

        CLI::write('Job finished: ' . date('Y-m-d H:i:s'), 'yellow');
    }
}
