async function fetchData(url) {
  try {
    const response = await fetch(url);
    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error fetching data:', error);
    return null;
  }
}

function updateTopScorersTable(data) {
  const topScorersTableBody = document.querySelector('#top-scorers-table');
  topScorersTableBody.innerHTML = '';

  data.top_scorers.forEach(scorer => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${scorer.player}</td>
      <td>${scorer.team}</td>
      <td>${scorer.goals}</td>
    `;
    topScorersTableBody.appendChild(row);
  });
}

async function updateData() {
  const data = await fetchData('League.json');
  if (data) {
    updateTopScorersTable(data);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  updateData();
  setInterval(updateData, 5 * 60 * 1000); // Update every 5 minutes
});
