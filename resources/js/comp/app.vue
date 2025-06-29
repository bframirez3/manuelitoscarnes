<template>
    <v-app class="app-background">
        <v-container class="d-flex justify-center align-center" style="min-height: 100vh;">
            <v-card elevation="4" class="pa-6"
                style="border-radius: 16px; width: 100%; max-width: 400px; background: white;">
                <!-- Encabezado con ícono centrado y texto debajo -->
                <v-card-title class="d-flex flex-column align-center justify-center mb-4">
                    <v-icon size="50" color="primary" class="mb-2">mdi-apple-finder</v-icon>
                    <span class="text-h5 font-weight-bold text-primary text-center">Inicio de Sesión</span>
                </v-card-title>
                <!-- Formulario -->
                <v-card-text>
                    <v-form>
                        <!-- Campo de correo electrónico -->
                        <v-text-field v-model="form.email" label="Correo electrónico" type="email"
                            placeholder="ejemplo@correo.com" prepend-inner-icon="mdi-email-outline" outlined dense
                            rounded color="primary" :rules="[rules.required, rules.email]" class="mb-4"></v-text-field>
                        <!-- Campo de contraseña -->
                        <v-text-field v-model="form.password" label="Contraseña" type="password" placeholder="••••••••"
                            prepend-inner-icon="mdi-lock-outline" outlined dense rounded color="primary"
                            :rules="[rules.required]" class="mb-4"></v-text-field>
                        <!-- Mensaje de error (opcional) -->
                        <v-alert v-if="error" type="error" class="mb-4" dense border="left">
                            {{ error }}
                        </v-alert>
                        <!-- Botón de ingresar -->
                        <v-btn color="primary" block large elevation="2" rounded @click="login">
                            Ingresar
                        </v-btn>
                    </v-form>
                </v-card-text>
                <!-- Pie con link de ayuda 
                <v-card-actions class="justify-center mt-4">
                    <v-btn text color="primary" small @click="forgotPassword">
                        ¿Olvidaste tu contraseña?
                    </v-btn>
                </v-card-actions>-->
            </v-card>
            <div class="text-center ma-2">
                <v-snackbar v-model="snackbarSuccess" :timeout="timeout" bottom color="success" shaped>
                    {{ text }}

                </v-snackbar>
                <v-snackbar v-model="snackbarError" :timeout="timeout" bottom color="error" shaped>
                    {{ text }}

                </v-snackbar>
                <v-snackbar v-model="snackbarWarning" :timeout="timeout" bottom color="warning" shaped>
                    {{ text }}

                </v-snackbar>
            </div>
        </v-container>
    </v-app>
</template>

<script>
import { useRouter } from 'vue-router';
export default {
    data: () => ({
        URI: 'api/spa/login',
        error: null,
        text: '',
        mensajeReturn: '',
        snackbarSuccess: false,
        snackbarError: false,
        snackbarWarning: false,
        form: {
            email: null,
            password: null,
        },
        rules: [
            v => !!v || 'Campo es requerido',
            v => (v && v.length <= 25) || 'El campo debe tener menos de 25 caracteres.',
        ],
    }),
    methods: {
        activarMensaje(colors, mensaje) {
            this.text = mensaje;
            if (colors === 'error') {
                this.snackbarError = true;
            } else if (colors === 'success') {
                this.snackbarSuccess = true;
            } else if (colors === 'warning') {
                this.snackbarWarning = true;
            }
        },
        async login() {
            if (!this.form.email || !this.form.password) {
                this.activarMensaje('error', 'Por favor, completa todos los campos.');
            } else {
                await axios.post(this.URI, this.form).then((request) => {
                    if (request.data.estado == 400) {
                        this.mensajeReturn = request.data.mensajeError;
                        this.activarMensaje('error', this.mensajeReturn);
                    }
                    if (request.data.estado == 200) {
                        this.$router.push({ name: 'container' });
                        setTimeout(() => {
                            window.location.reload();  // Recarga la página después de 5 segundos
                        }, 1000);
                    }
                }, (err) => {
                    console.log(err);
                });
            }
        },
        forgotPassword() {
            alert("Redirigiendo a la página de recuperación de contraseña...");
        },
    },
};
</script>

<style scoped>
/* Fondo degradado suave */
.app-background {
    background: linear-gradient(135deg, #74b9ff, #a29bfe);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Color del texto */
.text-primary {
    color: #1976d2 !important;
}

/* Estilo para botón de texto */
.v-btn--text {
    font-size: 0.9rem;
    text-decoration: underline;
}
</style>