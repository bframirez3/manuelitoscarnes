<template>
  <v-app>
    <!-- Navigation Drawer -->
    <v-navigation-drawer
      v-model="drawer"
      floating
      app
      class="menu-left rounded-right-menu shadow"
      color="rgba(0, 0, 0, 0.87)"
      width="260"
      absolute
    >
      <!-- Logo -->
      <v-list-item>
        <v-list-item-content class="d-flex justify-center">
          <v-img src="../../img/logo.png" width="80%" max-height="100px" contain></v-img>
        </v-list-item-content>
      </v-list-item>

      <!-- Title -->
      <v-list-item class="text-center py-4">
        <v-list-item-title class="text-h6 font-weight-bold text-white">Manuelito´s</v-list-item-title>
      </v-list-item>

      <v-divider class="border-opacity-100"></v-divider>

      <!-- Menu Items -->
      <v-list density="compact" nav>
        <v-list-item
          v-for="item in items"
          :key="item.title"
          :to="item.ruta"
          class="menu-item"
          link
          :class="{ 'selected-item': selectedItem === item.title }"
          @click="selectedItem = item.title"
        >
          <v-row class="d-flex align-center" no-gutters>
            <v-col class="d-flex align-center" cols="auto">
              <v-icon color="white">{{ item.icon }}</v-icon>
            </v-col>
            <v-col class="d-flex align-center" cols="auto">
              <v-list-item-title class="text-body-1 font-weight-medium white--text">
                {{ item.title }}
              </v-list-item-title>
            </v-col>
          </v-row>
        </v-list-item>
      </v-list>

      <!-- Botón de Salir anclado abajo -->
      <v-list-item class="logout-btn" link @click="logout">
        <v-row class="d-flex align-center" no-gutters>
          <v-col class="d-flex align-center" cols="auto">
            <v-icon color="white">mdi-logout</v-icon>
          </v-col>
          <v-col class="d-flex align-center" cols="auto">
            <v-list-item-title class="text-body-1 font-weight-medium white--text">Salir</v-list-item-title>
          </v-col>
        </v-row>
      </v-list-item>
    </v-navigation-drawer>

    <!-- App Bar -->
    <v-app-bar color="#4caf50" elevation="6" class="rounded-bar mx-3 mt-3" absolute>
      <v-toolbar-title class="text-h6 white--text">Panel de Control</v-toolbar-title>
    </v-app-bar>

    <!-- Main Content -->
    <v-main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      drawer: true,
      selectedItem: null,
      items: [
        { title: "PRODUCTOS", icon: "mdi-cow", ruta: "productos" },
        { title: "INVENTARIO", icon: "mdi-book-open", ruta: "inventarios" },
        { title: "REPORTE", icon: "mdi-chart-timeline", ruta: "reportes" },
      ],
    };
  },
  methods: {
    logout() {
      localStorage.clear();
      this.$router.push({ name: "/" });
    },
  },
};
</script>

<style scoped>
/* Menú lateral con esquinas derechas redondeadas */
.rounded-right-menu {
  position: fixed;
  left: 20px;
  top: 20px;
  bottom: 20px;
  z-index: 10;
  width: 260px;
  box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;
  background-color: rgba(0, 0, 0, 0.87);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

/* Botón de salir fijo al fondo */
.logout-btn {
  position: absolute;
  bottom: 20px;
  width: 100%;
  padding-left: 20px;
  padding-right: 20px;
  transition: background-color 0.3s;
}
.logout-btn:hover {
  background-color: rgba(169, 169, 169, 0.3);
}

/* Barra superior */
.rounded-bar {
  border-radius: 20px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
  background-color: #2c3e50;
}

/* Ítems del menú */
.menu-item {
  transition: background-color 0.3s, padding-left 0.3s;
  padding-left: 20px;
  padding-right: 20px;
  display: flex;
  align-items: center;
}
.menu-item:hover {
  background-color: rgba(169, 169, 169, 0.3);
  color: #2c3e50;
}

.v-main {
  background-color: #f5f5f5;
  min-height: 100vh;
  padding-top: 80px;
}

.text-primary {
  color: #2c3e50;
}

.v-list-item-title {
  color: #ffffff;
}

.v-toolbar-title {
  color: #ffffff;
}

.v-icon {
  color: #ffffff;
}

.white--text {
  color: white !important;
}

.selected-item {
  background-color: #4caf50 !important;
  color: white !important;
}

.menu-item .v-row {
  margin-left: 10px;
}
</style>
