import Vue from 'vue';
import VueRouter from "vue-router";

Vue.use(VueRouter);

import Dashboard from "../pages/Dashboard";
import Billing from "../pages/Billing";
import Settings from "../pages/Settings";
import Warehouse from "../pages/warehouse/Warehouse";
import Company from '../pages/company/Info';


import CreateCompany from "../pages/CreateCompany";

import BrowsePlaces from "../pages/places/Browse";
import BrowseProducts from "../pages/products/Browse";
import BrowseSuppliers from "../pages/suppliers/Browse";
import BrowseCustomers from "../pages/customers/Browse";

import CreateSellInvoice from '../pages/invoices/sell/Create';
import BrowseSellInvoices from '../pages/invoices/sell/Browse';
import ShowSellInvoice from '../pages/invoices/sell/Show';

import CreateReminder from '../pages/invoices/reminders/Create';
import BrowseReminders from '../pages/invoices/reminders/Browse';
import ShowReminder from '../pages/invoices/reminders/Show';

import CreateBuyInvoice from '../pages/invoices/buy/Create';
import BrowseBuyInvoices from '../pages/invoices/buy/Browse';
import ShowBuyInvoice from '../pages/invoices/buy/Show';

import CreateCreditNote from '../pages/invoices/creditNotes/Create';
import BrowseCreditNotes from "../pages/invoices/creditNotes/Browse";
import ShowCreditNote from "../pages/invoices/creditNotes/Show";

import Accounting from "../pages/accounting/Accounting";
import Vat from "../pages/accounting/Vat";
import BrowseFeedback from '../pages/feedback/Browse';

import Help from "../pages/help/Help";
import HelpPlaces from "../pages/help/HelpPlaces";
import HelpProducts from "../pages/help/HelpProducts";
import HelpCustomers from "../pages/help/HelpCustomers";
import HelpSuppliers from "../pages/help/HelpSuppliers";

const routes = [

  {
    path: '/',
    component: Dashboard
  },
  {
    path: '/billing',
    component: Billing
  },
  {
    path: '/settings',
    component: Settings
  },


  /**
   * Buy
   */

  {
    path: '/companies/create',
    component: CreateCompany
  },

  /**
   * Warehouse
   */

  {
    path: '/warehouse',
    component: Warehouse
  },

  /**
   * Places
   */
  {
    path: '/places',
    component: BrowsePlaces
  },

  /**
   * Items
   */
  {
    path: '/products',
    component: BrowseProducts
  },

  /**
   * Suppliers
   */

  {
    path: '/suppliers',
    component: BrowseSuppliers
  },

  /**
   * Contacts
   */

  {
    path: '/customers',
    component: BrowseCustomers
  },


  /**
   * Sell Invoices
   */

  {
    path: '/invoices/sell/create',
    component: CreateSellInvoice
  },
  {
    path: '/invoices/sell/browse',
    component: BrowseSellInvoices
  },
  {
    path: '/invoices/sell/:id',
    component: ShowSellInvoice,
    name: 'sell-invoice'
  },

  /**
   * Reminders
   */

  {
    path: '/invoices/reminder/create/:id',
    component: CreateReminder,
    name: 'create-reminder'
  },
  {
    path: '/invoices/reminder/browse',
    component: BrowseReminders
  },
  {
    path: '/invoices/reminder/:id',
    component: ShowReminder,
    name: 'reminder'
  },

  /**
   * Buy Invoices
   */

  {
    path: '/invoices/buy/create',
    component: CreateBuyInvoice
  },
  {
    path: '/invoices/buy/browse',
    component: BrowseBuyInvoices
  },
  {
    path: '/invoices/buy/:id',
    component: ShowBuyInvoice,
    name: 'buy-invoice'
  },

  /**
   * Credit Notes
   */

  {
    path: '/invoices/creditNote/create/:id',
    component: CreateCreditNote,
    name: 'create-credit-note'
  },
  {
    path: '/invoices/creditNote/browse',
    component: BrowseCreditNotes
  },
  {
    path: '/invoices/creditNote/:id',
    component: ShowCreditNote,
    name: 'credit-note'
  },

  /**
   * Accounting
   */

  {
    path: '/accounting/see',
    component: Accounting
  },

  {
    path: '/accounting/vat',
    component: Vat
  },

  /**
   * Bank
   */

  {
    path: '/company',
    component: Company
  },

  /**
   * Feedback
   */

  {
    path: '/feedback',
    component: BrowseFeedback
  },

  /**
   * Help
   */

  {
    path: '/help/places',
    component: HelpPlaces
  },
  {
    path: '/help/products',
    component: HelpProducts
  },
  {
    path: '/help/customers',
    component: HelpCustomers
  },
  {
    path: '/help/suppliers',
    component: HelpSuppliers
  },
  {
    path: '/help',
    component: Help
  },
];

export default new VueRouter({
  routes
})
