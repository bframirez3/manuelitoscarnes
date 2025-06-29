<template>
    <v-container fluid>
        <v-card flat>
            <v-card-title class="d-flex align-center pe-2">
                <v-icon icon="mdi-clipboard-flow"></v-icon> &nbsp;
                INVENTARIO
                <v-spacer></v-spacer>

                <v-text-field v-model="buscador" density="compact" label="Buscar" prepend-inner-icon="mdi-magnify"
                    variant="solo-filled" flat hide-details single-line></v-text-field>
                <v-spacer></v-spacer>

            </v-card-title>

            <v-divider></v-divider>
            <v-data-table v-model:search="buscador" :filter-keys="['nombre']" :items="items">
                <template v-slot:header.estado>
                    <div>Estado</div>
                </template>
                <template v-slot:header.fecha>
                    <div>Fecha Actualización</div>
                </template>
                <template v-slot:header._id>
                    <div>Cod Producto</div>
                </template>
                <template v-slot:header.acciones>
                    <div>Acciones</div>
                </template>
                <template v-slot:item.estado="{ item }">
                    <div>
                        <v-chip :color="item.estado ? 'green' : 'red'" :text="item.estado ? 'En Stock' : 'Agotado'"
                            class="text-uppercase" size="small" label></v-chip>
                    </div>
                </template>
                <template v-slot:item.acciones="{ item }">
                    <div class="d-flex gap-2">
                        <!-- Botón para entrada -->
                        <v-dialog v-model="dialogAdd" max-width="600">
                            <template v-slot:activator="{ props: activatorProps }">
                                <v-btn color="green" icon @click="entradaProducto(item)" v-bind="activatorProps">
                                    <v-icon>mdi-arrow-up-thick</v-icon>
                                </v-btn>
                            </template>
                            <v-form ref="formIngreso" v-model="validIngreso" lazy-validation @submit.prevent>
                                <v-card prepend-icon="mdi-arrow-up-thick" title="Ingreso de inventario">
                                    <v-divider></v-divider>
                                    <v-card-text>
                                        <v-row dense>
                                            <v-col cols="12" md="12" sm="12">
                                                <v-text-field label="Valor de entrada*" :rules="rules" :counter="3"
                                                    required v-model="productoEntrada.entrada"
                                                    onkeyup='this.value=this.value.replace(/[^a-zA-Z0-9,;@%()$#:_/\-.ÑñÁáÀàÉéÈèÍíÌìÓóÒòÚúÙù\s]/g,"");'>
                                                </v-text-field>
                                            </v-col>
                                        </v-row>

                                        <small class="text-caption text-medium-emphasis">*Campos requeridos</small>
                                    </v-card-text>
                                    <v-divider></v-divider>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="primary" variant="outlined" text="Cerrar"
                                            @click="this.closeAdd()"></v-btn>
                                        <v-btn color="success" variant="outlined" text="Guardar"
                                            :disabled="!validIngreso" @click="this.guardarIngreso()"></v-btn>
                                    </v-card-actions>

                                </v-card>
                            </v-form>
                        </v-dialog>


                        <!-- Botón para salida -->
                        <v-dialog v-model="dialogSalida" max-width="600">
                            <template v-slot:activator="{ props: activatorProps }">
                                <v-btn color="yellow" icon @click="salidaProducto(item)" v-bind="activatorProps">
                                    <v-icon>mdi-arrow-down-thick</v-icon>
                                </v-btn>

                            </template>
                            <v-form ref="formSalida" v-model="validSalida" lazy-validation @submit.prevent>
                                <v-card prepend-icon="mdi-arrow-down-thick" title="Salida de inventario">
                                    <v-divider></v-divider>
                                    <v-card-text>
                                        <v-row dense>
                                            <v-col cols="12" md="12" sm="12">
                                                <v-text-field label="Valor de salida*" :rules="rules" :counter="3"
                                                    type="number" required v-model="productoSalida.salida"
                                                    onkeyup='this.value=this.value.replace(/[^a-zA-Z0-9,;@%()$#:_/\-.ÑñÁáÀàÉéÈèÍíÌìÓóÒòÚúÙù\s]/g,"");'>
                                                </v-text-field>
                                            </v-col>
                                        </v-row>

                                        <small class="text-caption text-medium-emphasis">*Campos requeridos</small>
                                    </v-card-text>
                                    <v-divider></v-divider>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="primary" variant="outlined" text="Cerrar"
                                            @click="this.closeDecrease()"></v-btn>
                                        <v-btn color="success" variant="outlined" text="Guardar"
                                            :disabled="!validSalida" @click="this.guardarSalida()"></v-btn>
                                    </v-card-actions>

                                </v-card>
                            </v-form>
                        </v-dialog>

                    </div>
                </template>
            </v-data-table>
        </v-card>

        <div class="text-center ma-2">
            <v-snackbar v-model="snackbarSuccess" :timeout="timeout" bottom color="success" shaped>
                {{ text }}

            </v-snackbar>
            <v-snackbar v-model="snackbarError" :timeout="timeout" bottom color="error" shaped>
                {{ text }}

            </v-snackbar>
        </div>
    </v-container>
</template>

<script>
export default {
    data: () => ({
        URI: 'api/spa/productos',
        URIEntrada: 'api/spa/registroentrada',
        URISalida: 'api/spa/registrosalida',
        text: '',
        buscador: '',
        timeout: 2000,
        snackbarSuccess: false,
        snackbarError: false,
        color: '',
        dialogAdd: false,
        dialogSalida: false,
        validProducto: true,
        items: [],
        editedIndex: -1,
        productoEntrada: {
            id_producto: '',
            entrada: '',
            fecha: ''
        },
        productoSelect: {
            nombre: '',
            detalle: '',
            tipo: '',
            estado: true,
            cantidad: '',
            fecha: '',
            acciones: 'acciones',
            valorAntiguo: ''
        },
        productoSalida: {
            id_producto: '',
            salida: '',
            fecha: ''
        },
        rules: [
            v => !!v || 'Campo es requerido',
            v => (v && v.length <= 25) || 'El campo debe tener menos de 25 caracteres.',
        ],
        validIngreso: false,
        validSalida: false,
    }),

    computed: {

    },

    watch: {
        dialogDelete(val) {
            val || this.closeDelete()
        },
        dialogAdd(val) {
            val || this.closeAdd()
        },
        dialogSalida(val) {
            val || this.closeDecrease()
        },
    },

    created() {
        this.listar()
    },

    methods: {

        activarMensaje(colors, mensaje) {
            this.text = mensaje;
            if (colors === 'error') {
                this.snackbarError = true;
            } else {
                this.snackbarSuccess = true;

            }
        },

        async listar() {
            try {
                let res = await axios.get(this.URI);
                this.items = res.data;
                console.log(this.items);
            } catch (error) {
                this.activarMensaje('error', 'Existen incovenientes al momento de cargar la información! Error:' + error);
            }
        },
        fechaFormat() {
            const date = new Date();
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // months are 0-indexed
            const year = date.getFullYear();
            const formattedDate = `${year}-${month}-${day}`;
            return formattedDate;
        },
        entradaProducto(item) {
            this.productoSelect = item;
        },
        salidaProducto(item) {
            this.productoSelect = item;
        },
        async guardarIngreso() {
            this.productoSelect.valorAntiguo = this.productoEntrada.entrada;
            this.productoSelect.cantidad = +this.productoSelect.cantidad + + this.productoEntrada.entrada;
            if (this.productoSelect.cantidad > 0) {
                this.productoSelect.estado = true;
            } else {
                this.productoSelect.estado = false;
            }
            this.productoSelect.fecha = this.fechaFormat();
            await axios.put(this.URIEntrada + '/' + this.productoSelect._id, this.productoSelect).then(() => {
                this.activarMensaje('succes', 'El registro se guardo correctamente!.');
            }, (err) => {
                this.activarMensaje('error', 'Existen incovenientes al momento de guardar el registro! Error:' + err);
            });
            this.closeAdd();
            this.$nextTick(() => {
                this.listar()
            });
        },
        async guardarSalida() {
            this.productoSelect.valorAntiguo = this.productoSalida.salida;
            this.productoSelect.cantidad = +this.productoSelect.cantidad - +this.productoSalida.salida;
            if (this.productoSelect.cantidad > 0) {
                this.productoSelect.estado = true;
            } else {
                this.productoSelect.estado = false;
            }
            this.productoSelect.fecha = this.fechaFormat();
            await axios.put(this.URISalida + '/' + this.productoSelect._id, this.productoSelect).then(() => {
                this.activarMensaje('succes', 'El registro se guardo correctamente!.');
            }, (err) => {
                this.activarMensaje('error', 'Existen incovenientes al momento de guardar el registro! Error:' + err);
            });
            this.closeDecrease();
            this.$nextTick(() => {
                this.listar()
            });
        },
        editarItem(item) {
            this.editedIndex = this.items.indexOf(item);
            this.producto = Object.assign({}, item);
            this.dialog = true;
        },

        eliminarItem(item) {
            this.editedIndex = this.items.indexOf(item)
            this.producto = Object.assign({}, item)
            this.dialogDelete = true
        },

        closeAdd() {
            this.$refs.formIngreso.reset();
            this.dialogAdd = false;
        },
        closeDecrease() {
            this.$refs.formSalida.reset();
            this.dialogSalida = false;
        },
        closeDelete() {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.alimentoNuevo)
                this.editedIndex = -1
            })
        },

    },
}
</script>
