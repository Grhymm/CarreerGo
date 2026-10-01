<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>CV - {{ $user->name }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10.5px;
            line-height: 1.45;
            color: #1B2B25;
        }

        .header {
            background-color: #0F4C3A;
            color: #FFFFFF;
            padding: 36px 44px 28px 44px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .header .job-title {
            margin: 6px 0 0 0;
            font-size: 13px;
            color: #E2EAE7;
        }

        .accent {
            height: 5px;
            background-color: #C89B3C;
        }

        .layout {
            width: 100%;
            border-collapse: collapse;
        }

        .sidebar {
            width: 32%;
            vertical-align: top;
            background-color: #F1E9D8;
            padding: 24px 22px 24px 44px;
        }

        .main {
            vertical-align: top;
            padding: 24px 44px 24px 26px;
        }

        h2 {
            margin: 0 0 10px 0;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0F4C3A;
            border-bottom: 1.5px solid #C89B3C;
            padding-bottom: 4px;
        }

        .section {
            margin-bottom: 22px;
        }

        .contact-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4A4C4A;
            margin: 0;
        }

        .contact-value {
            margin: 1px 0 8px 0;
            word-wrap: break-word;
        }

        .skill {
            display: inline-block;
            background-color: #FFFFFF;
            color: #0F4C3A;
            border: 1px solid #BCCDC8;
            border-radius: 9px;
            padding: 2px 8px;
            margin: 0 4px 5px 0;
            font-size: 9.5px;
        }

        .experience {
            margin-bottom: 14px;
            padding-left: 10px;
            border-left: 2px solid #14607A;
        }

        .experience-title {
            margin: 0;
            font-size: 12px;
            font-weight: bold;
        }

        .experience-company {
            margin: 1px 0 0 0;
            color: #14607A;
            font-weight: bold;
        }

        .experience-meta {
            margin: 1px 0 0 0;
            color: #4A4C4A;
        }

        .empty {
            color: #4A4C4A;
            font-style: italic;
            margin: 0;
        }

        .footer {
            position: fixed;
            bottom: 18px;
            left: 44px;
            right: 44px;
            font-size: 8.5px;
            color: #5D605D;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $user->name }}</h1>
        @if ($experiences->isNotEmpty())
            <p class="job-title">{{ $experiences->first()->title }}</p>
        @endif
    </div>
    <div class="accent"></div>

    <table class="layout">
        <tr>
            <td class="sidebar">
                <div class="section">
                    <h2>Contact</h2>
                    <p class="contact-label">E-mail</p>
                    <p class="contact-value">{{ $user->email }}</p>
                </div>

                <div class="section">
                    <h2>Compétences</h2>
                    @if ($skills->isNotEmpty())
                        @foreach ($skills as $skill)
                            <span class="skill">{{ $skill->name }}</span>
                        @endforeach
                    @else
                        <p class="empty">Aucune compétence renseignée.</p>
                    @endif
                </div>
            </td>

            <td class="main">
                <div class="section">
                    <h2>Expériences professionnelles</h2>
                    @if ($experiences->isNotEmpty())
                        @foreach ($experiences as $experience)
                            <div class="experience">
                                <p class="experience-title">{{ $experience->title }}</p>
                                <p class="experience-company">{{ $experience->company_name }}</p>
                                <p class="experience-meta">
                                    {{ $experience->start_date->format('m/Y') }} –
                                    {{ $experience->end_date ? $experience->end_date->format('m/Y') : "aujourd'hui" }}
                                </p>
                            </div>
                        @endforeach
                    @else
                        <p class="empty">Aucune expérience renseignée.</p>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">CV généré avec CareerGO le {{ now()->format('d/m/Y') }}</div>
</body>
</html>
