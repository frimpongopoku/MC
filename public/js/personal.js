new Chart(document.getElementById("line-chart"),{
  type: 'line',
  data: {
    labels: [1500,1600,1700,1750,1800,1850,1900,1950,1999,2050],
    datasets: [{ 
        data: [86,114,106,106,107,111,133,221,783,2478],
        label: "Africa",
        borderColor: "#3e95cd",
        fill: false
      }, { 
        data: [282,350,411,502,635,809,947,1402,3700,5267],
        label: "Asia",
        borderColor: "#8e5ea2",
        fill: false
      }, { 
        data: [168,170,178,190,203,276,408,547,675,734],
        label: "Europe",
        borderColor: "#3cba9f",
        fill: false
      }, { 
        data: [40,20,10,16,24,38,74,167,508,784],
        label: "Latin America",
        borderColor: "#e8c3b9",
        fill: false
      }, { 
        data: [6,3,2,2,7,26,82,172,312,433],
        label: "North America",
        borderColor: "#c45850",
        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: 'World population per region (in millions)'
    }
  }
});

// pie charts
new Chart(document.getElementById("pie-chart"), {
  type: 'pie',
  data: {
    labels: ["Girls", "Boys"],
    datasets: [{
      label: "Users",
      backgroundColor: ["#3e95cd", "#8e5ea2",],
      data: [2478,5267]
    }]
  },
  options: {
    title: {
      display: true,
      text: 'statistic of girls to boys on the platform'
    }
  }
});

// bar chart upload

new Chart(document.getElementById("bar-chart1"), {
  type: 'bar',
  data: {
    labels: ["KNUST", "UG", "UDS", "UCC", "Ashesi","Webster", "ANU", "UMT", "AIG", "CSUS", "Islamic U","Methodist", "Penticost", "presby", "CUC", "CIBT", "GIL", "MERITIME", "Blue C"],
    datasets: [
      {
        label: "uploads",
        backgroundColor: ['#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6', 
                          '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D',
                          '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A', 
                          '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A'],
        data: [24,78,52,67,73,44,78,48,43,39,67,90,50,72,23,55,29,80,100]
      }
    ]
  },
  options: {
    legend: { display: false },
    title: {
      display: true,
      text: 'Stats of uplaod from universities'
    }
  }
});
// bar chart download

new Chart(document.getElementById("bar-chart2"), {
  type: 'bar',
  data: {
    labels: ["KNUST", "UG", "UDS", "UCC", "Ashesi","Webster", "ANU", "UMT", "AIG", "CSUS", "Islamic U","Methodist", "Penticost", "presby", "CUC", "CIBT", "GIL", "MERITIME", "Blue C"],
    datasets: [
      {
        label: "download",
        backgroundColor: ['#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6', 
                          '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D',
                          '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A', 
                          '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A'],
        data: [24,58,52,67,83,44,78,28,43,79,67,90,50,52,23,55,99,80,100]
      }
    ]
  },
  options: {
    legend: { display: false },
    title: {
      display: true,
      text: 'Stats of uplaod from universities'
    }
  }
});
