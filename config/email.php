<?php
// Memanggil PHPMailer dari Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Menggunakan class PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Mengatur akun SMTP Gmail
define('SMTP_EMAIL', 'mchdalief24@gmail.com');
define('SMTP_APP_PASSWORD', 'bmzv ccwn nscv fnhc');
define('SMTP_NAME', 'Mey Salon');

// Mengirim email HTML
function kirimEmail($tujuan, $nama_tujuan, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {
        // Mengatur SMTP Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_EMAIL;
        $mail->Password   = SMTP_APP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Mengatur pengirim dan penerima
        $mail->setFrom(SMTP_EMAIL, SMTP_NAME);
        $mail->addAddress($tujuan, $nama_tujuan);

        // Mengatur isi email
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body    = $body;

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}

// Mengirim kode OTP ke email
function kirimOtpEmail($email, $nama, $otp, $judul = 'Kode OTP Mey Salon')
{
    $body = "
        <div style='font-family: Arial, sans-serif; background:#fff1f2; padding:24px;'>
            <div style='max-width:520px; margin:auto; background:white; border-radius:18px; padding:28px; border:1px solid #ffe4e6;'>
                <h2 style='color:#e11d48; margin:0 0 8px;'>Mey Salon</h2>

                <p style='font-size:14px; color:#374151;'>
                    Halo <b>$nama</b>,
                </p>

                <p style='font-size:14px; color:#374151;'>
                    Gunakan kode OTP berikut:
                </p>

                <div style='font-size:34px; font-weight:bold; letter-spacing:8px; color:#111827; background:#fff1f2; border-radius:14px; padding:18px; text-align:center; margin:22px 0;'>
                    $otp
                </div>

                <p style='font-size:14px; color:#374151;'>
                    Kode ini berlaku selama <b>10 menit</b>.
                </p>

                <p style='font-size:12px; color:#6b7280;'>
                    Jika Anda tidak meminta kode ini, abaikan email ini.
                </p>
            </div>
        </div>
    ";

    return kirimEmail($email, $nama, $judul, $body);
}
?>