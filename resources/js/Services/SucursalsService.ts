import { router, useForm } from "@inertiajs/vue3";
import GeneralService from "./GeneralService";
import { ref } from "vue";
import type { Sucursal as SucursalType } from "@/types";
import { store, update, destroy, index} from "@/actions/App/Http/Controllers/SucursalController";
import { getErrorMessage, questionDeleteMessage } from '@/composables/Toast';
import { email } from "@/routes/password";
import Client from "./ClientService";
import axios from "axios";



export default class Sucursal extends GeneralService {
    sucursal = ref<SucursalType | null>(null);
    form = useForm({
        client_id: '',
        name: '',
        address: '',
        ciudad: '',
        phone_number: '',
        contact_name: '',
        email: '',
        image: '',
        latitude: null as number | null,
        longitude: null as number | null,
    });


    constructor(sucursal?: SucursalType | null) {
        super();
        if (sucursal) {
            this.sucursal.value = sucursal;
            this.assignMatchingKeys(sucursal, this.form);
        }
    }

    async getSucursals() {
        try {
            const { data } = await  axios.get(index().url);
            return data.sucursals as SucursalType[];
        } catch (error) {
            getErrorMessage('Error al obtener las sucursales');
            return [];
        }
    }

    


    async submit(onSuccessCallback?: () => void) {
        if (this.sucursal.value?.id) {
            return super.update(update(this.sucursal.value.id), this.form, onSuccessCallback);
        }
        return super.store(store(), this.form, onSuccessCallback);
    }

    async delete(id: string) {
        questionDeleteMessage(destroy(id), 'Esta acción eliminará la sucursal', 'Sucursal');
    }
}