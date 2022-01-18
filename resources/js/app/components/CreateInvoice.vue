<template>
    <div>
        <v-alert class="mt-5" :value="true" color="purple" dark>
            <v-icon>mdi-star-four-points</v-icon>
            <span>Dette er en feature, som du kun kan bruge, når du har et aktivt abonnement</span>
        </v-alert>

        <Errors :errors="errors"></Errors>

        <div class="invoice">
            <div class="preview">
                <v-btn v-if="!preview" icon @click="startTour">
                    <v-icon>mdi-help-circle</v-icon>
                </v-btn>
                <v-btn @click="setPreview(true)" icon v-if="!preview">
                    <v-icon>search</v-icon>
                </v-btn>
                <v-btn @click="setPreview(false)" icon v-else>
                    <v-icon>close</v-icon>
                </v-btn>
                <v-btn id="invoice-step-6" color="purple" dark @click="create">Opret</v-btn>
            </div>
            <div class="top">
                <div class="customer">
                    <v-autocomplete
                            id="invoice-step-1"
                            v-if="!preview"
                            :label="type === 'sell' || type === 'creditNote' || type === 'reminder' ? 'Kunde' : 'Leverandør'"
                            :items="contacts"
                            item-text="name"
                            item-value="id"
                            v-model="chosenContactId"
                    >
                        <template slot="append-item">
                            <v-btn v-if="type === 'sell' || type === 'creditNote' || type === 'reminder'"
                                   @click="createCustomerDialog = true"
                                   text
                                   color="green">
                                Opret Kunde
                            </v-btn>
                            <v-btn v-else color="green"
                                   text
                                   @click="createSupplierDialog = true">
                                Opret Leverandør
                            </v-btn>
                        </template>
                    </v-autocomplete>
                    <p v-else>
                        {{ chosenContact ? chosenContact.name : '' }}
                    </p>

                    <div class="customer-info" v-if="chosenContact">
                        <p>{{ chosenContact.address }}</p>
                        <p>{{ chosenContact.zip }}</p>
                        <p>{{ chosenContact.city }}</p>
                        <p class="mb-0">{{ chosenContact.country }}</p>
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
                    <v-menu
                            v-if="!preview"
                            v-model="paymentDateMenu"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                    id="invoice-step-2"
                                    v-model="paymentDate"
                                    label="Betalingsdato"
                                    prepend-icon="event"
                                    readonly
                                    v-on="on"
                            ></v-text-field>
                        </template>
                        <v-date-picker v-model="paymentDate" @input="paymentDateMenu = false"></v-date-picker>
                    </v-menu>
                    <div v-else>
                        <p class="mb-0">Betalingsdato: {{ paymentDate }}</p>
                    </div>
                </div>
                <div class="invoiceNumber">
                    <v-text-field v-if="!preview"
                                  type="number"
                                  v-model="invoiceNumber"
                                  label="Fakturanr."></v-text-field>
                    <span v-else>Fakturanr..: {{ invoiceNumber }}</span>
                </div>
            </div>
            <div class="top3">
                <div class="account">
                    <v-select label="Konto"
                              v-if="!preview"
                              :items="accountItems"
                              item-value="value"
                              item-text="text"
                              v-model="account">
                    </v-select>
                    <span v-else>Konto: {{ getAccount.text }}</span>
                </div>
            </div>
            <div class="middle">
                <v-text-field v-if="!preview" v-model="headline" label="Overskrift"></v-text-field>
                <h2 v-else>{{ headline }}</h2>

                <v-textarea v-if="!preview"
                            id="invoice-step-4"
                            v-model="comments"
                            rows="1"
                            label="Evt. kommentarer"></v-textarea>
                <p v-else class="mb-0">{{ comments }}</p>
            </div>
            <div class="middle2">
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                        <tr>
                            <th style="width: 38%" class="text-left">Beskrivelse</th>
                            <th style="width: 12%" class="text-left">Antal</th>
                            <th style="width: 15%" class="text-left">Enhed</th>
                            <th style="width: 15%" class="text-left">Pris</th>
                            <th style="width: 5%" class="text-left">Rabat (i procent)</th>
                            <th style="width: 10%" class="text-left">Total</th>
                            <th v-if="!preview" style="width: 5%">Handlinger</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(line, index) in lines" :key="index">
                            <td>
                                <div v-if="!preview">
                                    <v-autocomplete
                                            v-if="!line.isFreetext && (type === 'sell' || type === 'creditNote')"
                                            @change="itemChosen(index)"
                                            label="Produkt"
                                            :items="items"
                                            item-text="title"
                                            item-value="id"
                                            autocomplete="off"
                                            v-model="line.itemId"
                                    >
                                        <template slot="append-item">
                                            <v-btn @click="openCreateItemDialog(index)" text color="green">Opret Produkt</v-btn>
                                            <v-btn @click="line.isFreetext = true" text color="green">Fritekst</v-btn>
                                        </template>
                                    </v-autocomplete>
                                    <v-text-field v-else v-model="line.freetext"></v-text-field>
                                    <v-btn @click="line.isAddingComments = true"
                                           class="mb-3"
                                           x-small
                                           text
                                           color="purple"
                                           dark
                                        v-if="!line.isAddingComments">
                                        Kommentarer
                                    </v-btn>
                                    <v-text-field prepend-icon="close"
                                                  dense
                                                  @click:prepend="line.isAddingComments = false"
                                                  v-else
                                                  v-model="line.comments"
                                                  placeholder="Kommentarer">
                                    </v-text-field>
                                </div>
                                <div v-else>
                                    <div v-if="line.itemId">
                                        {{ getItem(line.itemId).title }}
                                    </div>
                                    <div v-else-if="line.isFreetext">
                                        {{ line.freetext }}
                                    </div>
                                    <div class="mt-3" v-if="line.comments">
                                        {{ line.comments }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <v-text-field @blur="checkLineQuantity(line, index)" v-if="!preview" type="number" v-model="line.quantity"></v-text-field>
                                <p class="mb-0" v-else>{{ line.quantity }}</p>
                            </td>
                            <td>
                                <v-select v-model="line.unit"
                                          v-if="!preview"
                                          item-value="key"
                                          item-text="text"
                                          :items="units"></v-select>
                                <p class="mb-0" v-else>
                                    {{ getUnit(line.unit).text }}
                                </p>
                            </td>
                            <td>
                                <v-text-field v-if="!preview" v-model="line.price"></v-text-field>
                                <p class="mb-0" v-else>{{ line.price }}</p>
                            </td>
                            <td>
                                <v-text-field v-if="!preview" v-model="line.discount"></v-text-field>
                                <span v-else>{{ line.discount }} %</span>
                            </td>
                            <td>
                                <p class="mb-0">{{ getLinePrice(line) }}</p>
                            </td>
                            <td>
                                <v-btn v-if="!preview" icon @click="deleteLine(index)">
                                    <v-icon>close</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                        </tbody>
                    </template>
                </v-simple-table>
                <v-btn id="invoice-step-5" v-if="!preview && (type === 'sell' || type === 'buy')" class="mt-3" @click="addLine">Tilføj Linje</v-btn>
            </div>
            <v-divider class="mt-3"></v-divider>
            <div class="bottom">
                <div class="content">
                    <p>Subtotal: {{ subtotal }}</p>
                    <p v-if="account === 'withVat'">Moms (25%): {{ vat }}</p>
                    <h3>TOTAL DKK: {{ total }}</h3>
                </div>
            </div>
            <div class="bottom2" v-if="type === 'sell'">
                <div class="content">
                    <p>Forfaldsdato: {{ paymentDate }}</p>
                    <p v-if="$store.state.user">Reg nr. {{ $store.state.user.company.reg_nr }}</p>
                    <p v-if="$store.state.user" class="mb-0">Kontonr. {{ $store.state.user.company.account_number }}</p>
                </div>
            </div>
        </div>

        <v-dialog width="700" v-model="createCustomerDialog">
            <CreateContact type-contact="customer" @created="onCustomerCreated"></CreateContact>
        </v-dialog>
        <v-dialog width="700" v-model="createSupplierDialog">
            <CreateContact type-contact="supplier" @created="onSupplierCreated"></CreateContact>
        </v-dialog>
        <v-dialog width="700" v-model="createItemDialog">
            <CreateItemForm @closed="createItemDialog = false" @created="onItemCreated"></CreateItemForm>
        </v-dialog>


        <v-tour name="tour" :steps="steps" :options="tourOptions">
            <template slot-scope="tour">
                <transition name="fade">
                    <v-step
                            v-if="tour.currentStep === index"
                            v-for="(step, index) of tour.steps"
                            :key="index"
                            :step="step"
                            :previous-step="tour.previousStep"
                            :next-step="tour.nextStep"
                            :stop="tour.stop"
                            :is-first="tour.isFirst"
                            :is-last="tour.isLast"
                            :labels="tour.labels"
                    >
                    </v-step>
                </transition>
            </template>
        </v-tour>
    </div>
</template>

<script>
  import units from "../data/units";
  import account from "../data/account";
  import CreateItemForm from '../pages/products/CreateForm';
  import Errors from "../../common/Errors";
  import CreateContact from "./CreateContact";
  import Create from "../pages/invoices/sell/Create";
  export default {
    name: "CreateInvoice",
    props: ['type'],
    components: {
      Create,
      CreateContact,
      Errors,
      CreateItemForm,
    },
    data() {
      return {
        invoice: null,
        steps: [
          {
            target: '#invoice-step-1',
            content: `Her kan du vælge ` + (this.type === 'sell' || this.type === 'creditNote' ? 'kunden' : 'leverandøren' )
          },
        ],
        tourOptions: {
          labels: {
            buttonSkip: 'Forlad guiden',
            buttonPrevious: 'Forrige',
            buttonNext: 'Næste',
            buttonStop: 'Færdig'
          }
        },
        preview: false,
        contacts: [],
        suppliers: [],
        chosenContact: null,
        chosenContactId: '',
        contactSearchQuery: '',
        createCustomerDialog: false,
        createSupplierDialog: false,
        createItemDialog: false,
        paymentDate: '',
        paymentDateMenu: false,
        invoiceNumber: 0,
        headline: '',
        lines: [],
        comments: '',
        items: [],
        searchItems: '',
        units,
        currentLine: null,
        currentLineIndex: null,
        errors: [],
        account: 'withVat',
        accountItems: account
      }
    },
    mounted() {
      if (!this.$store.state.user) {
        this.$router.push('/auth/login');
        return;
      }

      if (!this.$store.state.user.company) {
        this.$router.push('/companies/create');
        return;
      }

      if (!this.$store.state.isSubscribed) {
        this.$router.push('/billing');
        return;
      }

      if (this.type === 'sell' || this.type === 'buy') {
        this.headline = 'Faktura';
      } else if (this.type === 'creditNote') {
        this.headline = 'Kreditnota';
      } else if (this.type === 'reminder') {
        this.headline = 'Rykker';
      }

      if (this.type === 'sell' || this.type === 'creditNote') {
        this.getItems();
        this.getCustomers();
      } else {
        this.getSuppliers();
      }

      if (this.type === 'creditNote' || this.type === 'reminder') {
        this.getInvoice();
      }

      if (this.type === 'sell') {
        this.steps.push({
          target: '#invoice-step-2',
          content: `Her kan du angive betalingsdatoen.`
        });
      }

      this.steps.push({
        target: '#invoice-step-4',
        content: `Her kan du skrive dine kommentarer.`
      });
      this.steps.push({
        target: '#invoice-step-5',
        content: `Her tilføjer du en ny fakturalinje.`
      });
      this.steps.push({
        target: '#invoice-step-6',
        content: `Her opretter du fakturen.`
      });



      this.invoiceNumber = this.$store.state.user.company.invoice_number
        ? this.$store.state.user.company.invoice_number
        : 1;

    },
    watch: {
      chosenContactId(id) {
        this.chosenContact = this.contacts.find(contact => contact.id === id);
      },
    },
    computed:{
      getAccount() {
        return this.accountItems.find(accountItem => {
          if (accountItem.value === this.account) {
            return accountItem;
          }
        })
      },
      subtotal() {
        let total = 0;

        this.lines.forEach(line => {
          let discount = 1 - (0.01 * line.discount);
          total += line.quantity * line.price * discount;
        });

        return total.toFixed(2);
      },
      vat() {
        return (this.subtotal * 0.25).toFixed(2);
      },
      total() {
        if (this.account === 'withVat') {
          return (parseFloat(this.subtotal) + parseFloat(this.vat)).toFixed(2);
        } else {
          return parseFloat(this.subtotal).toFixed(2);
        }
      }
    },

    methods: {

      getInvoice() {
        const data = {
          id: this.$route.params.id,
          params: {
            type: 'sell'
          }
        };
        this.$store.dispatch('invoices/getById', data).then(invoice => {
          this.invoice = invoice;
          this.chosenContactId = invoice.contact.id;
          this.chosenContact = invoice.contact;


          if (this.type === 'reminder') {
            this.lines.push({
              quantity: 1,
              unit: 'piece',
              price: this.invoice.subtotal,
              freetext: `Faktura #${this.invoice.invoice_number}`,
              isFreetext: true,
              discount: 0
            });
            this.lines.push({
              quantity: 1,
              unit: 'piece',
              price: 100,
              freetext: 'Rykker',
              isFreetext: true,
              discount: 0
            });
          } else if (this.type === 'creditNote') {
            this.invoice.items.forEach(item => {
              this.lines.push({
                quantity: item.quantity,
                unit: item.unit,
                price: item.price,
                discount: item.discount,
                itemId: item.item.id,
                freetext: item.freetext,
                isFreetext: item.item ? false : true
              });
            })
          }
        })
      },

      setPreview(isPreview) {
        if (isPreview) {
          this.stopTour();
        }

        this.preview = isPreview;
      },
      stopTour() {
        this.$tours['tour'].stop();
      },
      startTour() {
        this.$tours['tour'].start();
      },
      create() {
        this.errors = [];

        let contact = '';
        if (this.type === 'sell' || this.type === 'creditNote' || this.type === 'reminder') {
          contact = 'kunde';
        } else if (this.type === 'buy') {
          contact = 'leverandør';
        }

        if (!this.chosenContactId) this.errors.push('Du skal vælge en ' + ' ' + contact);

        if (this.type === 'sell' || this.type === 'reminder') {
          if (!this.paymentDate) this.errors.push('Betalingsdato er påkrævet');
        }

        if (!this.headline) this.errors.push('Overskrift er påkrævet');
        if (!this.lines.length) this.errors.push('Du skal lave mindst én fakturalinje');
        else {
          this.lines.forEach((line, index) => {
            let currentLine = index + 1;
            if (!line.itemId && !line.freetext) this.errors.push('Fakturalinje'+` #${currentLine}` + ' ' + 'mangler et produkt eller fritekst');
            if (!line.price) this.errors.push('Fakturalinje'+` #${currentLine}` + ' ' + 'mangler en pris' );
          })
        }

        if (this.errors.length) return;

        const data = {
          headline: this.headline,
          lines: this.lines,
          invoiceNumber: this.invoiceNumber,
          contactId: this.chosenContactId,
          account: this.account,
          subtotal: this.subtotal,
          vat: this.vat,
          total: this.total,
          type: this.type,
          discount: this.discount
        };


        if (this.type === 'sell') {
          data['paymentDate'] = this.paymentDate;
        } else if (this.type === 'reminder') {
          data['paymentDate'] = this.paymentDate;
          data['invoiceId'] = this.invoice.id;
        } else if (this.type === 'creditNote') {
          data['invoiceId'] = this.invoice.id;
        }

        this.$store.dispatch(`invoices/create`, data).then(invoice => {
          let text = '';
          let name = '';
          if (this.type === 'sell') {
            text = 'salgsfaktura';
            name = 'sell-invoice';
          } else if (this.type === 'creditNote') {
            text = 'kreditnota';
            name = 'credit-note';
          } else if (this.type === 'reminder') {
            text = 'rykker';
            name = 'reminder';
          } else if (this.type === 'buy') {
            text = 'købsfaktura';
            name = 'buy-invoice';
          }
          this.$notify('Du har nu oprettet denne' + ' ' + text);

          this.$router.push({
            name: name,
            params: {
              id: invoice.id
            }
          })
        })
      },
      checkLineQuantity(line, index) {
        if (line.freetext) return;

        let item = {};

        if (this.type === 'sell') {
          item = this.items.find(_item => _item.id === line.itemId);
        } else {
          item = this.invoice.items[index];
        }

        if (line.quantity > item.quantity) {
          line.quantity = item.quantity;
        }
      },
      getLinePrice(line) {
        let base = line.quantity * line.price;
        let discount = 1 - (0.01 * line.discount);

        return (base * discount).toFixed(2);
      },
      getUnit(key) {
        return this.units.find(unit => unit.key === key);
      },
      getItem(itemId) {
        return this.items.find(item => item.id === itemId);
      },
      openCreateItemDialog(lineIndex) {
        this.currentLineIndex = lineIndex;
        this.createItemDialog = true;
      },
      onItemCreated(item) {
        this.items.push(item);
        this.lines[this.currentLineIndex].item = item;
        this.lines[this.currentLineIndex].itemId = item.id;
        this.lines[this.currentLineIndex].price = item.price;
        this.lines[this.currentLineIndex].unit = item.unit;
        this.lines[this.currentLineIndex].account = item.account;
        this.createItemDialog = false;
      },
      deleteLine(index) {
        this.lines.splice(index, 1);
      },
      itemChosen(index) {
        const itemId = this.lines[index].itemId;
        const item = this.items.find(_item => _item.id === itemId);
        this.lines[index].item = item;
        this.lines[index].price = item.price;
        this.lines[index].unit = item.unit ? item.unit : 'piece';
        this.lines[index].account = item.account ? item.account : 'withVat';
      },
      addLine() {
        this.lines.push({
          item: null,
          quantity: 1,
          unit: 'piece',
          price: 0,
          discount: 0,
          itemId: '',
          isFreetext: false,
          freetext: '',
          account: 'withVat',
          isAddingComments: false,
          comments: '',
        });
      },
      onCustomerCreated(contact) {
        this.contacts.unshift(contact);
        this.chosenContactId = contact.id;
        this.chosenContact = contact;
        this.createCustomerDialog = false;
      },
      onSupplierCreated(supplier) {
        this.contacts.unshift(supplier);
        this.chosenContactId = supplier.id;
        this.chosenContact = supplier;
        this.createSupplierDialog = false;
      },
      getCustomers() {
        this.$store.dispatch('contacts/browse').then(contacts => {
          this.contacts = contacts;
        })
      },
      getSuppliers() {
        this.$store.dispatch('suppliers/browse').then(suppliers => {
          this.contacts = suppliers;
        })
      },
      getItems() {
        this.$store.dispatch('items/browse').then(items => {
          this.items = items;
        })
      }
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
    .invoice .top2 .invoiceNumber {
        width: 300px;
    }
    .invoice .top3 .account {
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
