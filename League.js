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

function getFormString(teamName, fixtures) {
  const recentFixtures = fixtures
    .filter(fixture => fixture.home_team === teamName || fixture.away_team === teamName)
    .slice(-6); // Get the last 6 fixtures for the team

  let formString = '';
  recentFixtures.forEach(fixture => {
    if (fixture.home_team === teamName) {
      formString += fixture.home_score > fixture.away_score ? 'W' : 'L';
    } else {
      formString += fixture.away_score > fixture.home_score ? 'W' : 'L';
    }
  });

  return formString;
}

function updateLeagueTable(data) {
  const leagueTableBody = document.querySelector('#league-table');
  if (leagueTableBody) { // Check if the league table exists
    leagueTableBody.innerHTML = '';

    data.teams.forEach(team => {
      const formString = getFormString(team.name, data.fixtures);
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
        <td class="form">${formString.split('').map(result => `<span class="${result}"></span>`).join('')}</td>
      `;
      leagueTableBody.appendChild(row);
    });
  }
}

function updateTopScorersTable(data) {
  const topScorersTableBody = document.querySelector('#top-scorers-table');
  if (topScorersTableBody) {
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
}

async function updateData() {
  const data = await fetchData('League.json');
  if (data) {
    updateLeagueTable(data);
    updateTopScorersTable(data);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  updateData();
  setInterval(updateData, 5 * 60 * 1000);
});