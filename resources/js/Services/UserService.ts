import { router, useForm } from "@inertiajs/vue3";
import GeneralService from "./GeneralService";
import { ref } from "vue";
import type { User as UserType } from "@/types";
import { store, update, destroy, index} from "@/actions/App/Http/Controllers/Auth/UserController";
import { getErrorMessage, questionDeleteMessage } from '@/composables/Toast';

import axios from "axios";



export default class User extends GeneralService {

    async getUsers() {
        try {
            const { data } = await  axios.get(index().url);
            return data.users as UserType[];
        } catch (error) {
            getErrorMessage('Error al obtener los usuarios');
            return [];
        }
    }

}