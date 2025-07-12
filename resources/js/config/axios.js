import axios from 'axios';

const instance = axios.create({
  baseURL: '/api', // Basis URL untuk semua request API
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  timeout: 10000 // Timeout 10 detik
});

// Tambahkan interceptor untuk error handling global
instance.interceptors.response.use(
  response => response,
  error => {
    // Log error untuk debugging
    console.error('Axios Error:', error);

    // Tangani error umum
    if (error.response) {
      // Error dari server dengan response
      switch (error.response.status) {
        case 400:
          console.error('Bad Request:', error.response.data);
          break;
        case 401:
          console.error('Unauthorized:', error.response.data);
          break;
        case 403:
          console.error('Forbidden:', error.response.data);
          break;
        case 404:
          console.error('Not Found:', error.response.data);
          break;
        case 500:
          console.error('Server Error:', error.response.data);
          break;
      }
    } else if (error.request) {
      // Request dibuat tapi tidak ada response
      console.error('No response received:', error.request);
    } else {
      // Error dalam setup request
      console.error('Error setting up request:', error.message);
    }

    return Promise.reject(error);
  }
);

export default instance;