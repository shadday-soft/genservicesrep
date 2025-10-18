
import { router } from "@inertiajs/vue3";
import { getSuccessMessage, getErrorMessage } from '@/composables/Toast';
import { ref } from "vue";


export default class GeneralService {



    url = ref<string | null>(null);

    assignMatchingKeys(source: { [key: string]: any }, target: { [key: string]: any }) {
        Object.keys(source).forEach((key: string) => {
            if (key in target) {
                target[key] = source[key];
            }
        });
    }

    store(route: any, data: any, onSuccessCallback?: () => void) {
        return data.post(route, {
            onSuccess: () => {
                getSuccessMessage("Guardado Exitosamente");
                if (onSuccessCallback) {
                    onSuccessCallback();
                }
            },
            onError: () => {
                getErrorMessage("Error al guardar");
            }
        });
    }
    update(route: any, data: any, onSuccessCallback?: () => void) {
        return data.put(route, {
            onSuccess: () => {
                getSuccessMessage("Actualizado Exitosamente");
                if (onSuccessCallback) {
                    onSuccessCallback();
                }
            },
            onError: () => {
                getErrorMessage("Error al actualizar");
            }
        });
    }
}