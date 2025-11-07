import { router, useForm } from "@inertiajs/vue3";
import GeneralService from "./GeneralService";
import { ref } from "vue";
import type { Tecnico } from "@/types";
import { store, update, index } from "@/actions/App/Http/Controllers/TecnicoController";
import { destroy } from "@/routes/tecnicos";
import { questionDeleteMessage } from '@/composables/Toast';
import axios from "axios";

export default class TecnicoService extends GeneralService {
    tecnico = ref<Tecnico | null>(null);
    form = useForm({
        foto: null as File | null,
        identificacion: '',
        correo: '',
        nombre_completo: '',
        persona_contacto: '',
        tipo_sangre: '',
        eps: '',
        fecha_nacimiento: new Date(),
        fecha_inicio_contrato: new Date(),
        fecha_fin_contrato: new Date(),
        tipo_contrato: 'Indefinido' as 'Indefinido' | 'Fijo' | 'Obra o labor' | 'Prestación de servicios',
    });

    constructor(tecnico?: Tecnico | null) {
        super();
        this.url.value = 'tecnicos';
        if (tecnico) {
            this.tecnico.value = tecnico;
            this.assignMatchingKeys(tecnico, this.form);
            this.form.fecha_nacimiento = tecnico.fecha_nacimiento ? new Date(tecnico.fecha_nacimiento) : new Date();
            this.form.fecha_inicio_contrato = tecnico.fecha_inicio_contrato ? new Date(tecnico.fecha_inicio_contrato) : new Date();
            this.form.fecha_fin_contrato = tecnico.fecha_fin_contrato ? new Date(tecnico.fecha_fin_contrato) : new Date();
        }
    }

    async getTecnicos() {
        try {
            const { data } = await axios.get(index().url);
            return data.tecnicos;
        } catch (error) {
            console.error("Error fetching tecnicos:", error);
            return [];
        }
    }

    async submit(onSuccessCallback?: () => void) {
        if (this.tecnico.value?.id) {
            return super.update(update(this.tecnico.value.id), this.form, onSuccessCallback);
        }
        return super.store(store(), this.form, onSuccessCallback);
    }

    async delete(id: string) {
        questionDeleteMessage(destroy(id), 'Esta acción eliminará el técnico', 'Técnico');
    }
}
