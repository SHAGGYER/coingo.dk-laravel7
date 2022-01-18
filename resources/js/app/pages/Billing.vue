<template>
    <div>
        <h1>Betalinger</h1>

        <v-alert class="mt-5" :value="!$store.state.isSubscribed" color="purple" dark>
            For at kunne styre dine købs- og salgsfakturer, skal du have et aktivt abonnement.
        </v-alert>

        <v-card class="mt-5">
            <v-card-title v-if="$store.state.isSubscribed">
                Nuværende Plan: {{ currentPlanText.text }}
            </v-card-title>
            <v-divider class="my-3" v-if="$store.state.isSubscribed"></v-divider>
            <v-card-text v-if="$store.state.onGracePeriod">
                Abonnement udløber den: {{ $store.state.user.subscription.ends_at }}
            </v-card-text>
            <v-card-actions v-if="$store.state.user.card || $store.state.isSubscribed">
                <v-spacer></v-spacer>
                <v-btn color="purple"
                       v-if="$store.state.user.card && !$store.state.isSubscribed && !$store.state.onGracePeriod"
                       dark
                       @click="planDialog = true"
                       id="v-step-add-plan">
                    Tilføj Plan
                </v-btn>
                <v-btn v-if="$store.state.isSubscribed && !$store.state.onGracePeriod" color="purple" dark @click="planDialog = true">
                    Skift Plan
                </v-btn>
                <v-btn color="error" v-if="$store.state.isSubscribed && !$store.state.onGracePeriod" @click="cancelPlanDialog = true">
                    Annuller Plan
                </v-btn>
                <v-btn color="warning" v-else-if="$store.state.onGracePeriod" @click="resumePlanDialog = true">
                    Genoptag Plan
                </v-btn>
            </v-card-actions>
        </v-card>

        <v-divider class="my-5"></v-divider>

        <v-card>
            <v-card-title>Kreditkort</v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <div v-if="card && $store.state.user">
                    <div class="cardFront mx-auto mt-3">
                        <div class="card__brand text-capitalize">{{ card.card.brand }}</div>
                        <div class="card__front card__part">
                            <p class="card_numer">**** **** **** {{ card.card.last4 }}</p>
                            <div class="card__space-75">
                                <span class="card__label">Kortholder</span>
                                <p class="card__info">{{ $store.state.user.name }}</p>
                            </div>
                            <div class="card__space-25">
                                <span class="card__label">Udløber</span>
                                <p class="card__info">{{ card.card.exp_month + '/' + card.card.exp_year }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <span v-if="!isAddingCard" class="text-secondary">Intet kreditkort</span>

                    <v-form @submit.prevent="addCard" v-if="isAddingCard" class="card">
                        <div class="fieldset">
                            <div id="card-number" class="field empty"></div>
                            <div id="card-expiry" class="field empty third-width"></div>
                            <div id="card-cvc" class="field empty third-width"></div>
                        </div>
                        <span id="card-errors">{{ errors[0] }}</span>
                    </v-form>
                </div>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn v-if="card" @click="deleteCardDialog = true" color="error" id="v-step-delete-card">Slet Kort</v-btn>
                <v-btn v-else-if="!isAddingCard" @click="isAddingCard = true" color="purple" dark>Nyt Kort</v-btn>
                <v-btn v-else @click="addCard" color="purple" dark>Tilføj Kort</v-btn>
                <v-btn v-if="isAddingCard" color="error" @click="isAddingCard = false">Annullér</v-btn>
            </v-card-actions>
        </v-card>

        <v-divider class="my-5"></v-divider>

        <v-btn block class="mt-5" color="purple" dark @click="getInvoices">Hent Fakturer</v-btn>

        <v-dialog v-model="invoicesDialog" width="700">
            <v-card>
                <v-card-title>
                    <h3>Dine fakturer (på engelsk)</h3>
                </v-card-title>
                <v-card-text>
                    <v-simple-table class="mt-5" v-if="invoices.length">
                        <template v-slot:default>
                            <thead>
                            <tr>
                                <th class="text-left">Dato</th>
                                <th class="text-left">Total</th>
                                <th class="text-left">Download</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="invoice in invoices" :key="invoice.id">
                                <td>{{ invoice.date }}</td>
                                <td>{{ invoice.total }}</td>
                                <td><v-chip color="purple" dark @click="downloadInvoice(invoice.id)">Download</v-chip></td>
                            </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="invoicesDialog = false">Annullér</v-btn>
                    <v-btn color="purple" dark @click="getInvoices">Hent Fakturer</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="planDialog" width="700">
            <v-card>
                <v-card-title>
                    {{ $store.state.isSubscribed ? 'Skift Plan' : 'Tilføj Plan' }}
                </v-card-title>
                <v-card-text>
                    <Errors :errors="errors"></Errors>

                    <v-select label="Vælg Plan"
                              v-model="currentPlan"
                              :items="plans"
                              item-text="text"
                              item-value="value">
                    </v-select>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="planDialog = false">Annullér</v-btn>
                    <v-btn v-if="$store.state.isSubscribed" color="purple" dark @click="changePlanDialog = true">
                        Skift Plan
                    </v-btn>
                    <v-btn v-else color="purple" dark @click="addPlanDialog = true">Tilføj Plan</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="cancelPlanDialog" width="300">
            <ConfirmDialog @closed="cancelPlanDialog = false" @confirmed="cancelPlan"></ConfirmDialog>
        </v-dialog>
        <v-dialog v-model="changePlanDialog" width="300">
            <ConfirmDialog @closed="changePlanDialog = false" @confirmed="changePlan"></ConfirmDialog>
        </v-dialog>
        <v-dialog v-model="addPlanDialog" width="300">
            <ConfirmDialog @closed="addPlanDialog = false" @confirmed="addPlan"></ConfirmDialog>
        </v-dialog>
        <v-dialog v-model="deleteCardDialog" width="300">
            <ConfirmDialog @closed="deleteCardDialog = false" @confirmed="deleteCard"></ConfirmDialog>
        </v-dialog>
        <v-dialog v-model="resumePlanDialog" width="300">
            <ConfirmDialog @closed="resumePlanDialog = false" @confirmed="resumePlan"></ConfirmDialog>
        </v-dialog>
        <v-tour name="tourAddedCard" :steps="stepsAddedCard" :options="tourOptions">
        </v-tour>
    </div>
</template>

<script>
  import Errors from "../../common/Errors";
  import ConfirmDialog from "../../common/ConfirmDialog";
  export default {
    name: "Billing",
    components: {ConfirmDialog, Errors},
    data() {
      return {
        stripe: null,
        cardElement: null,
        submittingPayment: false,
        invoices: [],
        planDialog: false,
        changePlanDialog: false,
        cancelPlanDialog: false,
        addPlanDialog: false,
        deleteCardDialog: false,
        resumePlanDialog: false,
        invoicesDialog: false,
        errors: [],
        name: '',
        address: '',
        city: '',
        zip: '',
        isAddingCard: false,
        currentPlan: '',
        card: null,
        plans: [
          {
            text: 'Månedlig -- 99kr / måned',
            value: 'coingo-monthly'
          },
          {
            text: 'Kvartal -- 279kr / kvartal',
            value: "coingo-quarterly"
          },
          {
            text: 'Årlig -- 999kr / år',
            value: "coingo-yearly"
          }
        ],
        stepsAddedCard: [
          {
            target: '#v-step-add-plan',
            content: `Nu kan du tilføje en plan her.`
          },
          {
            target: '#v-step-delete-card',
            content: `Her kan du slette dit kort.`
          },
        ],
        tourOptions: {
          labels: {
            buttonSkip: 'Forlad guiden',
            buttonPrevious: 'Forrige',
            buttonNext: 'Næste',
            buttonStop: 'Færdig'
          }
        }
      }
    },
    mounted() {
      if (!this.$store.state.user.company) {
        this.$router.push('/companies/create');
        return;
      }

      this.card = this.$store.state.user.card;
    },
    computed: {
      currentPlanText() {
        if (!this.$store.state.user.subscription) return;

        return this.plans.find(plan => {
          if (plan.value === this.$store.state.user.subscription.stripe_plan) {
            return plan.text;
          }
        });
      }
    },
    watch: {
      isAddingCard(val) {
        if (val) {
          this.$store.commit('setLoading', true);
          setTimeout(() => {
            this.$store.commit('setLoading', false);
            this.stripe = Stripe(STRIPE_KEY);
            const elements = this.stripe.elements({
              locale: 'da'
            });


            let elementStyles = {
              base: {
                color: '#fff',
                fontWeight: 600,
                fontFamily: 'Quicksand, Open Sans, Segoe UI, sans-serif',
                fontSize: '16px',
                fontSmoothing: 'antialiased',

                ':focus': {
                  color: '#424770',
                },

                '::placeholder': {
                  color: '#9BACC8',
                },

                ':focus::placeholder': {
                  color: '#CFD7DF',
                },
              },
              invalid: {
                color: '#fff',
                ':focus': {
                  color: '#FA755A',
                },
                '::placeholder': {
                  color: '#FFCCA5',
                },
              },
            };

            let elementClasses = {
              focus: 'focus',
              empty: 'empty',
              invalid: 'invalid',
            };

            let errorMessage = document.querySelector('#card-errors');
            let _elements = [];

            this.cardElement = elements.create('cardNumber', {
              style: elementStyles,
              classes: elementClasses,
            });
            this.cardElement.mount('#card-number');

            _elements.push(this.cardElement);

            let cardExpiry = elements.create('cardExpiry', {
              style: elementStyles,
              classes: elementClasses,
            });
            cardExpiry.mount('#card-expiry');
            _elements.push(cardExpiry);

            let cardCvc = elements.create('cardCvc', {
              style: elementStyles,
              classes: elementClasses,
            });
            cardCvc.mount('#card-cvc');
            _elements.push(cardCvc);

            _elements.forEach(element => {
              element.addEventListener('change', event => {
                if (event.error) {
                  errorMessage.textContent = event.error.message;
                } else {
                  errorMessage.textContent = '';
                }
              });
            });
          }, 500);
        }
      }
    },
    methods: {
      resumePlan() {
        this.$store.dispatch('users/resumePlan').then(() => {
          this.$notify('Du har genoptaget din plan');
          this.resumePlanDialog = false;
        })
      },
      deleteCard() {
        this.$store.dispatch('users/deleteCard').then(() => {
          this.$notify('Du har nu slettet dit kort');
          this.card = null;
          this.deleteCardDialog = false;
        })
      },
      addCard() {
        this.submittingPayment = true;
        this.$store.commit('setLoading', true);
        this.stripe.handleCardSetup(this.$store.state.user.stripeClientSecret, this.cardElement)
          .then(result => {
            if (result.error) {
              // Inform the user if there was an error.
              let errorElement = document.getElementById('card-errors');
              errorElement.textContent = result.error.message;
              this.submittingPayment = false;
              this.$store.commit('setLoading', false);
            } else {
              const paymentMethod = result.setupIntent.payment_method;
              const data = {
                paymentMethod
              };
              this.$store.dispatch('users/addCard', data).then(card => {
                this.submittingPayment = false;
                this.$store.commit('setLoading', false);
                this.card = card;
                this.$notify('Du har nu tilføjet et kort');
                this.isAddingCard = false;
                if (!this.$store.state.isSubscribed){
                  this.$tours['tourAddedCard'].start();

                }
              })

            }
          });
      },
      getCardInfo() {
        this.$store.dispatch('users/getCardInfo').then(card => {
          this.card = card.paymentMethod;
        })
      },
      downloadInvoice(invoiceId) {
        window.open(BASE_URL + '/users/invoice/'+invoiceId, '_blank');
      },
      getInvoices() {
        this.$store.dispatch('users/getInvoices').then(response => {
          this.invoices = response.invoices;
          this.invoicesDialog = true;

        })
      },
      addPlan() {
        this.addPlanDialog = false;
        this.errors = [];

        if (!this.currentPlan) this.errors.push('Du skal vælge en plan');
        if (this.errors.length) return;

        const data = {
          plan: this.currentPlan
        };
        this.$store.dispatch('users/addPlan', data).then(() => {
          this.$notify('Du har nu tilføjet denne plan');
          this.addPlanDialog = false;
          this.planDialog = false;
          this.$tours['tourAddedPlan'].start();
        }).catch(error => {
          this.errors.push(error);
        })
      },
      cancelPlan() {
        this.cancelPlanDialog = false;
        this.errors = [];
        this.$store.dispatch('users/cancelPlan').then(() => {
          this.$notify('Du har nu annulleret din plan');
          this.cancelPlanDialog = false;
        })
      },
      changePlan() {
        this.changePlanDialog = false;
        this.errors = [];

        if (!this.currentPlan) this.errors.push('Du skal vælge en plan');
        if (this.errors.length) return;
        if (this.currentPlan === this.$store.state.user.subscription.stripe_plan) {
          this.errors.push('Du kan ikke vælge din nuværende plan');
        }
        if (this.errors.length) return;

        const data = {
          plan: this.currentPlan
        };

        this.$store.dispatch('users/changePlan', data).then(() => {
          this.$notify('Du har nu skiftet din plan');
          this.changePlanDialog = false;
          this.planDialog = false;
        }).catch(error => {
          this.errors.push(error);
        });
      }
    }
  }
</script>

<style scoped>
    .card {
        background-color: #525f7f;
        text-align: center;

    }

    .card * {
        font-family: Quicksand, Open Sans, Segoe UI, sans-serif;
        font-size: 16px;
        font-weight: 600;
    }

    .card .fieldset {
        margin: 0 15px;
        padding: 20px;
        border-style: none;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-flow: row wrap;
        flex-flow: row wrap;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }

    .card .field {
        padding: 10px 20px 11px;
        background-color: #7488aa;
        width: 100%;
    }

    .card .field.half-width {
        width: calc(50% - (5px / 2));
    }

    .card .field.third-width {
        width: calc(33% - (5px / 3));
    }

    .card .field + .field {
        margin-top: 6px;
    }

    .card .field.focus,
    .card .field:focus {
        color: #424770;
        background-color: #f6f9fc;
    }

    .card .field.invalid {
        background-color: #fa755a;
    }

    .card .field.invalid.focus {
        background-color: #f6f9fc;
    }

    .card .field.focus::-webkit-input-placeholder,
    .card .field:focus::-webkit-input-placeholder {
        color: #cfd7df;
    }

    .card .field.focus::-moz-placeholder,
    .card .field:focus::-moz-placeholder {
        color: #cfd7df;
    }

    .card .field.focus:-ms-input-placeholder,
    .card .field:focus:-ms-input-placeholder {
        color: #cfd7df;
    }

    .card input, .card button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        outline: none;
        border-style: none;
    }

    .card input {
        color: #fff;
    }

    .card input::-webkit-input-placeholder {
        color: #9bacc8;
    }

    .card input::-moz-placeholder {
        color: #9bacc8;
    }

    .card input:-ms-input-placeholder {
        color: #9bacc8;
    }

    .card button {
        display: block;
        width: calc(100% - 30px);
        height: 40px;
        margin: 0 15px;
        background-color: #fcd669;
        border-radius: 20px;
        color: #525f7f;
        font-weight: 600;
        text-transform: uppercase;
        cursor: pointer;
    }

    .card button:active {
        background-color: #f5be58;
    }

    .card #card-errors {
        color: #fff;
    }

    .card .success .icon .border {
        stroke: #fcd669;
    }

    .card .success .icon .checkmark {
        stroke: #fff;
    }

    .card .success .title {
        color: #fff;
    }

    .card .success .message {
        color: #9cabc8;
    }

    .card .success .reset path {
        fill: #fff;
    }




    .cardFront{
        width: 320px;
        height: 190px;
        -webkit-perspective: 600px;
        -moz-perspective: 600px;
        perspective:600px;
        position: relative;
    }

    .card__part{
        box-shadow: 1px 1px #aaa3a3;
        top: 0;
        position: absolute;
        z-index: 1000;
        left: 0;
        display: inline-block;
        width: 320px;
        height: 190px;
        background-image: url('https://image.ibb.co/bVnMrc/g3095.png'), linear-gradient(to right bottom, #fd696b, #fa616e, #f65871, #f15075, #ec4879); /*linear-gradient(to right bottom, #fd8369, #fc7870, #f96e78, #f56581, #ee5d8a)*/
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        border-radius: 8px;

        -webkit-transition: all .5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        -moz-transition: all .5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        -ms-transition: all .5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        -o-transition: all .5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transition: all .5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        -webkit-transform-style: preserve-3d;
        -moz-transform-style: preserve-3d;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        z-index: 0;
    }

    .card__front{
        padding: 18px;
        -webkit-transform: rotateY(0);
        -moz-transform: rotateY(0);
    }

    .card__black-line {
        margin-top: 5px;
        height: 38px;
        background-color: #303030;
    }

    .card__logo {
        height: 16px;
    }

    .card__front-logo{
        position: absolute;
        top: 18px;
        right: 18px;
    }
    .card__square {
        border-radius: 5px;
        height: 30px;
    }

    .card_numer {
        display: block;
        width: 100%;
        word-spacing: 4px;
        font-size: 20px;
        letter-spacing: 2px;
        color: #fff;
        text-align: center;
        margin-bottom: 20px;
        margin-top: 20px;
    }

    .card__space-75 {
        width: 75%;
        float: left;
    }
    .card__space-25 {
        width: 25%;
        float: left;
    }

    .card__brand {
        position: absolute;
        bottom: 5px;
        right: 5px;
        z-index: 1;
        color: white;
    }

    .card__label {
        font-size: 10px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.8);
        letter-spacing: 1px;
    }

    .card__info {
        margin-bottom: 0;
        margin-top: 5px;
        font-size: 16px;
        line-height: 18px;
        color: #fff;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

</style>
