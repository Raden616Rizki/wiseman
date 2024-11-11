<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Wiseman Send Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: #f4f4f4;
        }

        table {
            border-collapse: collapse !important;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .email-header {
            padding: 20px;
            text-align: center;
        }

        .email-header img {
            max-width: 150px;
        }

        /*  */
        .email-body {
            background-color: #ffffff;
            padding: 20px;
            font-family: Arial, sans-serif;
            color: #333333;
            line-height: 1.6;
        }

        .email-body h1 {
            font-size: 22px;
            color: #0056b3;
            margin: 0 0 20px 0;
        }

        .email-footer {
            background-color: #067e82;
            padding: 20px;
            font-size: 12px;
            color: #ffffff;
        }

        .btn-container {
            text-align: center;
        }

        .btn {
            background-color: #067e82;
            color: #ffffff;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
            margin: 20px 0;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .footer-address {
            font-size: 12px;
            color: #ffffff;
            text-align: right;
        }

        .footer-address-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-address-container img {
            max-width: 114px;
        }
    </style>
</head>
{{-- resources/views/emails/ForgotPassword.blade.php --}}
<body>
    <table class="email-container" role="presentation" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <div>
                    {{-- <div class="email-header" style="background-color: #ffffff;">
                        <img src="https://drive.google.com/file/d/1JGiv_yovcYW4PW2hAx00-PO7w1i64VHJ/view?usp=drive_link" alt="logo perusahaan">
                    </div> --}}
                    <div
                        style="background-color: #067e82; padding: 20px; display: flex; align-items: center; margin: 0;">
                        <div>
                            <h1 style="color: white; margin: 0; font-size: 16px;">Permintaan Ganti Password</h1>
                        </div>
                    </div>
                    <div class="email-body">

                        <p style="font-size: 12px;">
                            {{ $name }}, Kami menerima permintaan untuk mereset kata sandi akun Anda. Jika Anda yang meminta reset ini,
                            silakan klik tombol di bawah untuk melanjutkan proses penggantian kata sandi.
                        </p>
                        <a class="button" href={{ $link }} style="display: block; padding: 10px 20px; background-color: #067e82; color: white; text-decoration: none; text-align: center; width: fit-content; margin: 20px auto; border-radius: 5px;">
                            Klik Disini</a>
                        <p>Jika tombol tidak berfungsi, Anda dapat menyalin link berikut dan membukanya di browser: <br>{{ $link }}</p>
                        <p style="font-size: 12px;">Hormat kami,<br><br><br>Wiseman Society</p>
                    </div>
                    <div style="background-color: #067e82; padding: 20px;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="padding-right: 10px;" align="left">
                                    {{-- <img src="https://drive.google.com/file/d/1JGiv_yovcYW4PW2hAx00-PO7w1i64VHJ/view?usp=drive_link"  alt="company logo"
                                        style="max-width: 114px;"> --}}
                                </td>
                                <td style="padding-left: 10px;" align="right">
                                    {{-- <p style="color: white; font-size: 10px; margin: 0;">
                                        JL Griya Shanta Permata Blok G No 8, Lowokwaru, Malang <br>
                                        Jawa Timur, 13860
                                    </p> --}}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
