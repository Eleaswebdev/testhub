// authService.js

const authenticateUser = async (username, applicationPassword) => {
    console.log(applicationPassword);
  try {
    const response = await fetch('http://hubwppool.local/wp-json/wp/v2/users/me', {
      headers: {
        'Authorization': `Basic ${btoa(username + ':' + applicationPassword)}`
      }
    });

    console.log('Response status:', response.status);
console.log('Response body:', await response.text());

    if (!response.ok) {
      throw new Error('EFailed to authenticate user');
    }

    const userData = await response.json();
    console.log('userData',userData); 
    // Check if the user data contains the user's information, indicating successful authentication
    if (userData && userData.id) {
      console.log('User is logged in:', userData);
      return true;
    } else {
      console.log('User is not logged in');
      return false;
    }
  } catch (error) {
    console.error('Error authenticating user:', error);
    return false;
  }
};



export { authenticateUser };
