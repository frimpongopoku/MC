new Chart(document.getElementById("shipment-to-days-chart"),{
  type: 'line',
  data: {...sales_chart_data,labels:lab},
  options: {
    title: {
      display: true,
      text: 'Sales (KES) Per Item Per Shipment - Figure A'
    }
  }
});
new Chart(document.getElementById("s_p_ship"), {
  type: 'bar',
  data: {...s_p_s,labels:s_lab},
  options: {
    legend: { display: false },
    title: {
      display: true,
      text: 'Sales Per Shipment - Figure B'
    }
  }
});
new Chart(document.getElementById("diff_in_ship"), {
  type: 'bar',
  data: {...diff,labels:diff_lab},
  options: {
    legend: { display: false },
    title: {
      display: true,
      text: 'Difference In Expected Income And Real Sales Made Per Shipment - Figure C'
    }
  }
});
