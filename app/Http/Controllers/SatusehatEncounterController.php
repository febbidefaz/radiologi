<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class SatusehatEncounterController extends Controller
{
    private function getSatusehatToken()
    {
        $response = Http::asForm()->post(
            config('services.satusehat.auth_url') . '/accesstoken?grant_type=client_credentials',
            [
                'client_id' => config('services.satusehat.client_id'),
                'client_secret' => config('services.satusehat.client_secret'),
            ]
        );

        if (!$response->successful()) {
            throw new \Exception('Gagal mendapatkan token SATUSEHAT: '.$response->body());
        }

        return $response->json('access_token');
    }

    public function sendEncounter()
    {
        $token = $this->getSatusehatToken();

        $organizationId = config('services.satusehat.organization_id');

        $payload = [
            "resourceType" => "Encounter",

            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/encounter/".$organizationId,
                    "value" => "RJ-20260924-0001"
                ]
            ],

            "status" => "arrived",

            "class" => [
                "system" => "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                "code" => "AMB",
                "display" => "ambulatory"
            ],

            "subject" => [
                "reference" => "Patient/100000030009",
                "display" => "Nama Pasien"
            ],

            "participant" => [
                [
                    "type" => [
                        [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                    "code" => "ATND",
                                    "display" => "attender"
                                ]
                            ]
                        ]
                    ],

                    "individual" => [
                        "reference" => "Practitioner/10000000001",
                        "display" => "dr. Nama Dokter"
                    ]
                ]
            ],

            "period" => [
                "start" => "2026-09-24T09:00:00+07:00"
            ],

            "location" => [
                [
                    "location" => [
                        "reference" => "Location/ID_LOCATION_POLI",
                        "display" => "Poli Penyakit Dalam"
                    ]
                ]
            ],

            "statusHistory" => [
                [
                    "status" => "arrived",
                    "period" => [
                        "start" => "2026-09-24T09:00:00+07:00"
                    ]
                ]
            ],

            "serviceProvider" => [
                "reference" => "Organization/".$organizationId
            ]
        ];

        $response = Http::withToken($token)
            ->acceptJson()
            ->post(
                config('services.satusehat.fhir_url').'/Encounter',
                $payload
            );

        if (!$response->successful()) {
            return [
                'status' => false,
                'http_code' => $response->status(),
                'response' => $response->json()
            ];
        }

        return [
            'status' => true,
            'encounter_id' => $response->json('id'),
            'response' => $response->json()
        ];
    }
}
