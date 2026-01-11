<?php

use App\Models\SuspensionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suspension_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 500)->unique();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        if (app()->environment() !== 'testing') {
            $this->seedSuspensionTypesTable();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suspension_types');
    }

    protected function seedSuspensionTypesTable()
    {

        SuspensionType::create([
            'name' => 'Suspension por Mutuo Acuerdo',
            'description' => 'El empleado y el empleador estuvieron de acuerdo con una suspension temporal',
        ]);

        SuspensionType::create([
            'name' => 'Matermidad',
            'description' => 'Descanso por maternidad, segun el articulo 236',
        ]);

        SuspensionType::create([
            'name' => 'Obligaciones Legales',
            'description' => 'El empleado se encuentra cumpliendo obligaciones legales que lo imposibilitan temporalmente',
        ]);

        SuspensionType::create([
            'name' => 'Caso de Fuerza Mayor',
            'description' => 'Caso fortuito de fuerza mayor que imposibilite la continuacion de la faena laboral',
        ]);

        SuspensionType::create([
            'name' => 'Arresto o Prision Preventiva',
            'description' => 'Prisión preventiva del trabajador, seguida o no de libertad provisional hasta la fecha en que sea irrevocable la sentencia definitiva, siempre que lo absuelva o descargue o que lo condene únicamente a penas pecuniarias, sin perjuicio de lo previsto en el artículo 88 ordinal 18',
        ]);

        SuspensionType::create([
            'name' => 'Enfermedad Contagiosa',
            'description' => 'Enfermedad contagiosa del trabajador o cualquier otra que lo imposibilite temporalmente para el desempeño de sus labores',
        ]);

        SuspensionType::create([
            'name' => 'Accidente Laboral',
            'description' => 'Acidentes que ocurran al trabajador en las condiciones y circunstancias previstas y amparadas por la ley sobre Accidentes de Trabajo, cuando sólo le produzca la incapacidad temporal',
        ]);

        SuspensionType::create([
            'name' => 'Falta de Materia Prima',
            'description' => 'Falta o insuficiencia de materia prima siempre que no sea imputable al empleado',
        ]);

        SuspensionType::create([
            'name' => 'Falta de Fondos o Quiebre de Empresa',
            'description' => 'Falta de fondos para la continuación normal de los trabajos, si el empleador justifica plenamente la imposibilidad de obtenerlos',
        ]);

        SuspensionType::create([
            'name' => 'Excedente de Production',
            'description' => 'Exceso de producción con relación a la situación económica de la empresa y a las condiciones del mercado',
        ]);

        SuspensionType::create([
            'name' => 'Huelga Legal',
            'description' => 'Huelga y el paro calificados legale',
        ]);

        SuspensionType::create([
            'name' => 'Licencia Medica por Enfermedad',
            'description' => 'Licencia medica otorgada por un doctor calificado que indique alguna enfermedad que imposibilite o dificulte la capacidad del empleado de realizar sus labores',
        ]);

        SuspensionType::create([
            'name' => 'Matrimonio Legal',
            'description' => 'Celebración de matrimonio legal con acta matrimonial emitida por las autoridades (5 dias)',
        ]);

        SuspensionType::create([
            'name' => 'Fallecimiento Familiar',
            'description' => 'Fallecimiento de cualquiera de sus padres, abuelos, conyugue, padres o hijos (3 dias)',
        ]);

        SuspensionType::create([
            'name' => 'Nacimiento de Hijo',
            'description' => 'Para los padres, nacimiento de un hijo (2 dias)',
        ]);
    }
};
