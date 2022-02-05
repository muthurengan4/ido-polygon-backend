<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{Setting::get('site_name')}}</title>
    <style>
        /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */
        img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
        }

        body {
            background-color: #2a2a2b;
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        table {
            border-collapse: separate;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            width: 100%;
        }

        table td {
            font-family: sans-serif;
            font-size: 14px;
            vertical-align: top;
        }

        /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */
        .body {
            background-color: #2a2a2b;
            width: 100%;
        }

        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            display: block;
            Margin: 0 auto !important;
            /* makes it centered */
            max-width: 580px;
            padding: 10px;
            width: 580px;
        }

        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            max-width: 580px;
            padding: 10px;
        }

        /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
        .main {
            background: #fff;
            border-radius: 3px;
            width: 100%;
        }

        .wrapper {
            box-sizing: border-box;
            padding: 4em;
        }

        .footer {
            clear: both;
            padding-top: 10px;
            text-align: center;
            width: 100%;
        }

        .footer td,
        .footer p,
        .footer span,
        .footer a {
            color: #999999;
            font-size: 12px;
            text-align: center;
        }

        /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
        h1,
        h2,
        h3,
        h4 {
            color: #000000;
            font-family: sans-serif;
            font-weight: 400;
            line-height: 1.4;
            margin: 0;
            margin-bottom: 30px;
            margin-top: 15px;
        }

        h1 {
            font-size: 35px;
            font-weight: 300;
            text-align: center;
            text-transform: capitalize;
        }

        p,
        ul,
        ol {
            font-family: sans-serif;
            font-size: 14px;
            font-weight: normal;
            margin: 0;
            margin-bottom: 15px;
        }

        p li,
        ul li,
        ol li {
            list-style-position: inside;
            margin-left: 5px;
        }

        a {
            color: #3498db;
            text-decoration: underline;
        }

        /* -------------------------------------
          BUTTONS
      ------------------------------------- */
        .btn {
            box-sizing: border-box;
            width: 100%;
        }

        .btn>tbody>tr>td {
            padding-bottom: 15px;
        }

        .btn table {
            width: auto;
        }

        .btn table td {
            background-color: #ffffff;
            border-radius: 5px;
            text-align: center;
        }

        .btn a {
            background-color: #ffffff;
            border: solid 1px #3498db;
            border-radius: 5px;
            box-sizing: border-box;
            color: #3498db;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            padding: 12px 25px;
            text-decoration: none;
            text-transform: capitalize;
        }

        .btn-primary a {
            background: #ffba00;
            border-color: #ffba00;
            border-radius: 0px;
            pointer-events: all;
            cursor: pointer;
            font-size: 1em;
            font-weight: 700;
            letter-spacing: .5px;
            display: block;
            width: 100%;
            color: #ffffff;
            text-align: center;
            margin: 0.5em 0;
        }

        /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .clear {
            clear: both;
        }

        .mt0 {
            margin-top: 0;
        }

        .mb0 {
            margin-bottom: 0;
        }

        .preheader {
            color: transparent;
            display: none;
            height: 0;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            mso-hide: all;
            visibility: hidden;
            width: 0;
        }

        .powered-by a {
            text-decoration: none;
        }

        hr {
            border: 0;
            border-bottom: 1px solid #f6f6f6;
            margin: 20px 0;
        }

        .desc {
            font-size: 1.1em;
            color: #525f7f;
        }

        .sub-desc {
            color: #8898aa;
            font-size: 0.9em;
        }

        .marg-btm-sm {
            margin-bottom: 10px !important;
        }

        /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
        @media only screen and (max-width: 620px) {
            table[class=body] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }

            table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
                font-size: 16px !important;
                text-align: center;
            }

            table[class=body] .wrapper,
            table[class=body] .article {
                padding: 10px !important;
            }

            table[class=body] .content {
                padding: 0 !important;
            }

            table[class=body] .container {
                padding: 0 !important;
                width: 100% !important;
            }

            table[class=body] .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }

            table[class=body] .btn table {
                width: 100% !important;
            }

            table[class=body] .btn a {
                width: 100% !important;
            }

            table[class=body] .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }

            .align-center {
                margin: 0 auto !important;
                text-align: center !important;
            }
        }

        @media all {
            .ExternalClass {
                width: 100%;
            }

            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }

            .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }

            .btn-primary table td:hover {
                background-color: #ffba00 !important;
            }

            .btn-primary a:hover {
                background-color: #ffba00 !important;
                border-color: #ffba00 !important;
            }
        }
    </style>
</head>


<body>
    <table border="0" cellpadding="0" cellspacing="0" class="body">
        <tr>
            <td>&nbsp;</td>
            <td class="container">
                <div class="content">
                    <span class="preheader"> {{tr('welcome_to')}} {{Setting::get('site_name')}}
                    </span>
                    <table class="main">
                        <!-- START MAIN CONTENT AREA -->
                        <tr>
                            <td class="wrapper">
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;">
                                            <a target="_blank" style="text-decoration: none;" href="{{Setting::get('frontend_url')}}"><img border="0" vspace="0" hspace="0" src="{{Setting::get('site_logo')}}" width="100" height="30" class="align-center" alt="Logo" title="Logo" style="color: #FFFFFF; font-size: 10px;max-width: 10em; margin: 0; padding: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;" />
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-top: 0px;" class="hero">
                                            <hr class="border-bottom">
                                            </hr>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-top: 0px;" class="hero">
                                            <p class="desc">{{tr('hello')}} {{$data['data']['name']}}!</p>

                                            <p class="desc">{{tr('thanks_for_signup')}} {{Setting::get('site_name')}}. {{tr('user_welcome_description')}}</p>

                                            <h2>{{$data['data']['verification_code'] ?? ($data['data']['verification_code'] ?? "")}}</h2>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td align="left">
                                           
                                            <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                                                <tbody>
                                                    <tr>
                                                        <td align="left">
                                                            <a href="{{Setting::get('frontend_url')}}" target="_blank">{{tr('visit_website')}}</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table><br>
                                            <p class="desc">{{tr('need_help')}} {{tr('visit')}} 
                                                <a href="{{Setting::get('frontend_url')}}">
                                                    {{Setting::get('site_name')}} {{tr('support')}}
                                                </a>
                                                
                                                or 
                                                
                                                <a href="{{Setting::get('frontend_url')}}/pages/contact">{{tr('contact_us')}}.</a>
                                            </p>
                                        </td>
                                    </tr>
                                   
                                    <tr>
                                        <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-top: 0px;" class="hero">
                                            <p class="desc">{{tr('best_regards')}},<br><br>{{Setting::get('site_name')}}<br>{{Setting::get('tag_name')}}<br></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-top: 0px;" class="hero">
                                            <hr class="border-bottom">
                                            </hr>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-top: 0px;" class="hero">
                                            <p class="sub-desc"></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- END MAIN CONTENT AREA -->
                    </table>
                    <!-- END CENTERED WHITE CONTAINER -->
                </div>
            </td>
            <td>&nbsp;</td>
        </tr>
    </table>
</body>

</html>