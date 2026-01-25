<?php

use App\Jobs\SyncDefaultModelsJob;
use App\Models\Source;
use App\Models\SuspensionType;
use Illuminate\Support\Facades\Queue;

it('runs without error the job is not pushed to the queue', function () {
    Queue::fake([
        SyncDefaultModelsJob::class,
    ]);

    $command = $this->artisan('dainsys:sync-default-models-seed');

    $command->execute();

    Queue::assertNotPushed(SyncDefaultModelsJob::class);

    $command->assertExitCode(0);
});

it('sync default models seeds', function () {
    $defaultModels = [
        SuspensionType::class => [
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
        Source::class => [
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
    ];

    $this->artisan('dainsys:sync-default-models-seed');

    foreach ($defaultModels as $model => $records) {
        foreach ($records as $record) {
            $this->assertDatabaseHas($model, $record);
        }
    }
});
