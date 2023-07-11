<template>
  <div class="app">
    <div
      style="
        display: grid;
        grid-template-columns: auto 20% auto auto;
        gap: 20px;
      "
    >
      <div class="w-75" style="text-align: center">
        <form method="post">
          <select class="custom-select" name="purchasetype">
            <option value="">Choose Timeline</option>
            <option value="Today">Today</option>
            <option value="Mounthly">Mounthly</option>
            <option value="Yearly">Yearly</option>
          </select>
          <select class="custom-select" name="purchasetype">
            <option value="">Choose purchasetype</option>
            <option value="Utilities">Utilities</option>
            <option value="Recreational">Recreational</option>
            <option value="Food">Food</option>
          </select>
        </form>
      </div>
      <div>
        <form method="post">
          <select class="custom-select" name="purchasetype">
            <option value="">Choose purchasetype</option>
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
            />
          </div>
          <div class="form-group">
            <input
              class="btn btn-primary w-100"
              name="submit"
              type="submit"
              value="click"
            />
          </div>
        </form>
      </div>
    </div>
    <br />
    <br />

    <h1 class="mb-5" style="text-align: center">daily expenses</h1>
    <div id="app">
      <div class="loader-container">
        <div class="loader"></div>
      </div>
      <table class="table w-50 text-center m-auto">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Type</th>
            <th scope="col">Amount</th>
            <th scope="col">Created at</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(expense, index) in expenses" :key="index">
            <th scope="row">{{ index }}</th>
            <td>{{ expense.purchasetype }}</td>
            <td>{{ expense.amount }}</td>
            <td>{{ expense.created_at }}</td>
          </tr>
        </tbody>
      </table>
      <button class="btn btn-primary" @click="getExpenseByPurchaseType">
        Click me
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "HelloWorld",
  props: {
    msg: String,
  },
  data: function () {
    return {
      expenses: [],
    };
  },
  methods: {
    getAllExpenses: function () {
      axios
        .get("https://localhost/expenses-calculator/index2.php")
        .then((response) => {
          this.expenses = JSON.parse(JSON.stringify(response.data.expenses));
        })
        .catch((error) => {
          console.error(error);
        });
    },
    getExpenseByPurchaseType: function () {
      axios
        .get(`https://localhost/expenses-calculator/index2.php?type=food`)
        .then((response) => {
          this.expenses = JSON.parse(JSON.stringify(response.data.expenses));
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
  mounted() {
    this.getAllExpenses();
  },
};
</script>
