<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Company;
use App\Models\JobOffer;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    private const MOROCCAN_CITIES = [
        'Rabat' => 'Libreville',
        'Casablanca' => 'Libreville',
        'Tanger' => 'Port-Gentil',
        'Marrakech' => 'Franceville',
        'Fès' => 'Oyem',
    ];

    public function run(): void
    {
        $recruiter = User::where('email', 'recruteur@carreergo.test')->first();
        $candidate = User::where('email', 'candidat@carreergo.test')->first();

        if (! $recruiter || ! $candidate) {
            $this->command?->warn('Comptes de démonstration absents : lancez d’abord DemoUsersSeeder.');

            return;
        }

        foreach (self::MOROCCAN_CITIES as $moroccan => $gabonese) {
            JobOffer::where('location', $moroccan)->update(['location' => $gabonese]);
        }

        $companies = collect([
            'Ogooué Numérique (démo)' => false,
            'Banque Akanda (démo)' => true,
            'Mayumba Énergies (démo)' => true,
            'Woleu Agro (démo)' => false,
        ])->map(fn (bool $verified, string $name) => Company::updateOrCreate(
            ['name' => $name],
            ['is_verified' => $verified],
        ));

        $offers = [];

        foreach ($this->offers() as $key => $data) {
            $offer = JobOffer::updateOrCreate(
                ['title' => $data['title'], 'company_id' => $companies[$data['company']]->id],
                [
                    'user_id' => $recruiter->id,
                    'description' => $data['description'],
                    'location' => $data['location'],
                    'contract_type' => $data['contract_type'],
                    'salary_min' => $data['salary_min'] ?? null,
                    'salary_max' => $data['salary_max'] ?? null,
                    'show_salary' => $data['show_salary'] ?? false,
                    'stage_duration_months' => $data['stage_duration_months'] ?? null,
                    'stage_stipend' => $data['stage_stipend'] ?? null,
                    'guaranteed_response' => $data['guaranteed_response'] ?? false,
                    'status' => $data['status'] ?? 'published',
                ],
            );

            if ($offer->wasRecentlyCreated) {
                $offer->forceFill(['created_at' => now()->subDays($data['days_ago'])])->saveQuietly();
            }

            $offer->skills()->sync(
                collect($data['skills'])->map(fn (string $name) => Skill::firstOrCreate(['name' => $name])->id)
            );

            $offers[$key] = $offer;
        }

        foreach ($this->applications() as $key => [$status, $daysAgo]) {
            Application::updateOrCreate(
                ['user_id' => $candidate->id, 'job_offer_id' => $offers[$key]->id],
                ['status' => $status, 'applied_at' => now()->subDays($daysAgo)],
            );
        }
    }

    private function offers(): array
    {
        return [
            'web' => [
                'title' => 'Développeur·se web Laravel',
                'company' => 'Ogooué Numérique (démo)',
                'location' => 'Libreville',
                'contract_type' => 'CDI',
                'salary_min' => 650000,
                'salary_max' => 900000,
                'show_salary' => true,
                'guaranteed_response' => true,
                'days_ago' => 2,
                'skills' => ['PHP', 'Laravel', 'Vue.js', 'MySQL'],
                'description' => "Au sein d'une équipe de quatre développeurs, vous concevez et maintenez les applications web de nos clients.\n\nMissions :\n- développer de nouvelles fonctionnalités avec Laravel et Vue.js ;\n- écrire des tests automatisés ;\n- participer aux revues de code.",
            ],
            'comptable' => [
                'title' => 'Comptable confirmé(e)',
                'company' => 'Banque Akanda (démo)',
                'location' => 'Port-Gentil',
                'contract_type' => 'CDI',
                'salary_min' => 700000,
                'salary_max' => 950000,
                'show_salary' => false,
                'days_ago' => 5,
                'skills' => ['Comptabilité', 'Excel', 'Fiscalité'],
                'description' => "Vous prenez en charge la comptabilité générale de l'agence de Port-Gentil : saisie, rapprochements bancaires, déclarations fiscales et préparation des clôtures mensuelles.",
            ],
            'maintenance' => [
                'title' => 'Technicien·ne de maintenance industrielle',
                'company' => 'Mayumba Énergies (démo)',
                'location' => 'Port-Gentil',
                'contract_type' => 'CDD',
                'salary_min' => 450000,
                'salary_max' => 600000,
                'show_salary' => true,
                'days_ago' => 9,
                'skills' => ['Électricité', 'Maintenance', 'Sécurité HSE'],
                'description' => "Contrat de 12 mois. Vous assurez la maintenance préventive et corrective des équipements électriques du site, dans le respect des règles HSE.",
            ],
            'marketing' => [
                'title' => 'Stage — Assistant·e marketing digital',
                'company' => 'Ogooué Numérique (démo)',
                'location' => 'Libreville',
                'contract_type' => 'Stage',
                'stage_duration_months' => 4,
                'stage_stipend' => 150000,
                'guaranteed_response' => true,
                'days_ago' => 1,
                'skills' => ['Réseaux sociaux', 'Rédaction', 'Canva'],
                'description' => "Vous accompagnez la responsable marketing : calendrier éditorial, création de visuels, suivi des statistiques des réseaux sociaux.",
            ],
            'agronome' => [
                'title' => 'Stage — Agronome junior',
                'company' => 'Woleu Agro (démo)',
                'location' => 'Oyem',
                'contract_type' => 'Stage',
                'stage_duration_months' => 6,
                'stage_stipend' => 120000,
                'days_ago' => 12,
                'skills' => ['Agronomie', 'Gestion de projet'],
                'description' => "Vous participez au suivi des parcelles de cacao et de manioc, aux relevés de terrain et à la rédaction des rapports de campagne.",
            ],
            'graphiste' => [
                'title' => 'Graphiste (mission freelance)',
                'company' => 'Ogooué Numérique (démo)',
                'location' => 'Libreville',
                'contract_type' => 'Freelance',
                'salary_min' => 300000,
                'salary_max' => 500000,
                'show_salary' => true,
                'days_ago' => 4,
                'skills' => ['Illustrator', 'Photoshop', 'Canva'],
                'description' => "Mission de trois mois pour refondre l'identité visuelle de deux clients : logo, charte graphique et supports imprimés.",
            ],
            'clientele' => [
                'title' => 'Chargé(e) de clientèle',
                'company' => 'Banque Akanda (démo)',
                'location' => 'Franceville',
                'contract_type' => 'CDD',
                'salary_min' => 350000,
                'salary_max' => 450000,
                'show_salary' => true,
                'guaranteed_response' => true,
                'days_ago' => 7,
                'skills' => ['Relation client', 'Anglais'],
                'description' => "Contrat de 6 mois. Vous accueillez et conseillez les clients de l'agence de Franceville et assurez le suivi de leurs demandes.",
            ],
            'logistique' => [
                'title' => 'Responsable logistique',
                'company' => 'Mayumba Énergies (démo)',
                'location' => 'Franceville',
                'contract_type' => 'CDI',
                'status' => 'pending',
                'days_ago' => 0,
                'skills' => ['Logistique', 'Gestion de projet'],
                'description' => "Vous pilotez les approvisionnements et le stockage du site de Franceville. Offre en attente de modération, pour la démonstration de l'espace administrateur.",
            ],
        ];
    }

    private function applications(): array
    {
        return [
            'web' => ['interview', 2],
            'comptable' => ['rejected', 5],
            'maintenance' => ['accepted', 8],
            'marketing' => ['reviewing', 1],
            'graphiste' => ['new', 0],
        ];
    }
}
