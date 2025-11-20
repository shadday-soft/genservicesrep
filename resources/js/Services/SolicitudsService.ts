import { router, useForm } from "@inertiajs/vue3";
import GeneralService from "./GeneralService";
import { ref } from "vue";
import type { Solicitud as SolicitudType } from "@/types";
import { store, update, destroy, index} from "@/actions/App/Http/Controllers/SolicitudController";
import { getErrorMessage, questionDeleteMessage } from '@/composables/Toast';
import axios from "axios";



export default class Solicitud extends GeneralService {
    solicitud = ref<SolicitudType | null>(null);
    form = useForm({
        client_id: '',
        sucursal_id: '',
        equipo_id: '',
        user_id: '',
        numero_orden: '',
        prioridad: 'Normal' as 'Normal' | 'Intermedio' | 'Urgente',
        detalles: '',
        estado: 'Nueva' as 'Nueva' | 'Proceso' | 'Revisión' | 'Finalizada' | 'Anulada' | 'Programada',
        actividad: '',
        tipo_mantenimiento: '' as string,
        mantenimiento_id: '',
        fecha_mantenimiento: '',
        telefono: '',
        mail: '',
        ubicacion: '',
        quien_solicita: '',
        fecha_programada: new Date(),
        orden_trabajo: null as File | null,
    });


    constructor(solicitud?: SolicitudType | null) {
        super();
        if (solicitud) {
            this.solicitud.value = solicitud;
            this.assignMatchingKeys(solicitud, this.form);
            this.form.fecha_programada = solicitud.fecha_programada ? new Date(solicitud.fecha_programada) : new Date();
        }
    }

    async getSolicituds() {
        try {
            const { data } = await  axios.get(index().url);
            return data.solicituds as SolicitudType[];
        } catch (error) {
            getErrorMessage('Error al obtener las solicitudes');
            return [];
        }
    }

    


    async submit(onSuccessCallback?: () => void) {
        if (this.solicitud.value?.id) {
            return super.update(update(this.solicitud.value.id), this.form, onSuccessCallback);
        }
        return super.store(store(), this.form, onSuccessCallback);
    }

    async cancelar(id: string, razonCancelacion: string, onSuccessCallback?: () => void) {
        try {
            const response = await axios.post(`/solicituds/${id}/cancelar`, {
                razon_cancelacion: razonCancelacion
            });
            
            if (response.data) {
                router.reload({ only: ['solicituds'] });
                if (onSuccessCallback) {
                    onSuccessCallback();
                }
            }
        } catch (error) {
            getErrorMessage('Error al cancelar la solicitud');
        }
    }
}
