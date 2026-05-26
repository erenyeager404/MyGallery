<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi Email</title>
</head>

<body style="margin:0;padding:0;background:#0F172A;font-family:Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 20px;">
    <tr>
      <td align="center">
        <table width="560" cellpadding="0" cellspacing="0" style="max-width:560px;width:100%;">
          {{-- Logo --}}
          <tr>
            <td align="center" style="padding-bottom:28px;">
              <span style="font-size:28px;font-weight:800;color:#FFF;">
                My<span style="color:#A78BFA;">Gallery</span>
              </span>
            </td>
          </tr>

          {{-- Card --}}
          <tr>
            <td style="background:#1E293B;border:1px solid #334155;border-radius:16px;overflow:hidden;">
              {{-- Banner --}}
              <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="center"
                    style="background:linear-gradient(135deg,#4C1D95,#7C3AED);padding:40px 32px;border-radius:16px 16px 0 0;">
                    <div style="font-size:40px;margin-bottom:12px;">&#128140;</div>
                    <div style="font-size:22px;font-weight:700;color:#FFF;margin-bottom:6px;">Verifikasi Email Kamu
                    </div>
                    <div style="font-size:14px;color:#C4B5FD;">Satu langkah lagi untuk mulai berbagi foto</div>
                  </td>
                </tr>

                {{-- Body --}}
                <tr>
                  <td style="padding:32px;">
                    <p style="font-size:16px;color:#CBD5E1;margin:0 0 12px;">
                      Halo, <strong style="color:#F1F5F9;">{{ $user->name }}</strong>!
                    </p>
                    <p style="font-size:14px;color:#94A3B8;line-height:1.7;margin:0 0 28px;">
                      Terima kasih sudah mendaftar di <strong style="color:#A78BFA;">MyGallery</strong>.
                      Klik tombol di bawah untuk memverifikasi email kamu.
                    </p>

                    <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center" style="padding-bottom:28px;">
                          <a href="{{ $url }}" style="display:inline-block;background:linear-gradient(135deg,#6D28D9,#7C3AED);
                                          color:#FFF;text-decoration:none;font-size:15px;font-weight:700;
                                          padding:14px 44px;border-radius:10px;">
                            &#10003; Verifikasi Email Sekarang
                          </a>
                        </td>
                      </tr>
                    </table>

                    <div style="background:#1E1A2E;border:1px solid #4C1D95;border-radius:10px;
                                    padding:12px 16px;font-size:12px;color:#A78BFA;margin-bottom:20px;">
                      &#9200; Link ini kadaluarsa dalam <strong>60 menit</strong>.
                    </div>

                    <hr style="border:none;border-top:1px solid #334155;margin:24px 0;">

                    <p style="font-size:12px;color:#64748B;margin:0 0 6px;">
                      Jika tombol tidak berfungsi, copy URL ini ke browser:
                    </p>
                    <p style="font-size:11px;margin:0;word-break:break-all;">
                      <a href="{{ $url }}" style="color:#818CF8;">{{ $url }}</a>
                    </p>

                    <hr style="border:none;border-top:1px solid #334155;margin:24px 0;">

                    <p style="font-size:12px;color:#64748B;margin:0;">
                      Jika tidak merasa mendaftar, abaikan email ini.
                    </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          {{-- Footer --}}
          <tr>
            <td align="center" style="padding-top:24px;">
              <p style="font-size:12px;color:#475569;margin:0;line-height:1.8;">
                &copy; {{ date('Y') }} MyGallery &mdash; Email otomatis, jangan dibalas.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>

</html>