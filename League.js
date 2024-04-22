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
    .filter(fixture => (fixture.home_team === teamName || fixture.away_team === teamName) && fixture.home_score !== undefined)
    .sort((a, b) => new Date(b.date) - new Date(a.date))
    .slice(0, 6);

  let formString = '';
  recentFixtures.forEach(fixture => {
    if (fixture.home_team === teamName) {
      formString += fixture.home_score > fixture.away_score ? 'W' : (fixture.home_score < fixture.away_score ? 'L' : 'D');
    } else {
      formString += fixture.away_score > fixture.home_score ? 'W' : (fixture.away_score < fixture.home_score ? 'L' : 'D');
    }
  });

  return formString;
}

function calculateLeagueStatistics(fixtures) {
  const standings = {};
  fixtures.forEach(fixture => {
    const { home_team, away_team, home_score, away_score } = fixture;

    
    if (!standings[home_team]) {
      standings[home_team] = { played: 0, won: 0, drawn: 0, lost: 0, goals_for: 0, goals_against: 0, points: 0 };
    }
    if (!standings[away_team]) {
      standings[away_team] = { played: 0, won: 0, drawn: 0, lost: 0, goals_for: 0, goals_against: 0, points: 0 };
    }

    
    standings[home_team].played++;
    standings[away_team].played++;

    
    if (home_score !== undefined && away_score !== undefined) {
      standings[home_team].goals_for += home_score;
      standings[away_team].goals_for += away_score;
      standings[home_team].goals_against += away_score;
      standings[away_team].goals_against += home_score;
    }

    
    if (home_score > away_score) { 
      standings[home_team].won++;
      standings[away_team].lost++;
      standings[home_team].points += 3;
    } else if (home_score < away_score) { 
      standings[away_team].won++;
      standings[home_team].lost++;
      standings[away_team].points += 3;
    } else if (home_score === away_score) { 
      standings[home_team].drawn++;
      standings[away_team].drawn++;
      standings[home_team].points += 1;
      standings[away_team].points += 1;
    }
  });

  return Object.entries(standings).map(([name, stats]) => ({
    name,
    ...stats,
    goal_difference: stats.goals_for - stats.goals_against
  })).sort((a, b) => b.points - a.points || b.goal_difference - a.goal_difference || b.goals_for - a.goals_for);
}

function calculateTopScorers(fixtures) {
  let scorersMap = {};

  fixtures.forEach(fixture => {
    const collectScores = [...(fixture.home_scorers || []), ...(fixture.away_scorers || [])];

    collectScores.forEach(scorer => {
      const [name, goals] = scorer.includes('(')
        ? [scorer.slice(0, scorer.indexOf(' (')).trim(), parseInt(scorer.slice(scorer.indexOf('(') + 1, scorer.indexOf(')'), 10))]
        : [scorer.trim(), 1];

      scorersMap[name] = (scorersMap[name] || 0) + goals;
    });
  });

  let sortedScorers = Object.entries(scorersMap)
    .sort((a, b) => b[1] - a[1]); 

  let currentRank = 0;
  let previousGoals = null;
  const rankedScorers = sortedScorers.map(([name, goals]) => {
    if (goals !== previousGoals) {
      previousGoals = goals;
      currentRank++;         
    }
    return { name, goals, team: determineTeam(name, fixtures), rank: currentRank };
  });

  return rankedScorers.slice(0, 20); 
}

function updateTopScorersTable(data) {
  const topScorersTableBody = document.getElementById('top-scorers-table');
  if (topScorersTableBody) {
    const topScorers = calculateTopScorers(data.fixtures);
    topScorersTableBody.innerHTML = ''; 
    topScorers.forEach(scorer => {
      const row = `<tr><td>${scorer.rank}</td><td>${scorer.name}</td><td>${scorer.team}</td><td>${scorer.goals}</td></tr>`;
      topScorersTableBody.innerHTML += row;
    });
  }
}

function updateLeagueTable(data) {
  const leagueTableBody = document.querySelector('#league-table');
  if (leagueTableBody) {
    leagueTableBody.innerHTML = '';
    const leagueData = calculateLeagueStatistics(data.fixtures);
    leagueData.forEach((team, index) => {
      const formString = getFormString(team.name, data.fixtures);
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${index + 1}</td>
        <td>${team.name}</td>
        <td>${team.points}</td>
        <td>${team.played}</td>
        <td>${team.won}</td>
        <td>${team.drawn}</td>
        <td>${team.lost}</td>
        <td>${team.goals_for}</td>
        <td>${team.goals_against}</td>
        <td>${team.goal_difference}</td>
        <td class="form">${formString.split('').map(result => `<span class="${result}">${result}</span>`).join('')}</td> 
      `;
      leagueTableBody.appendChild(row);
    });
  }
}

function updateData() {
  fetchData('League.json').then(data => {
    if (data) {
      updateLeagueTable(data);
      updateTopScorersTable(data);
    }
  });
}
function determineTeam(playerName, fixtures) {
  for (let fixture of fixtures) {
      if (fixture.home_scorers && fixture.home_scorers.includes(playerName)) {
          return fixture.home_team;
      }
      if (fixture.away_scorers && fixture.away_scorers.includes(playerName)) {
          return fixture.away_team;
      }
  }
  return 'Unknown';
}

function updateData() {
  fetchData('League.json').then(data => {
      if (data) {
          updateLeagueTable(data);
          updateTopScorersTable(data);
      }
      setTimeout(updateData, 5000); 
  });
}

document.addEventListener('DOMContentLoaded', () => {
  updateData(); 
});
