import React, { useState } from "react";
import OrderComponent from "../components/order-component-old";
import BasicTable from "../components/orders-component";
import OrdersComponent from "../components/orders-component";

const App = () => {
  return (
    <div class="container">
      {/* <OrderComponent /> */}
      <OrdersComponent />
    </div>
  );
};
export default App;
