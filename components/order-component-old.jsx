// import React, { useState, useEffect } from "react";
// import MaterialTable from "material-table";

// const OrderComponent = () => {
//   const [orders, setOrders] = useState([]);

//   useEffect(() => {
//     const fetchOrders = async () => {
//       try {
//         const response = await fetch(
//           "http://hubwppool.local/wp-json/wp/v2/orders"
//         );
//         if (!response.ok) {
//           throw new Error("Failed to fetch orders");
//         }
//         const data = await response.json();
//         setOrders(data);
//       } catch (error) {
//         console.error("Error fetching orders:", error);
//       }
//     };

//     fetchOrders();
//   }, []);

// const secretKey = "wppool_is_awesome_24";
// const username = 'admin';
// const password = 'HMOD UaHg 5tO6 4OoM Ruxv 8Ifk';
// const authToken = 'Bearer ' + btoa(username + ':' + password);
// //const authToken = "Basic " + btoa("admin:HMOD UaHg 5tO6 4OoM Ruxv 8Ifk");

// const handleRowUpdate = async (newData, oldData) => {
//   console.log("newData", newData);
//   console.log("oldData", oldData);
//   console.log("Hello 00011233");
// const metaData = {
//   'order_notes': "Barry Go to sleep!"
// };
//   try {
    
//     const response = await fetch(
//       `http://hubwppool.local/wp-json/wp/v2/orders/985`,
//       {
//         method: "PUT",
//         headers: {
//           'Content-Type': "application/json",
//           'Authorization': authToken,
//         },
//          body: JSON.stringify({
//     meta: metaData
//   })
//       }
//     );
//     if (!response.ok) {
//       throw new Error("Failed to update order");
//     }
//     const updatedOrder = await response.json();
//     console.log("Inside");
//     console.log("Before map", updatedOrder);
//     const updatedOrders = orders.map((order) =>
//       order.order_id === updatedOrder.order_id ? updatedOrder : order
//     );
//     setOrders(updatedOrders);
//   } catch (error) {
//     console.error("Error updating order:", error);
//   }
// };


//   return (
//     <div>
//       <h1>Recent Orders</h1>
//       <MaterialTable
//         title=""
//         columns={[
//           { title: "ID", field: "order_id", editable: "never" },
//           { title: "First Name", field: "first_name", editable: "never" },
//           { title: "Last Name", field: "last_name", editable: "never" },
//           { title: "Email", field: "email", editable: "never" },
//           { title: "Status", field: "status", editable: "onUpdate" },
//           { title: "Order Notes", field: "order_notes", editable: "onUpdate" },
//         ]}
//         data={orders}
//         editable={{
//           onRowUpdate: handleRowUpdate,
//         }}
//         options={{
//           sorting: true,
//           search: true,
//         }}
//       />
//     </div>
//   );
// };

// export default OrderComponent;
