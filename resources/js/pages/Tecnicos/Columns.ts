import type { Column } from "@/types";

export const columns: Column[] = [
    {
        field: "foto",
        header: "Foto",
        type: "image",
    },
    {
        field: "nombre_completo",
        header: "Nombre Completo",
        sortable: true,
        filter: true,
    },
    {
        field: "identificacion",
        header: "Identificación",
        sortable: true,
    },
    {
        field: "correo",
        header: "Correo",
        sortable: true,
    },
    {
        field: "eps",
        header: "EPS",
    },
    {
        field: "tipo_sangre",
        header: "Tipo Sangre",
    },
    {
        field: "tipo_contrato",
        header: "Tipo Contrato",
        tags: [
            { label: "Indefinido", value: "Indefinido", severity: "success" },
            { label: "Fijo", value: "Fijo", severity: "info" },
            { label: "Obra o labor", value: "Obra o labor", severity: "warn" },
            { label: "Prestación de servicios", value: "Prestación de servicios", severity: "secondary" },
        ],
    },
    {
        field: "fecha_inicio_contrato",
        header: "Fecha Inicio",
        type: "date",
        sortable: true,
    },
    
];
