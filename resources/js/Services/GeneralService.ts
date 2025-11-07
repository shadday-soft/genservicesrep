
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
        // Detectar si hay archivos en los datos
        const hasFiles = Object.values(data.data()).some((value: any) => 
            value instanceof File || value instanceof FileList || value instanceof Blob
        );

        // Si hay archivos, forzar POST con _method
        if (hasFiles) {
            return data.transform((data: any) => ({
                ...data,
                _method: 'PUT'
            })).post(route, {
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

        // Sin archivos, usar PUT normal
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