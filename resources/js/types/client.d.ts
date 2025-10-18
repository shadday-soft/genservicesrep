export interface Client {
    id: string;
    user_id: number;
    enterprise_name: string;
    contact_name: string;
    email: string;
    phone_number?: string;
    nit?: string;
    created_at: string;
    updated_at: string;
}
