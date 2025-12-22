<?php

namespace App\Controllers\Api;

use CodeIgniter\Controller;
use App\Models\EmailQueueModel;
use App\Models\JobVacancyModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

class CronController extends Controller
{
    public function emailqueuejobvacancy()
    {
        $secret = $this->request->getGet('key');
        if ($secret !== env('CRON_SECRET')) {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }

        $jobVacancyModel = new JobVacancyModel();
        $queueModel = new EmailQueueModel();

        $now = date('Y-m-d H:i:s');
        $next3days = date('Y-m-d H:i:s', strtotime('+3 days'));

        $expiredJobsAlert = $jobVacancyModel
            ->where('deleted_at', null)
            ->where('selection_date >=', $now)
            ->where('selection_date <=', $next3days)
            ->findAll();
        $insertData = [];
        foreach ($expiredJobsAlert as $job) {
            if (!$job->company || empty($job->company->email)) {
                continue;
            }

            $alreadyQueued = $queueModel
                ->where('to_email', $job->company->email)
                ->like('subject', 'Lowongan ' . $job->position . ' Tanggal ' . date('d M Y', strtotime($job->selection_date)) . ' Seleksi Akan Berlansung')
                ->first();

            if ($alreadyQueued) {
                continue;
            }

            $subject = 'Lowongan ' . $job->position . ' Tanggal  ' . date('d M Y', strtotime($job->selection_date)) . ' Seleksi Akan Berlansung';
            $body = "
                <p>Halo <strong>{$job->company->name}</strong>,</p>
                <p>Lowongan kerja <strong>{$job->position}</strong> akan berakhir pada 
                <strong>" . date('d M Y', strtotime($job->selection_date)) . "</strong>.</p>
                <p>Segera perbarui atau buat lowongan baru untuk terus menarik kandidat terbaik.<br/><a href=" . env('app.baseBackendURL') . "/job-vacancy/" . $job->id . "/edit" . ">Link {$job->position}, klik disini</a></p>
                <p>Terima kasih,<br>Jariklurik</p>
            ";

            $insertData[] = [
                'to_email' => $job->company->email,
                'from_email' => env('email.fromEmail'),
                'subject' => $subject,
                'body' => $body,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        $expiredJobs = $jobVacancyModel
            ->where('deleted_at', null)
            ->where('selection_date <', $now)
            ->findAll();
        $insertData = [];
        foreach ($expiredJobs as $job) {
            if (!isset($job->company) || empty($job->company->email)) {
                continue;
            }

            $alreadyQueued = $queueModel
                ->where('to_email', $job->company->email)
                ->like('subject', 'Lowongan ' . $job->position . ' Tanggal ' . date('d M Y', strtotime($job->selection_date)) . ' Seleksi Sudah Lewat')
                ->first();

            if ($alreadyQueued) {
                continue;
            }

            $subject = 'Lowongan ' . $job->position . ' Tanggal ' . date('d M Y', strtotime($job->selection_date)) . ' Seleksi Sudah Lewat';
            $body = "<p>Halo <strong>{$job->company->name}</strong>,</p>
                <p>Lowongan kerja <strong>{$job->position}</strong> sudah berakhir pada 
                <strong>" . date('d M Y', strtotime($job->selection_date)) . "</strong>.</p>
                <p>Segera perbarui atau buat lowongan baru untuk terus menarik kandidat terbaik.<br/><a href=" . env('app.baseBackendURL') . "/job-vacancy/" . $job->id . "/edit" . ">Link {$job->position}, klik disini</a></p>
                <p>Terima kasih,<br>Jariklurik</p>";

            $insertData[] = [
                'to_email' => $job->company->email,
                'from_email' => env('email.fromEmail'),
                'subject' => $subject,
                'body' => $body,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        if (!empty($insertData)) {
            $queueModel->insertBatch($insertData);
            echo count($insertData) . " email queue berhasil dibuat.";
        } else {
            echo "Semua job sudah ada di email_queue.";
        }

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
                $emailService->setMailType('html');

                // Kirim
                if ($emailService->send()) {
                    $emailModel->update($row['id'], [
                        'status' => 'sent',
                        'sent_at' => date('Y-m-d H:i:s'),
                        'error' => null,
                        'id' => $row['id']
                    ]);
                    CLI::write("Sent: {$row['to_email']}", 'green');
                } else {
                    $err = $emailService->printDebugger(['headers']);
                    $emailModel->update($row['id'], [
                        'status' => 'failed',
                        'error' => substr($err, 0, 1000),
                        'id' => $row['id']
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
