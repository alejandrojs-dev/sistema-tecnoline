import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from './views/Home.vue'
import NotFound from './views/NotFound.vue'
import Unauthorized from './views/Unauthorized.vue'
import Billing from './views/Billing.vue'
import CreateTicket from './views/Tickets/CreateTicket.vue'
import TicketsTray from './views/Tickets/TicketsTray.vue'
import AuthTicketsTray from './views/Tickets/AuthTicketsTray.vue'
import TicketTools from './views/Tickets/TicketTools.vue'
import TicketSubGroups from './views/Tickets/TicketSubGroups.vue'
import TicketGroups from './views/Tickets/TicketGroups.vue'
import Processes from './views/Production/Processes.vue'
import Perfileria from './views/Production/Perfileria.vue'
import Cut from './views/Production/Cut.vue'
import Assembling from './views/Production/Assembling.vue'
import Leveling from './views/Production/Leveling.vue'
import Packaging from './views/Production/Packaging.vue'
import Shipment from './views/Production/Shipment.vue'
import Orders from './views/Production/Orders.vue'
import Details from './views/Production/Details.vue'
import ProcessDetails from './views/Production/ProcessDetails.vue'
import DashboardConfig from './views/Production/DashboardConfig.vue'
import MyDashboards from './views/Business_Intelligence/MyDashboards.vue'
import DashboardAdmin from './views/Business_Intelligence/DashboardAdmin.vue'
import { protectAppRoutes } from './guards/routes.guard'

Vue.use(VueRouter)

const routes = [
  {
    path: '/inicio',
    name: 'Home',
    component: Home,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Inicio | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/facturacion',
    name: 'Facturacion',
    redirect: '/facturacion',
    component: Billing,
  },
  {
    path: '/tickets/crear-ticket',
    name: 'Crear ticket',
    component: CreateTicket,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Crear ticket | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/tickets/bandeja',
    name: 'Bandeja de tickets',
    component: TicketsTray,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Bandeja de tickets | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/tickets/por-autorizar',
    name: 'Tickets por autorizar',
    component: AuthTicketsTray,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Tickets por autorizar | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/tickets/herramientas',
    name: 'Herramientas',
    component: TicketTools,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Herramientas | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/tickets/herramientas/ver-grupos-de-ticket',
    name: 'Ver grupos de ticket',
    component: TicketGroups,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Grupos de ticket | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/tickets/herramientas/ver-subgrupos-de-ticket',
    name: 'Ver subgrupos de ticket',
    component: TicketSubGroups,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Subgrupos de ticket | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/produccion/procesos',
    name: 'Procesos',
    component: Processes,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Procesos | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/produccion/procesos/perfileria',
    name: 'Perfileria',
    component: Perfileria,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Perfileria | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/produccion/procesos/perfileria/pedido/:id',
    name: 'PerfileriaDetails',
    component: Details,
    children: [
      {
        path: 'detalles',
        name: 'Perfileria Detalles',
        component: ProcessDetails,
        meta: {
          title: 'Detalles Perfileria | Tecnoline',
          requiresAuth: true,
        },
      },
    ],
  },
  {
    path: '/produccion/procesos/corte',
    name: 'Corte',
    component: Cut,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Corte | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/produccion/procesos/armado',
    name: 'Armado',
    component: Assembling,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Armado | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/produccion/procesos/nivelacion',
    name: 'Nivelacion',
    component: Leveling,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Nivelación | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/produccion/procesos/empaquetado',
    name: 'Empaquetado',
    component: Packaging,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Empaquetado | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/produccion/procesos/embarque',
    name: 'Embarque',
    component: Shipment,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Embarque | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/produccion/procesos/informacion-de-pedidos',
    name: 'Informacion de pedidos',
    component: Orders,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Información de pedidos | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/produccion/procesos/dashboard',
    name: 'Dashboard',
    component: DashboardConfig,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Configuración de Dashboard | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/business-intelligence/mis-dashboards',
    name: 'Mis Dashboards',
    component: MyDashboards,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Mis Dashboards | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '/business-intelligence/administracion-de-dashboards',
    name: 'Administracion de dashboards',
    component: DashboardAdmin,
    beforeEnter: protectAppRoutes,
    meta: {
      title: 'Administración de dashboards | Tecnoline',
      requiresAuth: true,
    },
  },
  {
    path: '*',
    name: 'NotFound',
    component: NotFound,
    meta: {
      title: 'Página no encontrada | Tecnoline',
    },
  },
  {
    path: '/sin-acceso',
    name: 'Unauthorized',
    component: Unauthorized,
    meta: {
      title: 'No autorizado | Tecnoline',
    },
  },
]

const router = new VueRouter({
  mode: 'history',
  routes,
})

router.beforeEach(async (to, from, next) => {
  document.title = to.meta.title
  next()
})

export default router
