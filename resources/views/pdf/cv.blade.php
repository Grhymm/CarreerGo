<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>CV - {{ $user->name }}</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #1f2937;
            font-size: 12px;
        }

        h1 {
            font-size: 22px;
            margin: 0;
        }

        .job-title {
            font-size: 14px;
            color: #4f46e5;
            margin: 4px 0 20px 0;
        }

        .email {
            color: #6b7280;
            margin: 0 0 20px 0;
        }

        h2 {
            font-size: 14px;
            text-transform: uppercase;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 4px;
            margin-top: 24px;
            margin-bottom: 10px;
        }

        .skill {
            display: inline-block;
            background-color: #eef2ff;
            color: #4338ca;
            border-radius: 12px;
            padding: 3px 10px;
            margin: 0 6px 6px 0;
            font-size: 11px;
        }

        .experience {
            margin-bottom: 12px;
        }

        .experience-title {
            font-weight: bold;
        }

        .experience-meta {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <h1>{{ $user->name }}</h1>

    @if ($experiences->isNotEmpty())
        <p class="job-title">{{ $experiences->first()->title }}</p>
    @endif

    <p class="email">{{ $user->email }}</p>

    <h2>Compétences</h2>
    @if ($skills->isNotEmpty())
        <div>
            @foreach ($skills as $skill)
                <span class="skill">{{ $skill->name }}</span>
            @endforeach
        </div>
    @else
        <p>Aucune compétence renseignée.</p>
    @endif

    <h2>Expériences</h2>
    @if ($experiences->isNotEmpty())
        @foreach ($experiences as $experience)
            <div class="experience">
                <div class="experience-title">{{ $experience->title }} — {{ $experience->company_name }}</div>
                <div class="experience-meta">
                    {{ $experience->start_date->format('m/Y') }} —
                    {{ $experience->end_date ? $experience->end_date->format('m/Y') : "aujourd'hui" }}
                </div>
            </div>
        @endforeach
    @else
        <p>Aucune expérience renseignée.</p>
    @endif
</body>
</html>
