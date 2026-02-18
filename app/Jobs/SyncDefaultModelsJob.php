<?php

namespace App\Jobs;

use Illuminate\Foundation\Queue\Queueable;

class SyncDefaultModelsJob
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->getDefaultModels() as $model => $records) {
            foreach ($records as $record) {
                $firstKey = array_keys($record)[0];
                $checkArray = [
                    $firstKey => $record[$firstKey]
                ];

                 (new $model)->firstOrCreate(
                    $checkArray,
                    $record
                );
            }
        }
    }

    protected function getDefaultModels(): array
    {
        return [
            \App\Models\Site::class => [
                [
                    'name' => 'Ecco Headquarters Santiago',
                    'description' => 'Main Office',
                ],
                [
                    'name' => 'Ecco WFM Remote',
                    'description' => 'Work From Home',
                ],
            ],
            \App\Models\SuspensionType::class => [
                [
                    'name' => 'Suspension por Mutuo Acuerdo',
                    'description' => 'El empleado y el empleador estuvieron de acuerdo con una suspension temporal',
                ],
                [
                    'name' => 'Matermidad',
                    'description' => 'Descanso por maternidad, segun el articulo 236',
                ],
                [
                    'name' => 'Obligaciones Legales',
                    'description' => 'El empleado se encuentra cumpliendo obligaciones legales que lo imposibilitan temporalmente',
                ],
                [
                    'name' => 'Caso de Fuerza Mayor',
                    'description' => 'Caso fortuito de fuerza mayor que imposibilite la continuacion de la faena laboral',
                ],
                [
                    'name' => 'Arresto o Prision Preventiva',
                    'description' => 'Prisión preventiva del trabajador, seguida o no de libertad provisional hasta la fecha en que sea irrevocable la sentencia definitiva, siempre que lo absuelva o descargue o que lo condene únicamente a penas pecuniarias, sin perjuicio de lo previsto en el artículo 88 ordinal 18',
                ],
                [
                    'name' => 'Enfermedad Contagiosa',
                    'description' => 'Enfermedad contagiosa del trabajador o cualquier otra que lo imposibilite temporalmente para el desempeño de sus labores',
                ],
                [
                    'name' => 'Accidente Laboral',
                    'description' => 'Acidentes que ocurran al trabajador en las condiciones y circunstancias previstas y amparadas por la ley sobre Accidentes de Trabajo, cuando sólo le produzca la incapacidad temporal',
                ],
                [
                    'name' => 'Falta de Materia Prima',
                    'description' => 'Falta o insuficiencia de materia prima siempre que no sea imputable al empleado',
                ],
                [
                    'name' => 'Falta de Fondos o Quiebre de Empresa',
                    'description' => 'Falta de fondos para la continuación normal de los trabajos, si el empleador justifica plenamente la imposibilidad de obtenerlos',
                ],
                [
                    'name' => 'Excedente de Production',
                    'description' => 'Exceso de producción con relación a la situación económica de la empresa y a las condiciones del mercado',
                ],
                [
                    'name' => 'Huelga Legal',
                    'description' => 'Huelga y el paro calificados legale',
                ],
                [
                    'name' => 'Licencia Medica por Enfermedad',
                    'description' => 'Licencia medica otorgada por un doctor calificado que indique alguna enfermedad que imposibilite o dificulte la capacidad del empleado de realizar sus labores',
                ],
                [
                    'name' => 'Matrimonio Legal',
                    'description' => 'Celebración de matrimonio legal con acta matrimonial emitida por las autoridades (5 dias)',
                ],
                [
                    'name' => 'Fallecimiento Familiar',
                    'description' => 'Fallecimiento de cualquiera de sus padres, abuelos, conyugue, padres o hijos (3 dias)',
                ],
                [
                    'name' => 'Nacimiento de Hijo',
                    'description' => 'Para los padres, nacimiento de un hijo (2 dias)',
                ],
            ],
            \App\Models\Source::class => [
                [
                    'name' => 'Data Entry',
                ],
                [
                    'name' => 'Chat',
                ],
                [
                    'name' => 'Email',
                ],
                [
                    'name' => 'Escalation',
                ],
                [
                    'name' => 'QA Review',
                ],
                [
                    'name' => 'Resubmission',
                ],
                [
                    'name' => 'Downtime',
                ],
                [
                    'name' => 'Inbound Calls',
                ],
                [
                    'name' => 'Outbound Calls',
                ],
            ],
            \App\Models\DowntimeReason::class => [
                [
                    'name' => 'Backoffice Work',
                    'description' => 'Tasks related to backoffice work that require downtime',
                ],
                [
                    'name' => 'Assisting Supervisor Tasks',
                    'description' => 'Helping the supervisor with various tasks that require downtime',
                ],
                [
                    'name' => 'Computer Issues',
                    'description' => 'Technical problems with the computer that necessitate downtime',
                ],
                [
                    'name' => 'Early Release',
                    'description' => 'Agents released early from their shift',
                ],
                [
                    'name' => 'Electricity Problems',
                    'description' => 'Issues related to electricity that require downtime',
                ],
                [
                    'name' => 'Inductions',
                    'description' => 'HR or company inductions that require downtime',
                ],
                [
                    'name' => 'Lactation Breaks',
                    'description' => 'Breaks for lactating mothers that require downtime',
                ],
                [
                    'name' => 'Team Meetings',
                    'description' => 'Meetings held by the team that require downtime',
                ],
                [
                    'name' => 'Waiting for Credentials / Login Issues',
                    'description' => 'Issues related to waiting for credentials or login problems that require downtime',
                ],
                [
                    'name' => 'Internet Connectivity Issues',
                    'description' => 'Issues related to internet connectivity that require downtime',
                ],
                [
                    'name' => 'Waiting for Leads or Campaigns Assigments',
                    'description' => 'Waiting for leads or campaign assignments that require downtime',
                ],
                [
                    'name' => 'On The Job Training',
                    'description' => 'Training sessions that occur during work hours and require downtime',
                ],
                [
                    'name' => 'QA Coaching and Feedback',
                    'description' => 'Coaching and feedback sessions related to quality assurance that require downtime',
                ],
                [
                    'name' => 'Supervisor One on One Coaching',
                    'description' => 'Meeting with supervisor for coaching that requires downtime',
                ],
                [
                    'name' => 'Training for New Hires',
                    'description' => 'Training sessions for new hires that require downtime',
                ],
            ],
            \App\Models\Disposition::class => [
                [
                    'name' => ' Hang-up During Presentation',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Answering Machine',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Answering Machine - Live',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Answering Machine or VoiceMail',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Bad Number',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Busy',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Busy Signal',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Call Back',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Call Back Later',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Complete',
                    'sales' => 1.00,
                    'description' => ''
                ],
                [
                    'name' => 'Complete Replacement',
                    'sales' => 1.00,
                    'description' => ''
                ],
                [
                    'name' => 'Complete With Referral',
                    'sales' => 2.00,
                    'description' => ''
                ],
                [
                    'name' => 'Completed Survey',
                    'sales' => 1.00,
                    'description' => ''
                ],
                [
                    'name' => 'Contact',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Customer already has - ordered the product - has warranty',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Customer Requested Call Back',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Deceased',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Decision Maker Not Available',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Decision Maker Unavailable',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Disconnect',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Disconnected Phone (Agent)',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Do Not Call',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Do Not Call - Call Result',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Do Not Call - Do Not Solict',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Do Not Call NF',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Dual Dispo for Two Pubs',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Early Hangup',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Elderly or Fixed Income',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Immediate Hang-up',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Initial Refusal',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Language Barrier',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Language barrier - customer speaks only Spanish',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Language barrier-speaks language other than Spanish or English',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Machine Hangup',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Never Reached/Ring No Answer',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'New Name Accept, Keep Old',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'New Name Accept, Remove Old',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'New Name Refused, Remove Old',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'No Answer',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'No Answer / Answering Machine',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'No English',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'No longer w company No Replacement',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Non-Qualified Company/Disqual at company question',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Not Interested in Product or Offer',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Original Name Accept, Engagement Made',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Original Name Refused',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Patch',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Phone Busy ',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Problem with Client',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Refused',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Respondent Not Available/Generic callback',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Ring No Answer',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'RNA Ring No Answer',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Sale',
                    'sales' => 1.00,
                    'description' => ''
                ],
                [
                    'name' => 'Satisfied with Current Provider',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Service too expensive',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Take my number off the list',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Transferred to 3rd Party',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Vendor Do Not Call - Prior to Calling',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Voicemail',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Wrong - Bad Number',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Wrong Number',
                    'sales' => 0,
                    'description' => ''
                ],
                [
                    'name' => 'Non-Qualified Respondent/Disqual at person quest',
                    'sales' => 0,
                    'description' => ''
                ]
            ],
        ];
    }
}
