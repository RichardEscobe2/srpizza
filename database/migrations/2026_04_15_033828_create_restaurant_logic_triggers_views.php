<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Vista de Insumos Críticos
        DB::statement("
            CREATE VIEW VistaInsumosCriticos AS 
            SELECT insumo_id, nombre, stock_actual, stock_minimo 
            FROM insumos 
            WHERE stock_actual <= stock_minimo
        ");

        // 2. Trigger para automatizar mermas
        DB::unprepared("
            CREATE TRIGGER trg_merma_restar_stock
            AFTER INSERT ON mermas FOR EACH ROW
            BEGIN
                -- Restar el stock del insumo mermado
                UPDATE insumos 
                SET stock_actual = stock_actual - NEW.cantidad
                WHERE insumo_id = NEW.insumo_id;
                
                -- Opcional: Si tienes tu tabla 'bitacora' ya creada, registra la acción
                -- INSERT INTO bitacora (usuario_id, accion, tabla_afectada, detalles, fecha)
                -- VALUES (NEW.usuario_id, 'MERMA_REGISTRADA', 'insumos', CONCAT('Merma: ', NEW.cantidad), NOW());
            END
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS VistaInsumosCriticos");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_merma_restar_stock");
    }
};