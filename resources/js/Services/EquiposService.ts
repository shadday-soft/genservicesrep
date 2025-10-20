import { router, useForm } from "@inertiajs/vue3";
import GeneralService from "./GeneralService";
import { ref } from "vue";
import { store, update, destroy, index } from "@/actions/App/Http/Controllers/EquipoController";
import { getSuccessMessage, getErrorMessage, questionDeleteMessage } from '@/composables/Toast';
import axios from "axios";

export default class Equipo extends GeneralService {
    equipo = ref<import('@/types').Equipo | null>(null);
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

        // Insumos
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
    });

    constructor(equipo?: import('@/types').Equipo | null) {
        super();
        if (equipo) {
            this.equipo.value = equipo;
            this.assignMatchingKeys(equipo, this.form);
        }
    }

    async getEquipos() {
        try {
            const { data } = await axios.get(index().url);
            return data.equipos as import('@/types').Equipo[];
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
