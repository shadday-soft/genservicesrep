import { router, useForm } from "@inertiajs/vue3";
import GeneralService from "./GeneralService";
import { ref } from "vue";
import type { Actividad as ActividadType } from "@/types";
import { store, update, destroy, index } from "@/actions/App/Http/Controllers/ActividadController";
import { questionDeleteMessage } from '@/composables/Toast';
import axios from "axios";

export default class Actividad extends GeneralService {
    actividad = ref<ActividadType | null>(null);
    form = useForm({
        nombre: '',
        active: true as boolean,
    });

    constructor(actividad?: ActividadType | null) {
        super();
        if (actividad) {
            this.actividad.value = actividad;
            this.assignMatchingKeys(actividad, this.form);
        }
    }

    async submit(onSuccessCallback?: () => void) {
        if (this.actividad.value?.id) {
            return super.update(update(this.actividad.value.id), this.form, onSuccessCallback);
        }
        return super.store(store(), this.form, onSuccessCallback);
    }

    async delete(id: string) {
        questionDeleteMessage(destroy(id), 'Esta acción eliminará la actividad', 'Actividad');
    }

    async getActividades(): Promise<ActividadType[]> {
        try {
            const { data } = await axios.get(index().url);
            return data.actividads || [];
        } catch (error) {
            console.error("Error fetching actividades:", error);
            return [];
        }
    }
}
