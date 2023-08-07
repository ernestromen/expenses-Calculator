<template>
  <div class="app">
    <div class="row justify-content-center mx-5">
      <div class="col-4 text-center">
        <div
          class="alert alert-danger"
          role="alert"
          :style="{ display: searchFailed ? 'block' : 'none' }"
        >
          {{ searchFailedMessage }}
        </div>
        <form method="post">
          <select
            @change="searchExpenses"
            class="custom-select"
            name="purchasetype"
            v-model="selectedTimeOptionSearch"
          >
            <option diabled selected value="">Choose Timeline</option>
            <option value="Today">Today</option>
            <option value="Mounthly">Mounthly</option>
            <option value="Yearly">Yearly</option>
          </select>

          <select
            @change="searchExpenses"
            name="purchasetype"
            class="custom-select"
            v-model="selectedTypeOptionSearch"
          >
            <option disabled selected value="">Choose purchasetype</option>
            <option value="All">All</option>
            <option value="Utilities">Utilities</option>
            <option value="Recreational">Recreational</option>
            <option value="Food">Food</option>
          </select>
        </form>
      </div>
      <div class="col-4">
        <div
          class="alert alert-success"
          role="alert"
          :style="{ display: responseAddingSucceeded ? 'block' : 'none' }"
        >
          Successfully added an expense
        </div>
        <div
          class="alert alert-danger"
          role="alert"
          :style="{ display: responseAddingFailed ? 'block' : 'none' }"
        >
          {{ responseAddingFailedMessage }}
        </div>
        <form
          method="post"
          action="http://localhost:8000/"
        >
          <select
            v-model="selectedOptionAddingExpense"
            class="custom-select"
            name="purchasetype"
          >
            <option disabled :value="null">Choose purchasetype</option>
            <option value="Utilities">Utilities</option>
            <option value="Recreational">Recreational</option>
            <option value="Food">Food</option>
          </select>
          <div class="form-group">
            <input
              name="amount"
              class="form-control"
              type="number"
              placeholder="amount"
              v-model="typedAmount"
            />
          </div>
          <div class="form-group">
            <input
              class="btn btn-primary w-100"
              name="submit"
              type="submit"
              value="Add expense"
              @click="addExpense"
            />
          </div>
        </form>
      </div>
    </div>
    <br />
    <br />
    <div
      class="alert alert-danger w-50 m-auto"
      role="alert"
      :style="{ display: responseNetworkError ? 'block' : 'none' }"
    >
      {{ responseNetworkErrorMessage }}
    </div>
    <div :style="{ display: expenses.length === 0 ? 'none' : 'block' }">
      <h1 class="mb-5 text-center">Expenses</h1>
      <div class="loader-container">
        <div class="loader"></div>
      </div>
      <div
        class="alert alert-success w-50 m-auto"
        role="alert"
        :style="{ display: responseDeleteSucceeded ? 'block' : 'none' }"
      >
        Successfully deleted an expense
      </div>
      <div
        class="alert alert-success w-50 m-auto"
        role="alert"
        :style="{ display: responseDeleteFailed ? 'block' : 'none' }"
      >
        Successfully deleted an expense
      </div>
      <table class="table w-50 text-center m-auto border">
        <thead>
          <tr>
            <th scope="col">
              <input
                type="checkbox"
                @change="checkCheckedIds(null)"
                v-model="isMasterChecked"
              />
            </th>
            <th scope="col">Type</th>
            <th scope="col">Amount</th>
            <th scope="col">Created at</th>
            <th scope="col">
              <button
                class="btn btn-danger rounded-circle"
                title="delete all checked expenses"
                @click="deleteSelectedCheckboxes"
              >
                <i class="fa fa-trash" aria-hidden="true"></i>
              </button>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr class="border" v-for="(expense, index) in expenses" :key="index">
            <input
              :id="expense.id"
              class="form-check-input"
              type="checkbox"
              @change="checkCheckedIds(expense.id)"
              ref="myCheckbox"
            />
            <td>{{ expense.purchasetype }}</td>
            <td>{{ expense.amount }}</td>
            <td>{{ expense.created_at }}</td>
            <td>
              <button
                class="btn btn-danger fs-1 text rounded-circle"
                type="submit"
                @click="deleteExpense(expense.id)"
              >
                <i class="fa fa-trash" aria-hidden="true"></i>
              </button>
            </td>
          </tr>
          <tr class="border border-info">
            <td class="text-left">Total expenses</td>
            <td></td>
            <td>{{ totalSumOfExpenses["totalSum"] }}</td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
alert('asd');
import axios from "axios";
import moment from "moment";
import "font-awesome/css/font-awesome.css";

export default {
  name: "mainPage",
  data: function () {
    return {
      expenses: [],
      totalSumOfExpenses: "",
      checkedIds: [],
      selectedTypeOptionSearch: "",
      selectedTimeOptionSearch: "",
      selectedOptionAddingExpense: null,
      typedAmount: null,
      isMasterChecked: false,
      allTheExpensesId: [],
      currentDateTime: "",
      //shortern
      responseAddingSucceeded: false,
      responseAddingFailed: false,
      responseAddingFailedMessage: "",
      responseDeleteSucceeded: false,
      responseDeleteFailed: false,
      responseNetworkError: false,
      responseNetworkErrorMessage: "",
      searchFailed: false,
      searchFailedMessage: "",
    };
  },
  methods: {
    checkCheckedIds: function (id) {
      if (id !== null) {
        this.checkedIds = this.checkedIds.includes(id)
          ? this.checkedIds.filter((e) => e !== id)
          : [...this.checkedIds, ...[id]];
      } else {
        this.isMasterChecked
          ? this.$refs.myCheckbox.map((e) => {
              e.checked = true;
            })
          : this.$refs.myCheckbox.map((e) => {
              e.checked = false;
            });
        this.checkedIds = this.isMasterChecked
          ? this.expenses.map((e) => e.id)
          : [];
      }
    },
    getAllExpenses: function () {
      axios
        .get("http://localhost:8000/")
        .then((response) => {
          this.responseNetworkError = false;
          this.expenses = response.data.expenses;
        })
        .catch((error) => {
          this.responseNetworkError = true;
          this.responseNetworkErrorMessage = this.showFailedApiMessage(error);
        });
    },
    getTotalSum: function () {
      axios
        .get("http://localhost:8000/")
        .then((response) => {
          this.responseNetworkError = false;
          this.totalSumOfExpenses = response.data.totalSum[0];
        })
        .catch((error) => {
          this.responseNetworkError = true;
          this.responseNetworkErrorMessage = this.showFailedApiMessage(error);
        });
    },
    addExpense: function (e) {
      e.preventDefault();
      let selectedOptionAddingExpense = this.selectedOptionAddingExpense;
      let typedAmount = this.typedAmount;
      let currentDateTime = this.currentDateTime;
      
      if (!typedAmount && !isNaN(typedAmount)) {
        this.responseAddingFailed = true;
        this.responseAddingFailedMessage = "*input must be a number";
        setTimeout(() => {
          this.responseAddingSucceeded = false;
          this.responseAddingFailed = false;
        }, 2500);
        return false;
      }
      axios
        .post(
          "http://localhost:8000/",
          { selectedOptionAddingExpense, typedAmount, currentDateTime },
          { headers: { "content-type": "application/x-www-form-urlencoded" } }
        )
        .then((response) => {
          this.totalSumOfExpenses = response.data.totalSum[0];
          response.status == "200"
            ? (this.responseAddingSucceeded = true)
            : (this.responseAddingFailed = true);

          setTimeout(() => {
            this.responseAddingSucceeded = false;
            this.responseAddingFailed = false;
          }, 2500);

          let newExpense = {
            id: response.data.lastInsertedId,
            purchasetype: selectedOptionAddingExpense,
            amount: typedAmount,
            created_at: currentDateTime,
          };
          this.expenses = [...this.expenses, newExpense];
        })
        .catch((error) => {
          this.responseAddingFailed = true;
          this.responseAddingFailedMessage = this.showFailedApiMessage(error);
          setTimeout(() => {
            this.responseAddingSucceeded = false;
            this.responseAddingFailed = false;
          }, 2500);
        });
      this.typedAmount = "";
    },

    deleteExpense: function (id) {
      axios
        .delete(
          `http://localhost:8000/${id}`,
          { data: id },
          { headers: { "content-type": "application/json" } }
        )
        .then((response) => {
          let fillteredExpenses = this.expenses.filter((e) => e.id !== id);
          this.expenses = fillteredExpenses;
          this.totalSumOfExpenses = response.data.totalSum[0];
          response.status == "200"
            ? (this.responseDeleteSucceeded = true)
            : (this.responseDeleteFailed = true);
          setTimeout(() => {
            this.responseDeleteSucceeded = false;
            this.responseDeleteFailed = false;
          }, 2500);
        })
        .catch((error) => {
          this.responseDeleteFailed = this.showFailedApiMessage(error);
          setTimeout(() => {
            this.responseDeleteFailed = "";
          }, 2500);
        });
    },
    searchExpenses: function (e) {
      e.preventDefault();
      this.selectedTypeOptionSearch ? this.selectedTypeOptionSearch : "";
      axios
        .get(
          `http://localhost:8000/?type=${this.selectedTypeOptionSearch}&time=${this.selectedTimeOptionSearch}`
        )
        .then((response) => {
          this.expenses = response.data.expenses;
          this.totalSumOfExpenses = response.data.totalSum[0];
        })
        .catch((error) => {
          this.searchFailed = true;
          this.searchFailedMessage = this.showFailedApiMessage(error);
          setTimeout(() => {
            this.searchFailed = false;
          }, 2500);
        });
    },
    showFailedApiMessage: function (error) {
      return `${error["message"]} : ${error["name"]} `;
    },
    deleteSelectedCheckboxes: function () {
      if (this.checkedIds.length > 0) {
        this.isMasterChecked = false;
        axios
          .delete(
            `http://localhost:8000/delete-all-check-ids`,
            { data: this.checkedIds },
            { headers: { "content-type": "application/json" } }
          )
          .then((response) => {
            console.log(response);
            this.expenses = this.expenses.filter(
              (e) => !this.checkedIds.includes(e.id)
            );
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
  },
  mounted() {
    this.getAllExpenses();
    this.getTotalSum();
    this.currentDateTime = moment().format("YYYY-MM-DD HH:mm:ss");
  },
};
</script>
