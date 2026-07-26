/**
 * Nous allons charger manuellement le module axios
 * pour les requêtes HTTP
 */

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';