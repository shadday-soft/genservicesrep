<script setup lang="ts">
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import { Form, Head, Link, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type Tecnico } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
    tecnico?: Tecnico | null;
}

const props = defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: edit().url,
    },
];

const page = usePage();
const user = page.props.auth.user;
const isTecnico = user.role === 'Tecnico';
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    :title="isTecnico ? 'Información del Técnico' : 'Información del Perfil'"
                    :description="isTecnico ? 'Actualiza tu información personal y de contacto' : 'Actualiza tu nombre y dirección de correo electrónico'"
                />

                <Form
                    v-bind="ProfileController.update.form()"
                    class="space-y-6"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <!-- Campos para técnicos -->
                    <template v-if="isTecnico && props.tecnico">
                        <div class="grid gap-2">
                            <Label for="nombre_completo">Nombre Completo</Label>
                            <Input
                                id="nombre_completo"
                                class="mt-1 block w-full"
                                name="nombre_completo"
                                :default-value="props.tecnico.nombre_completo"
                                required
                                autocomplete="name"
                                placeholder="Nombre completo"
                            />
                            <InputError class="mt-2" :message="errors.nombre_completo" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="identificacion">Identificación</Label>
                            <Input
                                id="identificacion"
                                class="mt-1 block w-full"
                                name="identificacion"
                                :default-value="props.tecnico.identificacion"
                                required
                                placeholder="Número de identificación"
                            />
                            <InputError class="mt-2" :message="errors.identificacion" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="correo">Correo Electrónico</Label>
                            <Input
                                id="correo"
                                type="email"
                                class="mt-1 block w-full"
                                name="correo"
                                :default-value="props.tecnico.correo"
                                required
                                autocomplete="email"
                                placeholder="Correo electrónico"
                            />
                            <InputError class="mt-2" :message="errors.correo" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="persona_contacto">Persona de Contacto</Label>
                            <Input
                                id="persona_contacto"
                                class="mt-1 block w-full"
                                name="persona_contacto"
                                :default-value="props.tecnico.persona_contacto ?? ''"
                                placeholder="Nombre de la persona de contacto"
                            />
                            <InputError class="mt-2" :message="errors.persona_contacto" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="telefono_contacto">Teléfono de Contacto</Label>
                            <Input
                                id="telefono_contacto"
                                type="tel"
                                class="mt-1 block w-full"
                                name="telefono_contacto"
                                :default-value="props.tecnico.telefono_contacto ?? ''"
                                placeholder="Número de teléfono"
                            />
                            <InputError class="mt-2" :message="errors.telefono_contacto" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="direccion_contacto">Dirección de Contacto</Label>
                            <Input
                                id="direccion_contacto"
                                class="mt-1 block w-full"
                                name="direccion_contacto"
                                :default-value="props.tecnico.direccion_contacto ?? ''"
                                placeholder="Dirección completa"
                            />
                            <InputError class="mt-2" :message="errors.direccion_contacto" />
                        </div>
                    </template>

                    <!-- Campos para usuarios regulares -->
                    <template v-else>
                        <div class="grid gap-2">
                            <Label for="name">Nombre</Label>
                            <Input
                                id="name"
                                class="mt-1 block w-full"
                                name="name"
                                :default-value="user.name"
                                required
                                autocomplete="name"
                                placeholder="Nombre completo"
                            />
                            <InputError class="mt-2" :message="errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="email">Correo Electrónico</Label>
                            <Input
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                name="email"
                                :default-value="user.email"
                                required
                                autocomplete="username"
                                placeholder="Correo electrónico"
                            />
                            <InputError class="mt-2" :message="errors.email" />
                        </div>
                    </template>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link
                                :href="send()"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div
                            v-if="status === 'verification-link-sent'"
                            class="mt-2 text-sm font-medium text-green-600"
                        >
                            A new verification link has been sent to your email
                            address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button
                            :disabled="processing"
                            data-test="update-profile-button"
                            >Guardar</Button
                        >

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="recentlySuccessful"
                                class="text-sm text-neutral-600"
                            >
                                Guardado.
                            </p>
                        </Transition>
                    </div>
                </Form>
            </div>

            <!-- <DeleteUser /> -->
        </SettingsLayout>
    </AppLayout>
</template>
