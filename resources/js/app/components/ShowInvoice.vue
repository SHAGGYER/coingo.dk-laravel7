<template>
    <v-container>
        <div class="mb-5 d-flex justify-end" v-if="invoice">
            <v-menu offset-y>
                <template v-slot:activator="{ on }">
                    <v-btn
                            color="purple"
                            dark
                            v-on="on"
                    >
                        Handlinger
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item :to="{ name: 'create-reminder', params: { id: invoice.id } }" v-if="type === 'sell'">
                        <v-list-item-title>Opret Rykker</v-list-item-title>
                    </v-list-item>
                    <v-list-item v-if="!invoice.paid && (type === 'sell' || type === 'reminder')" @click="registerPaymentDialog = true">
                        <v-list-item-title>Registrér Betaling</v-list-item-title>
                    </v-list-item>
                    <v-list-item v-else-if="invoice.paid && (type === 'sell' || type === 'reminder')" @click="deletePaymentDialog = true">
                        <v-list-item-title>Slet Betaling</v-list-item-title>
                    </v-list-item>
                    <v-list-item v-if="!creditNote && type === 'sell'"
                                 :to="{ name: 'create-credit-note', params: { id: invoice.id } }">
                        Opret Kreditnota
                    </v-list-item>
                    <v-list-item @click="sendInvoiceToEmailDialog = true" v-if="type === 'sell' || type === 'reminder'">
                        <v-list-item-title>Send Faktura På Email</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="printInvoice">
                        Udskriv
                    </v-list-item>
                    <v-list-item @click="deleteDialog = true">Slet</v-list-item>
                </v-list>
            </v-menu>
        </div>

        <Errors :errors="errors"></Errors>

        <div class="invoice" v-if="invoice">
            <div class="paid" v-if="type === 'sell' || type === 'reminder'">
                <span v-if="invoice.paid" class="purple white--text">Betalt</span>
                <span v-else class="orange white--text">Ikke Betalt</span>
            </div>
            <div class="top">
                <div class="customer">
                    <p>
                        {{ invoice.contact.name }}
                    </p>

                    <div class="customer-info">
                        <p>{{ invoice.contact.address }}</p>
                        <p>{{ invoice.contact.zip }}</p>
                        <p>{{ invoice.contact.city }}</p>
                        <p class="mb-0">{{ invoice.contact.country }}</p>
                    </div>
                </div>
                <div class="company">
                    <h2>{{ $store.state.user.company.title }}</h2>
                    <p>{{ $store.state.user.company.address }}</p>
                    <p>{{ $store.state.user.company.city }}</p>
                    <p>{{ $store.state.user.company.zip }}</p>
                    <p>{{ $store.state.user.company.country }}</p>
                    <p>Tlf.: {{ $store.state.user.company.phone }}</p>
                    <p class="mb-0">{{ $store.state.user.company.email }}</p>
                </div>
            </div>
            <div class="top2">
                <div class="date" v-if="type === 'sell' || type === 'reminder'">
                    <p class="mb-0">Betalingsdato: {{ invoice.payment_date }}</p>

                </div>
                <div class="invoice-number">
                    <p class="mb-0">Fakturanr.: {{ invoice.invoice_number }}</p>
                </div>
            </div>
            <div class="middle">
                <h2>{{ invoice.headline }}</h2>

                <p class="mb-0">{{ invoice.comments }}</p>
            </div>
            <div class="middle2">
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                        <tr>
                            <th style="width: 30%" class="text-left">Beskrivelse</th>
                            <th style="width: 10%" class="text-left">Antal</th>
                            <th style="width: 15%" class="text-left">Enhed</th>
                            <th style="width: 15%" class="text-left">Pris</th>
                            <th style="width: 10%" class="text-left">Rabat</th>
                            <th style="width: 15%" class="text-left">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(line, index) in invoice.items" :key="index">
                            <td>
                                <div v-if="line.item_id">
                                    <p v-if="line.item">
                                        {{ line.item.title }}
                                    </p>
                                    <p v-else>Produkt ikke fundet</p>
                                </div>
                                <div v-else>
                                    {{ line.freetext }}
                                </div>
                                <p v-if="line.comments" class="mb-0">
                                    {{ line.comments }}
                                </p>
                            </td>
                            <td>
                                <p class="mb-0">{{ line.quantity }}</p>
                            </td>
                            <td>
                                <p class="mb-0">
                                    {{ getUnit(line.unit).text }}
                                </p>
                            </td>
                            <td>
                                <span>{{ line.price.toFixed(2) }}</span>
                            </td>
                            <td>
                                <span>{{ line.discount }} %</span>
                            </td>
                            <td>
                                <p class="mb-0">{{ getLinePrice(line) }}</p>
                            </td>
                        </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </div>
            <v-divider class="mt-3"></v-divider>
            <div class="bottom">
                <div class="content">
                    <p>Subtotal: {{ invoice.subtotal.toFixed(2) }}</p>
                    <p v-if="invoice.account === 'withVat'">Moms (25%): {{ invoice.vat.toFixed(2) }}</p>
                    <h3>TOTAL DKK: {{ invoice.total.toFixed(2) }}</h3>
                </div>
            </div>
            <div class="bottom2">
                <div class="content">
                    <p>Forfaldsdato: {{ invoice.payment_date }}</p>
                    <p>Reg nr.: {{ $store.state.user.company.reg_nr }}</p>
                    <p class="mb-0">Kontonr.: {{ $store.state.user.company.account_number }}</p>
                </div>
            </div>
        </div>

        <v-dialog v-model="registerPaymentDialog" width="300">
            <ConfirmDialog @confirmed="onRegisteredPayment" @closed="registerPaymentDialog = false"></ConfirmDialog>
        </v-dialog>
        <v-dialog v-model="deletePaymentDialog" width="300">
            <ConfirmDialog @confirmed="onDeletedPayment" @closed="deletePaymentDialog = false"></ConfirmDialog>
        </v-dialog>
        <v-dialog v-model="sendInvoiceToEmailDialog" width="300">
            <ConfirmDialog @confirmed="sendInvoiceToEmail" @closed="sendInvoiceToEmailDialog = false"></ConfirmDialog>
        </v-dialog>
        <v-dialog v-model="deleteDialog" width="300">
            <ConfirmDialog @confirmed="onDeleted" @closed="deleteDialog = false"></ConfirmDialog>
        </v-dialog>
    </v-container>
</template>

<script>
  import units from '../data/units';
  import ConfirmDialog from "../../common/ConfirmDialog";
  import Errors from "../../common/Errors";
  export default {
    name: "ShowInvoice",
    props: ['type'],
    components: {
      Errors,
      ConfirmDialog,
    },
    data() {
      return {
        invoice: null,
        creditNote: null,
        registerPaymentDialog: false,
        deletePaymentDialog: false,
        deleteDialog: false,
        sendInvoiceToEmailDialog: false,
        units,
        errors: [],
        gotResults: false,
      }
    },
    mounted() {
      if (!this.$store.state.user) {
        this.$router.push('/auth/login');
        return;
      }

      if (!this.$store.state.user.company) {
        this.$router.push('/buy');
        return;
      }

      if (!this.$store.state.isSubscribed) {
        this.$router.push('/billing');
        return;
      }

      this.getInvoice();
    },
    methods: {
      onDeleted() {
        this.$store.dispatch('invoices/delete', this.$route.params.id).then(() => {
          this.$notify('Du har nu slettet denne faktura');
          this.deleteDialog = false;
          this.$router.push(`/invoices/${this.type}/browse`);
        })
      },
      onDeletedPayment() {
        const data = {
          invoiceId: this.invoice.id,
          payment: 0
        };
        this.$store.dispatch('invoices/registerPayment', data).then(() => {
          this.invoice.paid = 0;
          this.deletePaymentDialog = false;
        })
      },
      onRegisteredPayment() {
        const data = {
          invoiceId: this.invoice.id,
          payment: 1
        };
        this.$store.dispatch('invoices/registerPayment', data).then(() => {
          this.invoice.paid = 1;
          this.registerPaymentDialog = false;
        })
      },
      printInvoice() {
        window.open(BASE_URL + '/invoices/print/'+this.$route.params.id+`?type=${this.type}`, '_blank');
      },
      sendInvoiceToEmail() {
        const data = {
          invoiceId: this.invoice.id,
          type: this.type
        };
        this.$store.dispatch('invoices/sendInvoiceToEmail', data).then(() => {
          this.$notify('Du har sendt denne faktura på email');
          this.sendInvoiceToEmailDialog = false;
        }).catch(error => {
          this.sendInvoiceToEmailDialog = false;
          this.errors.push(error);
        });
      },
      getInvoice() {
        const data = {
          id: this.$route.params.id,
          params: {
            type: this.type
          }
        };
        this.$store.dispatch('invoices/getById', data).then(invoice => {
          this.invoice = invoice;
          this.gotResults = true;
        }).catch(err => {
          this.gotResults = true;
          this.errors.push(err);
        })
      },
      getUnit(key) {
        return this.units.find(unit => unit.key === key);
      },
      getItem(itemId) {
        return this.invoice.items.find(item => item.id === itemId);
      },
      getLinePrice(line) {
        let base = line.quantity * line.price;
        let discount = 1 - (0.01 * line.discount);

        return (base * discount).toFixed(2);
      },
    }
  }
</script>

<style scoped>
    .invoice {
        border: 1px solid #333;
        padding: 20px;
        position: relative;
    }
    .invoice .preview {
        position: absolute;
        right: 5px;
        top: 5px;
    }
    .invoice .paid {
        position: absolute;
        left: 0;
        top: 5px;
    }
    .invoice .paid span {
        padding: 5px;
    }
    .invoice .top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-top: 2rem;
    }
    .invoice .top .customer {
        width: 300px;
        border: 1px solid #ccc;
        padding: 10px;
    }
    .invoice .top .customer .customer-info {
        font-size: 12px;
        line-height: 0.7;
    }
    .invoice .company {
        border: 1px solid #ccc;
        padding: 15px;
    }
    .invoice .company h2 {
        margin-bottom: 1rem;
    }
    .invoice .company p {
        line-height: 0.5;
    }
    .invoice .top2 {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-top: 2rem;
    }
    .invoice .top2 .date {
        width: 300px;
    }
    .invoice .middle {
        margin-top: 2rem;
    }
    .invoice .middle2 {
        margin-top: 2rem;
    }
    .invoice .bottom {
        margin-top: 2rem;
        display: flex;
        justify-content: flex-end;
        text-align: right;
    }
    .invoice .bottom2 {
        margin-top: 1rem;
    }
    .bottom2 .content {
        line-height: 0.5;
        border: 1px solid #ccc;
        padding: 10px;
    }
</style>
