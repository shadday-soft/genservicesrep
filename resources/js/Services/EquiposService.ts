import { router, useForm } from "@inertiajs/vue3";
import GeneralService from "./GeneralService";
import { ref } from "vue";
import { store, update, destroy, index } from "@/actions/App/Http/Controllers/EquipoController";
import { getSuccessMessage, getErrorMessage, questionDeleteMessage } from '@/composables/Toast';
import axios from "axios";
import type { Equipo as EquipoType } from "@/types";
import holidaysColombia from 'festivos-colombianos';

export default class Equipo extends GeneralService {
    equipo = ref<EquipoType | null>(null);
    form = useForm({
        client_id: '',
        sucursal_id: '',
        nombre_equipo: '',
        detalles: '',
        tipo_equipo: 'Planta Eléctrica',

        // Planta Eléctrica
        potencia: '',
        modelo_equipo: '',
        modelo_motor: '',
        tension_operacion: '',
        serie_equipo: '',
        serie_motor: '',
        marca_generador: '',
        horometro: '',
        marca_motor: '',

        // Tablero
        tablero_tipo: '',
        tablero_tension_operacion: '',
        tablero_tipo_aplicacion: '',
        tablero_fabricante: '',
        tablero_corriente_nominal: '',
        tablero_elemento_maniobra: '',
        tablero_controlador: '',

        filtro_aire_cantidad: 0,
        filtro_aire_referencia: '',
        filtro_aceite_cantidad: 0,
        filtro_aceite_referencia: '',
        filtro_combustible_cantidad: 0,
        filtro_combustible_referencia: '',
        filtro_separador_cantidad: 0,
        filtro_separador_referencia: '',
        filtro_agua_cantidad: 0,
        filtro_agua_referencia: '',
        filtro_aceite_2_cantidad: 0,
        filtro_aceite_2_referencia: '',
        refrigerante_cantidad: 0,
        refrigerante_referencia: '',

        // Programación de Mantenimientos (solo para Tableros)
        periodicidad: '',
        fecha_primer_mantenimiento: new Date(),
        proximas_fechas_mantenimiento: [] as Date[],
    });

    constructor(equipo?: import('@/types').Equipo | null) {
        super();
        if (equipo) {
            this.equipo.value = equipo;
            
            this.assignMatchingKeys(equipo, this.form);
            this.form.fecha_primer_mantenimiento = equipo.fecha_primer_mantenimiento ? new Date(equipo.fecha_primer_mantenimiento) : new Date();
        }
    }

    /**
     * Verifica si una fecha es día hábil (no sábado, domingo ni festivo)
     */
    esDiaHabil(fecha: Date): boolean {
        const diaSemana = fecha.getDay();
        
        // Verificar si es sábado (6) o domingo (0)
        if (diaSemana === 0 || diaSemana === 6) {
            return false;
        }
        
        try {
            // Verificar si es festivo en Colombia
            const festivos = holidaysColombia(fecha.getFullYear());
            const fechaStr = fecha.toISOString().split('T')[0];
            
            const esFestivo = festivos.some((festivo: any) => {
                const festivoDate = new Date(festivo.holiday);
                const festivoStr = festivoDate.toISOString().split('T')[0];
                return festivoStr === fechaStr;
            });
            
            return !esFestivo;
        } catch (error) {
            console.error('Error al verificar festivos:', error);
            // Si hay error, solo validar fin de semana
            return true;
        }
    }

    /**
     * Ajusta una fecha al siguiente día hábil si cae en fin de semana o festivo
     */
    ajustarASiguienteDiaHabil(fecha: Date): Date {
        const nuevaFecha = new Date(fecha);
        
        while (!this.esDiaHabil(nuevaFecha)) {
            nuevaFecha.setDate(nuevaFecha.getDate() + 1);
        }
        
        return nuevaFecha;
    }

    /**
     * Calcula las próximas fechas de mantenimiento según la periodicidad
     */
    calcularProximasMantenimientos(): Date[] {
        if (!this.form.fecha_primer_mantenimiento || !this.form.periodicidad) {
            return [];
        }
        
        const fechas: Date[] = [];
        const fechaInicialDate = new Date(this.form.fecha_primer_mantenimiento);
        const fechaLimite = new Date(fechaInicialDate);
        fechaLimite.setFullYear(fechaLimite.getFullYear() + 1); // Máximo un año
        
        // Ajustar la fecha inicial si cae en día no hábil
        const primeraFecha = this.ajustarASiguienteDiaHabil(fechaInicialDate);
        fechas.push(new Date(primeraFecha));
        
        // Determinar incremento según periodicidad
        let incremento = 0;
        switch (this.form.periodicidad) {
            case 'Semanal':
                incremento = 7;
                break;
            case 'Mensual':
                incremento = 31;
                break;
            case 'Trimestral':
                incremento = 365 / 4 + 1;
                break;
            case 'Semestral':
                incremento = 365 / 2 + 1;
                break;
            case 'Anual':
                incremento = 365;
                break;
            default:
                return fechas;
        }
        
        let fechaActual = new Date(primeraFecha);
        // fechas.push(new Date(fechaActual));
        // Calcular fechas hasta completar un año
        while (true) {
            // Agregar los meses correspondientes
            fechaActual = new Date(fechaActual);
            fechaActual.setDate(fechaActual.getDate() + incremento);
            
            // Verificar si excede el límite de un año
            if (fechaActual >= fechaLimite) {
                break;
            }
            
            // Ajustar a día hábil
            const fechaAjustada = this.ajustarASiguienteDiaHabil(fechaActual);
            fechas.push(new Date(fechaAjustada));
            fechaActual = fechaAjustada;
        }
        
        return fechas;
    }

    /**
     * Actualiza las fechas de mantenimiento en el formulario
     */
    actualizarFechasMantenimiento() {
        const fechas = this.calcularProximasMantenimientos();
        this.form.proximas_fechas_mantenimiento = fechas;
    }

    async getEquipos(sucursal_id: string) {
        try {
            const { data } = await axios.get(index().url);
            return data.equipos.filter((equipo: EquipoType) => equipo.sucursal_id === sucursal_id) as EquipoType[];
        } catch (error) {
            getErrorMessage('Error al obtener los equipos');
            return [];
        }
    }

    async submit(onSuccessCallback?: () => void) {
        if (this.equipo.value?.id) {
            return super.update(update(this.equipo.value.id), this.form, onSuccessCallback);
        }
        return super.store(store(), this.form, onSuccessCallback);
    }

    async delete(id: string) {
        questionDeleteMessage(destroy(id), 'Esta acción eliminará el equipo', 'Equipo');
    }
}
