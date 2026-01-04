// Chart.js script for ERP Dashboard
// Make sure Chart.js library is loaded before this file

// Example Sales Chart (Bar)
const salesCtx = document.getElementById('salesChart');
if (salesCtx) {
  new Chart(salesCtx, {
    type: 'bar',
    data: {
      labels: ['Jan','Feb','Mar','Apr','May','Jun'],
      datasets: [{
        label: 'Sales ($)',
        data: [1200, 1500, 1800, 2000, 1700, 2200], // replace with PHP data later
        backgroundColor: '#3498db'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: true },
        title: { display: true, text: 'Monthly Sales' }
      }
    }
  });
}

// Example Profit Chart (Line)
const profitCtx = document.getElementById('profitChart');
if (profitCtx) {
  new Chart(profitCtx, {
    type: 'line',
    data: {
      labels: ['Jan','Feb','Mar','Apr','May','Jun'],
      datasets: [
        {
          label: 'Sales ($)',
          data: [1200, 1500, 1800, 2000, 1700, 2200], // replace with PHP data later
          borderColor: '#2ecc71',
          backgroundColor: '#2ecc71',
          fill: false,
          tension: 0.1
        },
        {
          label: 'Purchases ($)',
          data: [800, 1100, 1300, 1400, 1200, 1600], // replace with PHP data later
          borderColor: '#e74c3c',
          backgroundColor: '#e74c3c',
          fill: false,
          tension: 0.1
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: true },
        title: { display: true, text: 'Sales vs Purchases' }
      }
    }
  });
}
