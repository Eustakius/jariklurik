<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situs Sedang Dalam Perbaikan - Jariklurik</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            background-color: white;
            padding: 2rem 3rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            max-width: 500px;
            width: 90%;
        }
        h1 {
            color: #d97706; /* Amber-600 */
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }
        p {
            line-height: 1.6;
            margin-bottom: 1.5rem;
            color: #4b5563;
        }
        .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #d97706;
        }
        .contact {
            font-size: 0.9rem;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 1rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🛠️</div>
        <h1>Sedang Dalam Perbaikan</h1>
        <p>
            Mohon maaf, saat ini situs kami sedang menjalani pemeliharaan sistem terjadwal untuk meningkatkan layanan kami. 
            <br>
            Kami akan segera kembali. Terima kasih atas kesabaran Anda.
        </p>
        <?php if (!empty($message)): ?>
            <p style="background-color: #fffbeb; padding: 10px; border-radius: 6px; border: 1px solid #fcd34d; font-size: 0.95rem;">
                <strong>Pesan dari Admin:</strong><br>
                <?= esc($message) ?>
            </p>
        <?php endif; ?>
        <div class="contact">
            &copy; <?= date('Y') ?> Jariklurik. All rights reserved.
        </div>
    </div>
</body>
</html>
