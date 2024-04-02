async function fetchData(url) {
    const response = await fetch(url);
    const data = await response.json();
    return data;
  }
  
  function updateLeagueTable(data) {
    const leagueTableBody = document.querySelector('#league-table');
    leagueTableBody.innerHTML = '';
  
    data.teams.forEach(team => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${team.name}</td>
        <td>${team.played}</td>
        <td>${team.won}</td>
        <td>${team.drawn}</td>
        <td>${team.lost}</td>
        <td>${team.goals_for}</td>
        <td>${team.goals_against}</td>
        <td>${team.goal_difference}</td>
        <td>${team.points}</td>
      `;
      leagueTableBody.appendChild(row);
    });
  }  
  
  function updateTopScorersTable(data) {
    const topScorersTableBody = document.querySelector('#top-scorers-table tbody');
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
  
  function updateData() {
    fetchData('League.json')
      .then(data => {
        updateLeagueTable(data);
        updateTopScorersTable(data);
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  }
  
  updateData();
  
  setInterval(updateData, 5 * 60 * 1000);
  