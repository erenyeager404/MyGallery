<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi Email</title>
</head>
<body style="margin:0;padding:0;background-color:#0F172A;font-family:'Segoe UI',Arial,sans-serif;">

  <!-- Wrapper -->
  <table width="100%" cellpadding="0" cellspacing="0" border="0"
         style="background-color:#0F172A;padding:40px 20px;">
    <tr>
      <td align="center">

        <!-- Container -->
        <table width="560" cellpadding="0" cellspacing="0" border="0"
               style="max-width:560px;width:100%;">

          <!-- LOGO -->
          <tr>
            <td align="center" style="padding-bottom:28px;">
              <span style="font-size:30px;font-weight:800;color:#FFFFFF;letter-spacing:-0.5px;">
                Our<span style="color:#7C3AED;">Memora</span>
              </span>
            </td>
          </tr>

          <!-- CARD -->
          <tr>
            <td style="background-color:#1E293B;border:1px solid #334155;
                       border-radius:16px;overflow:hidden;">

              <!-- Banner ungu -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center"
                      style="background:linear-gradient(135deg,#4C1D95,#7C3AED);
                             padding:40px 32px;border-radius:16px 16px 0 0;">

                    <!-- Icon bulat -->
                    <div style="width:64px;height:64px;background:rgba(255,255,255,0.15);
                                border-radius:50%;margin:0 auto 16px;
                                display:flex;align-items:center;justify-content:center;
                                font-size:28px;line-height:64px;text-align:center;">
                        
                    </div>

                    <div style="font-size:22px;font-weight:700;color:#FFFFFF;margin-bottom:6px;">
                      Verifikasi Email Kamu
                    </div>
                    <div style="font-size:14px;color:#C4B5FD;">
                      Satu langkah lagi untuk mulai berbagi foto
                    </div>
                  </td>
                </tr>

                <!-- Body card -->
                <tr>
                  <td style="padding:32px;">

                    <!-- Greeting -->
                    <p style="font-size:16px;color:#CBD5E1;margin:0 0 12px 0;">
                      Halo, <strong style="color:#F1F5F9;">{{ $user->name }}</strong>! 👋
                    </p>

                    <!-- Pesan -->
                    <p style="font-size:14px;color:#94A3B8;line-height:1.7;margin:0 0 28px 0;">
                      Terima kasih sudah mendaftar di
                      <strong style="color:#A78BFA;">OurMemora</strong>.
                      Untuk mengaktifkan akunmu dan mulai mengupload foto,
                      klik tombol di bawah ini untuk memverifikasi alamat email kamu.
                    </p>

                    <!-- Tombol -->
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td align="center" style="padding-bottom:28px;">
                          <a href="{{ $url }}"
                             style="display:inline-block;
                                    background:linear-gradient(135deg,#6D28D9,#7C3AED);
                                    color:#FFFFFF;text-decoration:none;
                                    font-size:15px;font-weight:700;
                                    padding:14px 44px;border-radius:10px;
                                    letter-spacing:0.3px;">
                            ✓ &nbsp; Verifikasi Email Sekarang
                          </a>
                        </td>
                      </tr>
                    </table>

                    <!-- Expire notice -->
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td style="background-color:#1E1A2E;border:1px solid #4C1D95;
                                   border-radius:10px;padding:12px 16px;
                                   font-size:12px;color:#A78BFA;">
                          Link ini akan kadaluarsa dalam
                          <strong>60 menit</strong>.
                          Jika sudah lewat, kamu bisa minta link baru
                          dari halaman verifikasi.
                        </td>
                      </tr>
                    </table>

                    <!-- Divider -->
                    <hr style="border:none;border-top:1px solid #334155;margin:24px 0;">

                    <!-- URL Fallback -->
                    <p style="font-size:12px;color:#64748B;margin:0 0 6px 0;">
                      Jika tombol di atas tidak berfungsi, copy URL ini ke browser:
                    </p>
                    <p style="font-size:11px;margin:0;word-break:break-all;">
                      <a href="{{ $url }}" style="color:#818CF8;">{{ $url }}</a>
                    </p>

                    <!-- Divider -->
                    <hr style="border:none;border-top:1px solid #334155;margin:24px 0;">

                    <!-- Disclaimer -->
                    <p style="font-size:12px;color:#64748B;margin:0;line-height:1.6;">
                      Jika kamu tidak merasa membuat akun di OurMemora,
                      abaikan email ini — tidak ada tindakan lebih lanjut
                      yang diperlukan.
                    </p>

                  </td>
                </tr>
              </table>

            </td>
          </tr>

          <!-- FOOTER -->
          <tr>
            <td align="center" style="padding-top:24px;">
              <p style="font-size:12px;color:#475569;margin:0;line-height:1.8;">
                © {{ date('Y') }} OurMemora. All rights reserved.<br>
                Email ini dikirim otomatis, mohon jangan membalas email ini.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>