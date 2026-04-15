<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
   public function up(): void
{
    // 1. Crear o Reemplazar la Vista
    DB::statement("
        CREATE OR REPLACE VIEW VistaInsumosCriticos AS 
        SELECT insumo_id, nombre, stock_actual, stock_minimo 
        FROM insumos 
        WHERE stock_actual <= stock_minimo
    ");

    // 2. Borrar el trigger si existe y volver a crearlo
    DB::unprepared("DROP TRIGGER IF EXISTS trg_merma_restar_stock");
    DB::unprepared("
        CREATE TRIGGER trg_merma_restar_stock
        AFTER INSERT ON mermas FOR EACH ROW
        BEGIN
            UPDATE insumos 
            SET stock_actual = stock_actual - NEW.cantidad
            WHERE insumo_id = NEW.insumo_id;
        END
    ");
}

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS VistaInsumosCriticos");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_merma_restar_stock");
    }
};