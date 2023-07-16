<template>
  <div class="app">
    <div class="row justify-content-center mx-5">
      <div class="col-4 text-center">
        <form method="post">
          <select class="custom-select" name="purchasetype">
            <option :value="null">Choose Timeline</option>
            <option value="Today">Today</option>
            <option value="Mounthly">Mounthly</option>
            <option value="Yearly">Yearly</option>
          </select>
          <select
            v-model="selectedOptionSearch"
            @change="searchExpenses"
            class="custom-select"
            name="purchasetype"
          >
            <option disabled :value="null">Choose purchasetype</option>
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
          action="https://localhost/expenses-calculator/index.php"
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
                @change="getAllCheckboxId"
                v-model="isMasterChecked"
              />
            </th>
            <th scope="col">Type</th>
            <th scope="col">Amount</th>
            <th scope="col">Created at</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr class="border" v-for="(expense, index) in expenses" :key="index">
            <input
              :id="expense.id"
              class="form-check-input"
              type="checkbox"
              @change="checkCheckedIds(expense.id)"
              :checked="isMasterChecked ? true : false"
              :data-row-id="expense.id"
            />
            <td>{{ expense.purchasetype }}</td>
            <td>{{ expense.amount }}</td>
            <td>{{ expense.created_at }}</td>
            <td>
              <input
                class="btn btn-danger fs-1 text"
                type="submit"
                value="Delete row"
                @click="deleteExpense(expense.id)"
              />
            </td>
          </tr>
          <tr class=" border border-info ">
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
import axios from "axios";
import moment from "moment";

export default {
  name: "mainPage",
  data: function () {
    return {
      expenses: [],
      totalSumOfExpenses: "",
      checkedIds: [],
      selectedOptionSearch: null,
      selectedOptionAddingExpense: null,
      typedAmount: null,
      isMasterChecked: false,
      allTheExpensesId: [],
      currentDateTime: "",
      responseAddingSucceeded: false,
      responseAddingFailed: false,
      responseAddingFailedMessage: "",
      responseDeleteSucceeded: false,
      responseDeleteFailed: false,
      responseNetworkError: false,
      responseNetworkErrorMessage: "",
    };
  },
  methods: {
    getAllCheckboxId: function () {
      if (this.isMasterChecked) {
        this.allTheExpensesId = JSON.parse(JSON.stringify(this.expenses)).map(
          (e) => {
            return e[0];
          }
        );
      } else {
        this.allTheExpensesId = [];
      }
    },
    checkCheckedIds: function (id) {
      let filteredId = this.expenses.filter((e) => e.id == id);
      filteredId = JSON.parse(JSON.stringify(filteredId[0]))[0];
      this.checkedIds = [...this.checkedIds, filteredId];
      console.log(
        "these are the checked ids:",
        JSON.parse(JSON.stringify(this.checkedIds))
      );
    },
    getAllExpenses: function () {
      axios
        .get("https://localhost/expenses-calculator/index.php")
        .then((response) => {
          this.responseNetworkError = false;
          this.expenses = response.data.expenses;
        })
        .catch((error) => {
          this.responseNetworkError = true;
          this.responseNetworkErrorMessage = this.showFailedApiMessage(error);
        });
    },
    getTotalSum : function () {
         axios
        .get("https://localhost/expenses-calculator/index.php")
        .then((response) => {
          console.log(response);
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

      axios
        .post(
          "https://localhost/expenses-calculator/index.php",
          { selectedOptionAddingExpense, typedAmount, currentDateTime },
          { headers: { "content-type": "application/x-www-form-urlencoded" } }
        )
        .then((response) => {
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
    },
    deleteExpense: function (id) {
      axios
        .delete(
          `https://localhost/expenses-calculator/index.php/${id}`,
          {},
          { headers: { "content-type": "application/json" } }
        )
        .then((response) => {
          let fillteredExpenses = this.expenses.filter((e) => e.id !== id);
          this.expenses = fillteredExpenses;
          response.status == "200"
            ? (this.responseDeleteSucceeded = true)
            : (this.responseDeleteFailed = true);
          setTimeout(() => {
            this.responseDeleteSucceeded = false;
            this.responseDeleteFailed = false;
          }, 2500);
        })
        .catch((error) => {
           setTimeout(() => {
            this.responseDeleteFailed = this.showFailedApiMessage(error);
          }, 2500);
        });
    },
    searchExpenses: function (e) {
      e.preventDefault();
      axios
        .get(
          `https://localhost/expenses-calculator/index.php?type=${this.selectedOptionSearch}`
        )
        .then((response) => {
          this.expenses = response.data.expenses;
          this.totalSumOfExpenses = response.data.totalSum[0];
        })
        .catch((error) => {
          console.error(error);
        });
    },
    showFailedApiMessage: function (error) {
      return `${error["message"]} : ${error["name"]} `;
    },
  },
  mounted() {
    this.getAllExpenses();
    this.getTotalSum();
    this.currentDateTime = moment().format("YYYY-MM-DD HH:mm:ss");
  },
};
</script>
