
import { Auth } from "@/types";
import { usePage } from "@inertiajs/vue3";

const COP = new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    minimumFractionDigits: 0,
});

export function mapSimpleArrayToArrayObject(array: string[]) {
    return array.map(item => ({ label: item, value: item }));
}

export function calculateTimeInHours(start:any, end: any) {
    if (!start || !end) return 0;
    const startDate = new Date(start);
    const endDate = new Date(end);
    const diffMs = endDate.getTime() - startDate.getTime();
    if (diffMs <= 0) return 0;
    const totalHours = Math.round((diffMs / 3600000) * 100) / 100;
    return totalHours;
}

export function validateRole(role: string) {
    return usePage().props.auth.user.role === role;
};

export function useCommonUtilities() {
    const autoTruncateString = (string = '') => {
        const maxLength = 20;
        return string.length > maxLength ? string.substring(0, maxLength) + '...' : string;
    };

    const byteSizeFormatter = (bytes: number) => {
        const k = 1024;
        const dm = 1;
        const sizeType = ['B', 'KB', 'MB', 'GB'];

        if (bytes === 0) {
            return `0 byte`;
        }

        const i = Math.floor(Math.log(bytes) / Math.log(k));
        const formattedSize = parseFloat((bytes / Math.pow(k, i)).toFixed(dm));

        return `${formattedSize} ${sizeType[i]}`;
    };

    const currencyFormat = (value: number) => {
        return COP.format(value);
    };

    const formatDate = (date: string) => {
        if (date == undefined || date == null) {
            return 'Sin definir';
        } else {
            return new Date(new Date(date).getTime() + new Date(date).getTimezoneOffset() * 60000).toLocaleString('es-CO', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                timeZone: 'America/Bogota',
            });
        }
    };

    const formatDateTime = (date: string) => {
        if (date == undefined || date == null) {
            return 'Sin definir';
        }
        return new Date(date).toLocaleString('es-CO', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
            timeZone: 'America/Bogota',
        });

    };


    const truncateString = (string = '', maxLength = 10) => {
        const truncatedString = string.length > maxLength ? string.substring(0, maxLength) + '...' : string;
        return truncatedString;
    };

    function esMovil() {
        const dispositivos = [/Android/i, /webOS/i, /iPhone/i, /iPad/i, /iPod/i, /BlackBerry/i, /Windows Phone/i];

        return dispositivos.some((dispositivo) => {
            return navigator.userAgent.match(dispositivo);
        });
    }

    return {
        autoTruncateString,
        byteSizeFormatter,
        currencyFormat,
        formatDate,
        esMovil,
        formatDateTime,
        truncateString,
    };
}
