import { router, useForm } from "@inertiajs/vue3";
import GeneralService from "./GeneralService";
import { ref } from "vue";
import type { Client as ClientType } from "@/types/client";
import { store, update, index } from "@/actions/App/Http/Controllers/ClientController";
import { destroy } from "@/routes/clients";
import { getSuccessMessage, getErrorMessage, questionDeleteMessage } from '@/composables/Toast';
import axios from "axios";
import Sucursal from "./SucursalsService";



export default class Client extends GeneralService {
    client = ref<ClientType | null>(null);
    form = useForm({
        enterprise_name: '',
        contact_name: '',
        email: '',
        phone_number: '',
        nit: 0,
    });


    constructor(client?: ClientType | null) {
        super();
        this.url.value = 'clients';
        if (client) {
            this.client.value = client;
            this.assignMatchingKeys(client, this.form);
        }
    }

    async getClients() {
        try {
            const { data } = await axios.get(index().url);
            return data.clients;
        } catch (error) {
            console.error("Error fetching clients:", error);
            return [];
        }
    }

    getSucursals(id: string) {
        const sucursalService = new Sucursal();

        return sucursalService.getSucursals().then(sucursals => {
            return sucursals.filter(sucursal => sucursal.client_id === id);
        });

    }

    async submit(onSuccessCallback?: () => void) {
        if (this.client.value?.id) {
            return super.update(update(this.client.value.id), this.form, onSuccessCallback);
        }
        return super.store(store(), this.form, onSuccessCallback);
    }

    async delete(id: string) {
        questionDeleteMessage(destroy(id), 'Esta acción eliminará el cliente', 'Cliente');
    }
}