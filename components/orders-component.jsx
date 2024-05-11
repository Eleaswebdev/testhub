import React, { useState, useEffect } from 'react';
import { Table, TableHead, TableBody, TableRow, TableCell, IconButton, TextField, Button } from '@material-ui/core';
import EditIcon from '@material-ui/icons/Edit';
import { authenticateUser } from './authService';

const OrdersComponent = () => {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [editingOrder, setEditingOrder] = useState(null);
  const [newTitle, setNewTitle] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [sortBy, setSortBy] = useState('');
  const [sortOrder, setSortOrder] = useState('asc');
  const [currentPage, setCurrentPage] = useState(1);
  const [itemsPerPage, setItemsPerPage] = useState(5);

  useEffect(() => {
    const fetchOrders = async () => {
      try {
        const response = await fetch('http://hubwppool.local/wp-json/wp/v2/orders');
        if (!response.ok) {
          throw new Error('Failed to fetch orders');
        }
        const data = await response.json();
        const transformedData = data.map(order => ({
          ...order,
          customer_name: `${order.first_name} ${order.last_name}`.trim(), // Concatenate first_name and last_name
          date: new Date(order.date).toLocaleDateString(), 
          shipping_date: new Date(new Date(order.date).getTime() + 14 * 24 * 60 * 60 * 1000).toLocaleDateString(), // Calculate shipping date 2 weeks from order date
        }));
        setOrders(transformedData);
        setLoading(false);
      } catch (error) {
        setError(error);
        setLoading(false);
      }
    };

    fetchOrders();
  }, []);

  const handleEdit = (orderId, currentTitle) => {
    setEditingOrder(orderId);
    setNewTitle(currentTitle);
  };

  const handleTitleChange = (event) => {
    setNewTitle(event.target.value);
  };

  const handleSaveTitle = async () => {
    try {
      const username = 'admin';
      const applicationPassword = 'admin';
      // Authenticate the user
      const authenticated = await authenticateUser(username, applicationPassword);
      if (!authenticated) {
        throw new Error('User authentication failed');
      }
      
      const response = await fetch(`http://hubwppool.local/wp-json/wp/v2/orders/${editingOrder}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          // 'Authorization': authToken, // Include JWT token in the Authorization header
        },
        body: JSON.stringify({ title: newTitle })
      });

      if (!response.ok) {
        throw new Error('Failed to update order title');
      }

      // Update the order title in the frontend...
    } catch (error) {
      console.error('Error updating order title:', error);
      // Handle error
    }
  };

  const handleSearchChange = (event) => {
    setSearchTerm(event.target.value);
    setCurrentPage(1); // Reset current page when search term changes
  };

  const handleSort = (column) => {
    if (sortBy === column) {
      setSortOrder(sortOrder === 'asc' ? 'desc' : 'asc');
    } else {
      setSortBy(column);
      setSortOrder('asc');
    }
  };

  const compareValues = (a, b) => {
    const aValue = typeof a === 'string' ? a.toLowerCase() : a;
    const bValue = typeof b === 'string' ? b.toLowerCase() : b;

    if (typeof a === 'string' && typeof b === 'string') {
      return sortOrder === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
    } else {
      return sortOrder === 'asc' ? a - b : b - a;
    }
  };

  const filteredOrders = orders.filter(order => {
    // Split search term into individual words
    const searchTerms = searchTerm.trim().toLowerCase().split(/\s+/);
    
    // Check if any column value contains any of the search terms
    return Object.values(order).some(value => {
      if (typeof value === 'object' && value.rendered) {
        // If value is an object with 'rendered' property (like title), check its rendered value
        const renderedValue = value.rendered.toLowerCase();
        return searchTerms.every(term => renderedValue.includes(term));
      } else if (typeof value === 'string') {
        // If value is a string, perform a simple search
        return searchTerms.every(term => value.toLowerCase().includes(term));
      } else if (typeof value === 'number') {
        // If value is a number, convert it to string and perform a search
        return searchTerms.every(term => value.toString().includes(term));
      }
      return false;
    });
  });

  const sortedOrders = sortBy ? filteredOrders.sort((a, b) => {
    if (sortBy === 'id' || sortBy === 'status' || sortBy === 'phone' || sortBy === 'shipping_date') {
      return compareValues(a[sortBy], b[sortBy]);
    } else if (sortBy === 'email' || sortBy === 'order_notes' || sortBy === 'customer_name') {
      return compareValues(a[sortBy].toLowerCase(), b[sortBy].toLowerCase());
    } else if (sortBy === 'date') {
      return compareValues(new Date(a[sortBy]).getTime(), new Date(b[sortBy]).getTime());
    } else {
      return compareValues(a[sortBy].rendered, b[sortBy].rendered);
    }
  }) : filteredOrders;

  // Pagination
  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentItems = sortedOrders.slice(indexOfFirstItem, indexOfLastItem);

  const paginate = pageNumber => setCurrentPage(pageNumber);

  return (
    <div>
      <h2>Orders</h2>
      <TextField
        value={searchTerm}
        onChange={handleSearchChange}
        placeholder="Search..."
      />
      <Table>
        <TableHead>
          <TableRow>
            <TableCell onClick={() => handleSort('id')} style={{ fontWeight: 'bold' }}>Order Id</TableCell>
            <TableCell onClick={() => handleSort('customer_name')} style={{ fontWeight: 'bold' }}>Customer Name</TableCell>
            <TableCell onClick={() => handleSort('email')} style={{ fontWeight: 'bold' }}>Email</TableCell>
            <TableCell onClick={() => handleSort('phone')} style={{ fontWeight: 'bold' }}>Phone</TableCell>
            <TableCell onClick={() => handleSort('status')} style={{ fontWeight: 'bold' }}>Status</TableCell>
            <TableCell onClick={() => handleSort('date')} style={{ fontWeight: 'bold' }}>Order Date</TableCell>
            <TableCell onClick={() => handleSort('shipping_date')} style={{ fontWeight: 'bold' }}>Shipping Date</TableCell>
            <TableCell onClick={() => handleSort('order_notes')} style={{ fontWeight: 'bold' }}>Order Notes</TableCell>
            <TableCell>Edit</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {currentItems.length > 0 ? currentItems.map(order => (
            <TableRow key={order.id}>
              <TableCell>{order.order_id}</TableCell>
              <TableCell>{order.customer_name}</TableCell>
              <TableCell>{order.email}</TableCell>
              <TableCell>{order.phone}</TableCell>
              <TableCell>{order.status}</TableCell>
              <TableCell>{order.date}</TableCell>
              <TableCell>{order.shipping_date}</TableCell>
              <TableCell>{order.order_notes}</TableCell>
              <TableCell>
                {editingOrder === order.id ? (
                  <Button onClick={handleSaveTitle}>Save</Button>
                ) : (
                  <IconButton onClick={() => handleEdit(order.id, order.title.rendered)}>
                    <EditIcon />
                  </IconButton>
                )}
              </TableCell>
            </TableRow>
          )) : (
            <TableRow>
              <TableCell colSpan={8}>No orders found with that search term</TableCell>
            </TableRow>
          )}
        </TableBody>
      </Table>
      {/* Pagination */}
      <div>
        {Array.from({ length: Math.ceil(sortedOrders.length / itemsPerPage) }, (_, i) => (
        
          <Button style={{marginTop: '20px'}} key={i} onClick={() => paginate(i + 1)}>
            {i + 1}
          </Button>
        
        ))}
      </div>
    </div>
  );
};

export default OrdersComponent;
