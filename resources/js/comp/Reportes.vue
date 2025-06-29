<template>
    <v-app>
        <v-container fluid>
            <v-card flat>
                <v-card-title class="d-flex align-center pe-2">
                    <v-icon icon="mdi-chart-areaspline"></v-icon> &nbsp;
                    REPORTE
                    <v-spacer></v-spacer>
                </v-card-title>

                <br />
                <v-form ref="formpersonal" v-model="validactivos" lazy-validation>
                    <v-row>
                        <v-col cols="12" sm="3" md="3">
                            <v-select v-model="form.producto" :items="this.itemselectProductos" item-title="nombre"
                                item-value="_id" prepend-icon="mdi-cow" label="Producto" variant="outlined"></v-select>
                        </v-col>
                        <v-col cols="12" sm="3" md="3">
                            <v-select v-model="form.tipo" :items="itemsTipoInventario"
                                prepend-icon="mdi-folder-multiple-outline" label="Tipo inventario"
                                variant="outlined"></v-select>
                        </v-col>

                        <v-col cols="12" sm="3" md="3">
                            <v-date-input v-model="form.fechaInicio" label="Fecha Inicio"
                                variant="outlined"></v-date-input>
                        </v-col>
                        <v-col cols="12" sm="3" md="3">
                            <v-date-input v-model="form.fechaFin" label="Fecha Fin" variant="outlined"></v-date-input>
                        </v-col>
                    </v-row>

                </v-form>
                <v-row>
                    <v-col cols="12" sm="12" md="12">
                        <v-card-actions>
                            <v-btn dark color="primary" @click="consultar" :disabled="!validactivos" variant="outlined">
                                Buscar
                            </v-btn>
                            <v-divider class="mx-4" inset vertical></v-divider>
                            <v-spacer></v-spacer>
                            <download-excel :data="this.cc" :fields="json_fields" v-if="this.cc.length > 10000">
                                <v-btn dark color="success">
                                    Descargar Excel
                                </v-btn>
                            </download-excel>
                        </v-card-actions>


                    </v-col>


                </v-row>
                <v-divider></v-divider>
                <v-data-table v-model:search="buscador" :filter-keys="['nombre']" :items="cc">
                    <template v-slot:header._id>
                        <div>Id transacción</div>
                    </template>
                    <template v-slot:header.entrada>
                        <div>Entrada / Salida</div>
                    </template>
                    <template v-slot:header.updated_at>
                        <div>Fecha registro</div>
                    </template>
                    <!-- Personalizar las filas -->
                    <template v-slot:item.updated_at="{ item }">
                        <!-- Mostrar solo la fecha (por ejemplo, YYYY-MM-DD) -->
                        {{ item.updated_at.substring(0, 10) }}
                    </template>
                </v-data-table>
            </v-card>
            <v-card class="mx-auto pa-4" max-width="100%">




            </v-card>

            <v-dialog v-model="dialogConfirmar" max-width="660px">
                <v-card>
                    <v-card-title class="headline text-center">Está seguro que desea replicar los Centros De Costo al
                        SGW?
                    </v-card-title>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" dark @click="closeDelete">Cancelar</v-btn>
                        <v-btn color="success" dark @click="guardarActivo">Sí</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>

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
import { VDateInput } from 'vuetify/labs/VDateInput'
export default {
    components: {
        VDateInput,
    },
    data: () => ({
        timeout: 8000,
        validactivos: true,
        snackbarSuccess: false,
        snackbarError: false,
        snackbarWarning: false,
        dialogConfirmar: false,
        dialog: false,
        text: '',
        URI: 'api/spa/reporte',
        selectRules: [
            v => !!v || 'Campo es requerido',
        ],
        form: {
            producto: null,
            tipo: '',
            fechaInicio: null,
            fechaFin: null,
        },
        cc: [],
        restaurantes: [],
        itemselectProductos: [],
        opcionselect: {
            restaurante: '',
            estado: '',
        },
        itemsTipoInventario: ['Ingreso de inventario', 'Salida de inventario'],
        estados: [
            { Cod_Estado: '0', valor: 'INACTIVO' },
            { Cod_Estado: '1', valor: 'ACTIVO' },
            { Cod_Estado: '3', valor: 'POR EXPORTAR' }
        ],
        json_fields: {
            "Cod_Tienda": "Cod_Tienda",
            "Clave_TipAX": "Clave_TipAX",
            "Nombre": "Nombre",
            "marca": "marca",
            "modelo": "modelo",
            "serie": "serie"
        },

    }),
    watch: {
        dialogConfirmar(val) {
            val || this.closeDelete()
        },
    },
    created() {
        this.listar()
    },
    methods: {
        async listar() {
            try {
                let res = await axios.get(this.URI);
                this.itemselectProductos = res.data;
                console.log(this.itemselectProductos);
            } catch (error) {
                this.activarMensaje('error', 'Existen incovenientes al momento de cargar la información! Error:' + error);
            }
        },
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
        async consultar() {

            if (this.form.tipo == '' || (this.form.fechaInicio == null && this.form.fechaFin == null)) {
                this.activarMensaje('error', 'Es necesario ingresar algún campo Tipo, Fecha inicio y Fecha fin.!')
            } else {
                await axios.post(this.URI, this.form).then((request) => {
                    if (request.data.length > 0) {
                        this.cc = request.data;
                    } else {
                        this.cc = [];
                        this.form.nombre = '';
                        this.form.clave = '';
                        this.form.serie = '';
                        this.activarMensaje('error', 'No existen registros detallados con los campos ingresados.!');
                    }

                }, (err) => {
                    console.log('error');
                });
            }

        },
        confirmar() {
            this.dialogConfirmar = true;
        }
    },

}
</script>
<style scoped>
.my-header-style {
    color: #FFFFFF !important;
    background: #2196F3;
}
</style>
