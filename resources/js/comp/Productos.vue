<template>
    <v-container fluid>
        <v-card flat>
            <v-card-title class="d-flex align-center pe-2">
                <v-icon icon="mdi-cow"></v-icon> &nbsp;
                PRODUCTOS
                <v-spacer></v-spacer>

                <v-text-field v-model="buscador" density="compact" label="Buscar" prepend-inner-icon="mdi-magnify"
                    variant="solo-filled" flat hide-details single-line></v-text-field>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="600">
                    <template v-slot:activator="{ props: activatorProps }">
                        <v-btn prepend-icon="mdi-cow" text="Nuevo Registro" variant="outlined"
                            v-bind="activatorProps"></v-btn>
                    </template>
                    <v-form ref="formProducto" v-model="validProducto" lazy-validation @submit.prevent>
                        <v-card prepend-icon="mdi-book-open" :title="formTitle">
                            <v-divider></v-divider>
                            <v-card-text>
                                <v-row dense>

                                    <v-col cols="12" md="12" sm="12">
                                        <v-text-field label="Nombre*" :rules="rules" :counter="25" required
                                            v-model="producto.nombre"></v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="12" sm="12">
                                        <v-text-field label="Detalle*" :rules="rules" :counter="25" required
                                            v-model="producto.detalle"></v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="12" sm="12">
                                        <v-text-field label="Tipo*" :rules="rules" :counter="25" required
                                            v-model="producto.tipo"></v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="12" sm="12">
                                        <v-text-field label="Stock inicial*" :rules="rules" :counter="25" required
                                            v-model="producto.cantidad"
                                            onkeyup='this.value=this.value.replace(/[^a-zA-Z0-9,;@%()$#:_/\-.ÑñÁáÀàÉéÈèÍíÌìÓóÒòÚúÙù\s]/g,"");'>
                                        </v-text-field>
                                    </v-col>

                                    <!--<v-col cols="12" sm="6">
                                    <v-autocomplete
                                        :items="['Kilogramo', 'Gramos', 'Unidad']"
                                        label="Interests" auto-select-first ></v-autocomplete>
                                </v-col>-->
                                </v-row>

                                <small class="text-caption text-medium-emphasis">*Campos requeridos</small>
                            </v-card-text>
                            <v-divider></v-divider>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" variant="outlined" text="Cerrar" @click="this.close"></v-btn>
                                <v-btn color="success" variant="outlined" text="Guardar" :disabled="!validProducto"
                                    @click="this.guardar"></v-btn>
                            </v-card-actions>

                        </v-card>
                    </v-form>
                </v-dialog>
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
                        <!-- Botón para editar -->
                        <v-btn color="blue" icon @click="editarItem(item)">
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <!-- Botón para eliminar -->
                        <v-btn color="red" icon @click="eliminarItem(item)">
                            <v-icon>mdi-delete</v-icon>
                        </v-btn>
                    </div>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialogDelete" max-width="400" persistent>

            <v-card prepend-icon="mdi-delete-circle" text="¿Estás seguro de que quieres eliminar este registro?"
                title="Confirmar eliminar registro">
                <v-divider></v-divider>
                <template v-slot:actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="dialogDelete = false" variant="outlined">
                        NO
                    </v-btn>
                    <v-btn @click="borrarItem()" variant="outlined">
                        SI
                    </v-btn>
                </template>
            </v-card>
        </v-dialog>
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
        text: '',
        buscador: '',
        timeout: 2000,
        snackbarSuccess: false,
        snackbarError: false,
        color: '',
        dialog: false,
        dialogDelete: false,
        validProducto: true,
        items: [],
        editedIndex: -1,
        producto: {
            nombre: '',
            detalle: '',
            tipo: '',
            estado: true,
            cantidad: '',
            fecha: '',
            acciones: 'acciones'
        },
        rules: [
            v => !!v || 'Campo es requerido',
            v => (v && v.length <= 25) || 'El campo debe tener menos de 25 caracteres.',
        ],
    }),

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'Nuevo Producto' : 'Editar Producto'
        },
    },

    watch: {
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },

    created() {
        this.listar()
    },

    methods: {
        formatDate(isoDate) {
            const date = new Date(isoDate);
            return date.toISOString().split("T")[0]; // Retorna solo la fecha
        },
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

        async borrarItem() {
            await axios.delete(this.URI + '/' + this.producto._id).then(() => {
                this.activarMensaje('success', 'El registro se borro correctamente!.');
            }, (err) => {
                this.activarMensaje('error', 'Existen incovenientes al momento de borrar el registro! Error:' + err);
            });
            this.closeDelete();
            this.$nextTick(() => {
                this.listar()
            })
        },
        close() {
            this.$refs.formProducto.reset();
            this.dialog = false;
            this.editedIndex = -1
        },
        closeDelete() {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.alimentoNuevo)
                this.editedIndex = -1
            })
        },
        async guardar() {
            if (this.producto.cantidad == 0) {
                this.producto.estado = false;
            } else {
                this.producto.estado = true;
            }
            this.producto.fecha = this.fechaFormat();
            if (this.$refs.formProducto.validate()) {
                if (this.editedIndex > -1) {
                    await axios.put(this.URI + '/' + this.producto._id, this.producto).then(() => {
                        this.$nextTick(() => {
                            this.activarMensaje('succes', 'El registro se edito correctamente!.');
                        })
                    }, (err) => {
                        this.activarMensaje('error', 'Existen incovenientes al momento de editar el registro! Error:' + err);
                    });
                } else {
                    await axios.post(this.URI, this.producto).then(() => {
                        this.activarMensaje('succes', 'El registro se guardo correctamente!.');
                    }, (err) => {
                        this.activarMensaje('error', 'Existen incovenientes al momento de guardar el registro! Error:' + err);
                    });
                }
                this.close()
                this.$nextTick(() => {
                    this.listar()
                })
            }

        },
    },
}
</script>
